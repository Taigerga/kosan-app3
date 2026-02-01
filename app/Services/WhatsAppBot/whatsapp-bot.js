import makeWASocket, {
    useMultiFileAuthState,
    DisconnectReason,
    fetchLatestBaileysVersion,
    makeCacheableSignalKeyStore
} from '@whiskeysockets/baileys';

import pino from 'pino';
import qrcode from 'qrcode-terminal';
import fs from 'fs';
import path from 'path';
import { fileURLToPath } from 'url';

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

class WhatsAppBot {
    constructor() {
        this.sock = null;
        this.isConnected = false;
        this.isReconnecting = false;

        // 1. FOLDER AUTH (Untuk simpan session login)
        this.authFolder = path.join(__dirname, 'auth_info');
        if (!fs.existsSync(this.authFolder)) {
            fs.mkdirSync(this.authFolder, { recursive: true });
        }

        // 2. FILE ANTRIAN (Queue File)
        const projectRoot = path.resolve(__dirname, '..', '..', '..');
        this.queueFile = path.join(projectRoot, 'storage', 'app', 'whatsapp_messages.json');

        // Pastikan folder storage ada
        const queueDir = path.dirname(this.queueFile);
        if (!fs.existsSync(queueDir)) fs.mkdirSync(queueDir, { recursive: true });
        if (!fs.existsSync(this.queueFile)) fs.writeFileSync(this.queueFile, '[]');

        // 3. KONTROL ANTI-BAN
        this.dailyLimit = 60; // Batas pesan per hari
        this.sentToday = 0;
        this.lastReset = new Date().toDateString();

        this.isProcessing = false;
        this.init();
        this.startQueueWatcher();
    }

    async cleanupConnection() {
        if (this.sock) {
            try {
                this.sock.ev.removeAllListeners('connection.update');
                this.sock.ev.removeAllListeners('creds.update');
                await this.sock.end();
                console.log('🧹 Socket lama dibersihkan');
            } catch (err) {
                console.log('⚠️ Error saat cleanup socket:', err.message);
            }
            this.sock = null;
        }
        this.isConnected = false;
    }

    async init() {
        const { state, saveCreds } = await useMultiFileAuthState(this.authFolder);
        const { version } = await fetchLatestBaileysVersion();

        console.log('🚀 Menghubungkan ke WhatsApp...');

        this.sock = makeWASocket({
            version,
            auth: {
                creds: state.creds,
                keys: makeCacheableSignalKeyStore(state.keys, pino({ level: 'fatal' }))
            },
            logger: pino({ level: 'silent' }),
            printQRInTerminal: false,
            // Browser agar terlihat seperti login dari Chrome Windows
            browser: ['Windows', 'Chrome', '110.0.5481.178'],
            syncFullHistory: false
        });

        this.sock.ev.on('creds.update', saveCreds);
        this.sock.ev.on('connection.update', this.onConnection.bind(this));
    }

    onConnection({ connection, lastDisconnect, qr }) {
        if (qr) {
            console.log('📲 Silakan scan QR Code ini:');
            qrcode.generate(qr, { small: true });
        }

        if (connection === 'open') {
            console.log('✅ WhatsApp TERHUBUNG!');
            this.isConnected = true;
            this.isReconnecting = false;
        }

        if (connection === 'close') {
            this.isConnected = false;
            const shouldReconnect = (lastDisconnect?.error)?.output?.statusCode !== DisconnectReason.loggedOut;

            console.log('🔌 Koneksi terputus. Sebab:', lastDisconnect?.error?.message);

            if (shouldReconnect && !this.isReconnecting) {
                this.isReconnecting = true;
                console.log('🔄 Menunggu 10 detik sebelum reconnect...');
                setTimeout(async () => {
                    await this.cleanupConnection();
                    this.isReconnecting = false;
                    this.init();
                }, 10000);
            } else if (shouldReconnect) {
                console.log('⏳ Reconnect sedang berlangsung, skip...');
            } else {
                console.log('🚨 Sesi dikeluarkan (Logged Out). Hapus folder auth_info dan scan ulang.');
            }
        }
    }

