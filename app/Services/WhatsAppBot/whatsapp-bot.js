import { default as makeWASocket, useMultiFileAuthState, DisconnectReason, fetchLatestBaileysVersion, makeCacheableSignalKeyStore } from '@whiskeysockets/baileys';
import { Boom } from '@hapi/boom';
import pino from 'pino';
import qrcode from 'qrcode-terminal';
import fs from 'fs';
import path from 'path';
import { fileURLToPath } from 'url';

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

// ========== PERBAIKAN 1: Safe Console Logging ==========
class SafeConsole {
    static log(...args) {
        try {
            // Always try console log first, but wrap in try/catch
            console.log(...args);
        } catch (error) {
            this.fallbackToFile('LOG', args);
        }
    }

    static error(...args) {
        try {
            console.error(...args);
        } catch (error) {
            this.fallbackToFile('ERROR', args);
        }
    }

    static fallbackToFile(level, args) {
        try {
            const message = args.map(arg => 
                typeof arg === 'object' ? JSON.stringify(arg) : String(arg)
            ).join(' ');
            fs.appendFileSync('whatsapp-bot-fallback.log', 
                `[${new Date().toISOString()}] ${level}: ${message}\n`
            );
        } catch (e) {
            // If even disk writing fails, we stop to prevent infinite recursion
        }
    }
}

// ========== PERBAIKAN 2: Setup Process Handlers ==========
function setupProcessHandlers() {
    // 1. SILENCE EPIPE globally
    // This prevents the process from crashing if the terminal closes
    process.stdout.on('error', (err) => {
        if (err.code === 'EPIPE') return; 
    });

    process.on('warning', (warning) => {
        if (warning.name === 'ExperimentalWarning') return;
    });

    // 2. Enhanced Uncaught Exception Handler
    process.on('uncaughtException', (error) => {
        // Check if it's a pipe error to prevent loop
        if (error.code === 'EPIPE') return;

        // Use fs.writeSync for emergencies because it's synchronous and doesn't rely on streams
        const errorLog = `\n💥 [${new Date().toISOString()}] UNCAUGHT EXCEPTION: ${error.message}\n${error.stack}\n`;
        try {
            fs.appendFileSync('critical-errors.log', errorLog);
        } catch (e) {
            // Nowhere left to log
        }
    });

    process.on('unhandledRejection', (reason, promise) => {
        // Silent catch for specific Baileys/Socket rejections that are common
    });
}

// Panggil setup handlers
setupProcessHandlers();

class WhatsAppBot {
    constructor() {
        this.sock = null;
        this.isConnected = false;
        this.isReconnecting = false;
        this.reconnectAttempts = 0;
        this.maxReconnectAttempts = 3;
        this.authFolder = path.join(__dirname, 'auth_info');
        this.keepAliveInterval = null;
        this.messageQueueInterval = null;
        this.lastActivity = Date.now();
        this.lastMessageTime = 0;
        this.minMessageDelay = 3000;
        this.maxMessageDelay = 8000;
        this.messagesPerMinute = 0;
        this.messagesSentThisMinute = 0;
        this.startTime = Date.now();
        
        // Queue file path
        const projectRoot = path.resolve(__dirname, '..', '..', '..');
        this.messageQueueFile = path.join(projectRoot, 'storage', 'app', 'whatsapp_messages.json');
        
        // Pastikan directory exists
        const queueDir = path.dirname(this.messageQueueFile);
        if (!fs.existsSync(queueDir)) {
            fs.mkdirSync(queueDir, { recursive: true });
        }
        
        SafeConsole.log('🚀 WhatsApp Bot initializing...');
        SafeConsole.log('📁 Queue file:', this.messageQueueFile);
        
        this.init();
    }

    // Helper function untuk delay
    sleep(ms) {
        return new Promise(resolve => setTimeout(resolve, ms));
    }

    // Helper function untuk random delay
    getRandomDelay() {
        return Math.floor(Math.random() * (this.maxMessageDelay - this.minMessageDelay + 1)) + this.minMessageDelay;
    }

    // ========== PERBAIKAN 3: Initialize dengan error handling ==========
    async init() {
        try {
            if (this.isReconnecting) {
                SafeConsole.log('⚠️ Already reconnecting, skipping...');
                return;
            }

            // Cleanup existing connection
            this.cleanup();

            // Buat auth folder jika belum ada
            if (!fs.existsSync(this.authFolder)) {
                fs.mkdirSync(this.authFolder, { recursive: true });
            }

            const { state, saveCreds } = await useMultiFileAuthState(this.authFolder);
            const { version } = await fetchLatestBaileysVersion();
            
            SafeConsole.log('📡 Creating WhatsApp socket...');
            
            this.sock = makeWASocket({
                version,
                logger: pino({ level: 'error' }), // Minimal logging
                printQRInTerminal: false,
                auth: {
                    creds: state.creds,
                    keys: makeCacheableSignalKeyStore(state.keys, pino({ level: 'fatal' })),
                },
                generateHighQualityLinkPreview: true,
                syncFullHistory: false,
                keepAliveIntervalMs: 30000,
                connectTimeoutMs: 60000,
                defaultQueryTimeoutMs: 0,
                emitOwnEvents: true,
                browser: ['Chrome', 'Linux', '120.0.6099'],
            });

            // Setup event handlers
            this.sock.ev.on('creds.update', saveCreds);
            this.sock.ev.on('connection.update', this.handleConnectionUpdate.bind(this));
            this.sock.ev.on('messages.upsert', this.handleIncomingMessage.bind(this));

            // Start message watcher
            this.startMessageWatcher();

            // Start keep-alive
            this.startKeepAlive();

            SafeConsole.log('🤖 WhatsApp Bot initialized. Waiting for connection...');

        } catch (error) {
            SafeConsole.error('❌ Error initializing:', error.message);
            this.handleReconnect();
        }
    }

    // ========== PERBAIKAN 4: Handle Connection Update yang lebih aman ==========
    handleConnectionUpdate(update) {
        try {
            const { connection, lastDisconnect, qr } = update;
            
            if (qr) {
                SafeConsole.log('📲 Scan QR Code:');
                qrcode.generate(qr, { small: true });
                this.isConnected = false;
            }
            
            if (connection === 'close') {
                SafeConsole.log('🔌 Connection closed');
                this.isConnected = false;
                
                const statusCode = lastDisconnect?.error?.output?.statusCode;
                const error = lastDisconnect?.error;
                
                SafeConsole.log('Disconnect details:', {
                    statusCode,
                    error: error?.message
                });
                
                // Handle specific disconnect reasons
                if (statusCode === DisconnectReason.loggedOut) {
                    SafeConsole.log('🚨 Logged out, cleaning auth...');
                    this.cleanAuth();
                    setTimeout(() => this.init(), 10000); // Delay 10 detik
                } else if (statusCode === DisconnectReason.restartRequired) {
                    SafeConsole.log('🔄 Restart required, reconnecting...');
                    setTimeout(() => this.init(), 10000); // Delay 10 detik
                } else if (statusCode === DisconnectReason.connectionClosed) {
                    SafeConsole.log('🔌 Connection closed, reconnecting...');
                    this.handleReconnect();
                } else {
                    // Unknown/other errors - delay lebih lama
                    SafeConsole.log('🔄 Reconnecting due to unknown closure...');
                    setTimeout(() => this.handleReconnect(), 15000); // Delay 15 detik
                }
            }
            
            if (connection === 'open') {
                this.isConnected = true;
                this.reconnectAttempts = 0;
                this.lastActivity = Date.now();
                SafeConsole.log('✅ WhatsApp CONNECTED!');
                SafeConsole.log('⏳ Waiting 10 seconds before sending messages...');
                // Delay 10 detik saat connect pertama untuk menghindari deteksi bot
                this.sleep(10000).then(() => {
                    SafeConsole.log('✅ Ready to send messages');
                });
            }
            
            if (connection === 'connecting') {
                SafeConsole.log('🔄 Connecting...');
            }
            
        } catch (error) {
            SafeConsole.error('Error in connection update:', error.message);
        }
    }

    // ========== PERBAIKAN 5: Handle Reconnect dengan protection ==========
    async handleReconnect() {
        if (this.isReconnecting) {
            SafeConsole.log('⚠️ Reconnection already in progress');
            return;
        }
        
        this.isReconnecting = true;
        
        if (this.reconnectAttempts >= this.maxReconnectAttempts) {
            SafeConsole.error('🚨 Max reconnection attempts reached, waiting 5 minutes...');
            // Tunggu 5 menit sebelum reset counter
            setTimeout(() => {
                this.reconnectAttempts = 0;
                this.isReconnecting = false;
                this.handleReconnect();
            }, 300000);
            return;
        }
        
        this.reconnectAttempts++;
        
        // Exponential backoff dengan delay lebih lama
        const delay = Math.min(10000 * Math.pow(2, this.reconnectAttempts - 1), 120000);
        
        SafeConsole.log(`🔄 Reconnect attempt ${this.reconnectAttempts}/${this.maxReconnectAttempts} in ${delay/1000}s...`);
        
        // Cleanup current connection
        this.cleanup();
        
        // Delay sebelum reconnect
        setTimeout(async () => {
            try {
                await this.init();
            } catch (error) {
                SafeConsole.error('❌ Reconnect failed:', error.message);
            } finally {
                this.isReconnecting = false;
            }
        }, delay);
    }