    // Fungsi jeda acak agar tidak terlihat seperti robot
    async humanDelay(min = 20000, max = 50000) {
        const delay = Math.floor(Math.random() * (max - min + 1)) + min;
        console.log(`⏳ Menunggu jeda aman: ${delay / 1000} detik...`);
        return new Promise(resolve => setTimeout(resolve, delay));
    }

    startQueueWatcher() {
        // Log status setiap 30 detik
        setInterval(() => {
            const now = new Date();
            console.log(`📊 [${now.toLocaleTimeString()}] Status: connected=${this.isConnected}, processing=${this.isProcessing}, sentToday=${this.sentToday}/${this.dailyLimit}`);
        }, 30000);

        // Gunakan timeout rekursif agar tidak tumpang tindih (anti-spam)
        const check = async () => {
            await this.processQueue();
            setTimeout(check, 10000); // Tunggu 10 detik setelah proses selesai baru cek lagi
        };
        check();
    }

    async processQueue() {
        if (this.isProcessing || !this.isConnected || !this.sock) {
            console.log(`⏸️ Skip: processing=${this.isProcessing}, connected=${this.isConnected}, hasSock=${!!this.sock}`);
            return;
        }

        this.isProcessing = true;
        try {
            // --- FILTER 1: JAM OPERASIONAL (06:00 - 21:00) ---
            const hour = new Date().getHours();
            console.log(`🕐 Current hour: ${hour}`);
            if (hour < 6 || hour >= 21) {
                console.log('⏰ Di luar jam operasional (06:00-21:00). Skip proses.');
                return;
            }

            // --- FILTER 2: RESET LIMIT HARIAN ---
            const today = new Date().toDateString();
            if (this.lastReset !== today) {
                this.sentToday = 0;
                this.lastReset = today;
                console.log('🌅 Hari baru dimulai, limit harian direset.');
            }

            if (this.sentToday >= this.dailyLimit) {
                console.log(`🚫 Limit harian (${this.dailyLimit}) tercapai. Berhenti mengirim.`);
                return;
            }

            // --- PROSES AMBIL DATA ---
            let messages;
            try {
                messages = JSON.parse(fs.readFileSync(this.queueFile, 'utf8'));
            } catch (e) {
                console.error('❌ Error baca file:', e.message);
                return;
            }

            const pending = messages.filter(m => m.status !== 'sent');
            console.log(`📊 Total pesan: ${messages.length}, Pending: ${pending.length}`);
            if (pending.length === 0) {
                console.log('✅ Tidak ada pesan pending.');
                return;
            }

            // Ambil pesan pertama dari antrean
            const msg = pending[0];
            const jid = msg.phone.replace(/\D/g, '') + '@s.whatsapp.net';

            console.log(`📩 Menyiapkan pesan untuk: ${msg.phone}`);

            try {
                // --- FILTER 3: JEDA SEBELUM KIRIM ---
                await this.humanDelay();

                // --- FILTER 4: FITUR MENGETIK (TYPING) ---
                await this.sock.sendPresenceUpdate('composing', jid);
                await new Promise(resolve => setTimeout(resolve, 3000)); // Pura-pura ngetik 3 detik

                // KIRIM PESAN
                await this.sock.sendMessage(jid, { text: msg.message });

                // UPDATE STATUS KE FILE JSON
                msg.status = 'sent';
                msg.sent_at = new Date().toISOString();
                this.sentToday++;

                fs.writeFileSync(this.queueFile, JSON.stringify(messages, null, 2));
                console.log(`✅ Berhasil terkirim ke ${msg.phone}. (Hari ini: ${this.sentToday}/${this.dailyLimit})`);

            } catch (err) {
                console.error('❌ Gagal mengirim:', err.message);
                // Jika gagal karena koneksi, biarkan status tetap pending untuk dicoba lagi nanti
            }
        } catch (error) {
            console.error('💥 Error in processQueue:', error.message);
        } finally {
            this.isProcessing = false;
        }
    }
}

// Jalankan Bot
new WhatsAppBot();