    // ========== PERBAIKAN 6: Keep-alive untuk maintain connection ==========
    startKeepAlive() {
        if (this.keepAliveInterval) clearInterval(this.keepAliveInterval);
        
        this.keepAliveInterval = setInterval(() => {
            if (!this.sock || !this.isConnected) return;
            
            try {
                this.lastActivity = Date.now();
                
                SafeConsole.log('❤️ Keep-alive check');
            } catch (error) {
                SafeConsole.log('⚠️ Keep-alive failed:', error.message);
                this.isConnected = false;
            }
        }, 240000); // Setiap 4 menit (dari 25 detik)
    }

    // ========== PERBAIKAN 7: Cleanup yang lebih baik ==========
    cleanup() {
        try {
            // Stop intervals
            if (this.keepAliveInterval) {
                clearInterval(this.keepAliveInterval);
                this.keepAliveInterval = null;
            }
            
            if (this.messageQueueInterval) {
                clearInterval(this.messageQueueInterval);
                this.messageQueueInterval = null;
            }
            
            // Cleanup socket - lebih gentle, jangan paksa close WS
            if (this.sock) {
                try {
                    // Remove all listeners
                    this.sock.ev.removeAllListeners();
                    
                    // End connection dengan cara gentle (tanpa error parameter)
                    this.sock.end();
                    
                } catch (sockError) {
                    // Ignore cleanup errors
                }
                
                this.sock = null;
            }
            
            this.isConnected = false;
            
        } catch (error) {
            SafeConsole.error('Error during cleanup:', error.message);
        }
    }

    // ========== PERBAIKAN 8: Shutdown method ==========
    shutdown() {
        SafeConsole.log('🛑 Shutting down WhatsApp bot...');
        
        this.cleanup();
        
        // Hapus global reference
        if (global.whatsappBot === this) {
            global.whatsappBot = null;
        }
    }

    // ========== PERBAIKAN 9: Clean auth files ==========
    cleanAuth() {
        try {
            if (fs.existsSync(this.authFolder)) {
                const files = fs.readdirSync(this.authFolder);
                files.forEach(file => {
                    if (file.endsWith('.json')) {
                        fs.unlinkSync(path.join(this.authFolder, file));
                    }
                });
                SafeConsole.log('🧹 Cleaned auth files');
            }
        } catch (error) {
            SafeConsole.error('Error cleaning auth:', error.message);
        }
    }

    // ========== PERBAIKAN 10: Message watcher yang lebih reliable ==========
    startMessageWatcher() {
        SafeConsole.log('🔍 Starting message watcher...');
        
        // Hentikan interval sebelumnya jika ada
        if (this.messageQueueInterval) {
            clearInterval(this.messageQueueInterval);
        }
        
        this.messageQueueInterval = setInterval(() => {
            this.processMessageQueue();
        }, 30000); // Cek setiap 30 detik (dari 5 detik)
    }

    // ========== PERBAIKAN 11: Process message queue ==========
    async processMessageQueue() {
        if (!this.isConnected || !this.sock) {
            SafeConsole.log('⚠️ Skipping queue check - not connected');
            return;
        }

        try {
            if (!fs.existsSync(this.messageQueueFile)) {
                // Buat file kosong jika tidak ada
                fs.writeFileSync(this.messageQueueFile, '[]');
                return;
            }
            
            const data = fs.readFileSync(this.messageQueueFile, 'utf8').trim();
            
            if (!data) {
                return;
            }
            
            let messages;
            try {
                messages = JSON.parse(data);
            } catch (parseError) {
                SafeConsole.error('❌ JSON parse error:', parseError.message);
                // Backup corrupt file
                const backupFile = `${this.messageQueueFile}.corrupt.${Date.now()}`;
                fs.copyFileSync(this.messageQueueFile, backupFile);
                // Reset file
                fs.writeFileSync(this.messageQueueFile, '[]');
                return;
            }
            
            const pendingMessages = messages.filter(msg => 
                msg.status === 'pending' || !msg.hasOwnProperty('status')
            );
            
            if (pendingMessages.length === 0) {
                return;
            }
            
            // Reset counter setiap menit
            const now = Date.now();
            if (now - this.startTime > 60000) {
                this.startTime = now;
                this.messagesSentThisMinute = 0;
            }
            
            // Rate limiting: maksimal 5 pesan per menit
            if (this.messagesSentThisMinute >= 5) {
                SafeConsole.log('⚠️ Rate limit reached, waiting...');
                return;
            }
            
            SafeConsole.log(`📨 Found ${pendingMessages.length} pending messages`);
            
            for (const message of pendingMessages) {
                // Cek lagi rate limit di setiap iterasi
                if (this.messagesSentThisMinute >= 5) {
                    SafeConsole.log('⚠️ Rate limit reached, stopping for now');
                    break;
                }
                
                const phone = message.phone || message.to || message.recipient;
                const text = message.message || message.text || message.content;
                
                if (!phone || !text) {
                    SafeConsole.error('❌ Invalid message format:', message);
                    continue;
                }
                
                // Delay antar pesan (random 3-8 detik)
                const delay = this.getRandomDelay();
                SafeConsole.log(`⏳ Waiting ${delay/1000}s before sending...`);
                await this.sleep(delay);
                
                SafeConsole.log(`📤 Sending to ${phone}: ${text.substring(0, 50)}...`);
                
                const result = await this.sendMessage(phone, text);
                
                if (result.success) {
                    this.messagesSentThisMinute++;
                    // Update status
                    const updatedMessages = messages.map(msg => {
                        if ((msg.id && msg.id === message.id) || 
                            (msg.phone === phone && msg.message === text)) {
                            return {
                                ...msg,
                                status: 'sent',
                                sent_at: new Date().toISOString(),
                                error: null
                            };
                        }
                        return msg;
                    });
                    
                    fs.writeFileSync(this.messageQueueFile, JSON.stringify(updatedMessages, null, 2));
                    SafeConsole.log(`✅ Message sent successfully (${this.messagesSentThisMinute}/5 this minute)`);
                } else {
                    SafeConsole.error(`❌ Failed to send:`, result.error);
                }
            }
            
        } catch (error) {
            SafeConsole.error('💥 Error processing queue:', error.message);
        }
    }

    // ========== PERBAIKAN 12: Send message dengan timeout ==========
    async sendMessage(phone, message) {
        try {
            if (!this.isConnected || !this.sock) {
                throw new Error('WhatsApp not connected');
            }

            // Format phone number
            const jid = phone.includes('@s.whatsapp.net') ? phone : `${phone.replace(/[^0-9]/g, '')}@s.whatsapp.net`;
            
            SafeConsole.log(`📤 Sending to ${jid}...`);
            
            // Send dengan timeout yang lebih panjang (60 detik)
            const timeout = new Promise((_, reject) => 
                setTimeout(() => reject(new Error('Send timeout')), 60000)
            );
            
            const sendPromise = this.sock.sendMessage(jid, { text: message });
            
            await Promise.race([sendPromise, timeout]);
            
            this.lastActivity = Date.now();
            
            return { success: true };
            
        } catch (error) {
            SafeConsole.error('❌ Send message error:', error.message);
            
            // Jika connection error, trigger reconnect dengan delay
            if (error.message.includes('not connected') || 
                error.message.includes('timeout') ||
                error.message.includes('EPIPE')) {
                this.isConnected = false;
                // Delay lebih lama sebelum reconnect (30 detik)
                setTimeout(() => this.handleReconnect(), 30000);
            }
            
            return { 
                success: false, 
                error: error.message 
            };
        }
    }

    // ========== PERBAIKAN 13: Handle incoming messages ==========
    handleIncomingMessage({ messages }) {
        try {
            for (const msg of messages) {
                if (!msg.message) continue;
                
                const sender = msg.key.remoteJid;
                const text = msg.message.conversation || 
                            msg.message.extendedTextMessage?.text ||
                            '';
                
                SafeConsole.log(`📥 Incoming from ${sender}: ${text.substring(0, 50)}...`);
                
                // Anda bisa tambahkan logic untuk handle pesan masuk di sini
                // Contoh: auto-reply, command handling, dll.
            }
        } catch (error) {
            SafeConsole.error('Error handling incoming message:', error.message);
        }
    }
}

// ========== PERBAIKAN 14: Start bot dengan error handling ==========
try {
    SafeConsole.log('🚀 Starting WhatsApp Bot Service...');
    global.whatsappBot = new WhatsAppBot();
} catch (error) {
    SafeConsole.error('💥 Failed to start WhatsApp bot:', error);
    process.exit(1);
}