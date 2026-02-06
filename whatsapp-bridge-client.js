/**
 * WhatsApp Bridge Client
 * 
 * Bot ini dijalankan di PC Rumah/Laptop
 * Fungsi: Polling pesan dari VPS Hostinger, lalu kirim via WhatsApp (Baileys)
 * 
 * Setup:
 * 1. Install Node.js di PC rumah
 * 2. Copy file ini ke folder (misal: ~/whatsapp-bridge/)
 * 3. Install dependecies: npm install @whiskeysockets/baileys axios qrcode-terminal
 * 4. Jalankan: node whatsapp-bridge.js
 * 5. Scan QR code dengan WhatsApp di HP
 * 
 * Environment Variables (bisa pakai .env atau hardcode):
 * - VPS_URL: https://vps-hostinger.com
 * - BOT_TOKEN: secret token dari VPS
 * - POLL_INTERVAL: 30000 (30 detik)
 */

import { default as makeWASocket, useMultiFileAuthState, DisconnectReason, fetchLatestBaileysVersion, makeCacheableSignalKeyStore } from '@whiskeysockets/baileys';
import qrcode from 'qrcode-terminal';
import axios from 'axios';
import fs from 'fs';
import path from 'path';
import pino from 'pino';

// ========== CONFIGURATION ==========
const CONFIG = {
    VPS_URL: (process.env.VPS_URL || 'https://ayo-kos.com').replace(/\/$/, ''), // Hapus trailing slash
    BOT_TOKEN: process.env.BOT_TOKEN || '1234567890mrizki',
    POLL_INTERVAL: parseInt(process.env.POLL_INTERVAL) || 30000, // 30 detik
    RATE_LIMIT_DELAY: 5000, // 5 detik antar pesan
    MAX_RETRY_ATTEMPTS: 3,
    AUTH_FOLDER: './auth_info'
};

// ========== SAFE LOGGING ==========
class SafeConsole {
    static log(...args) {
        console.log(`[${new Date().toISOString()}]`, ...args);
    }
    static error(...args) {
        console.error(`[${new Date().toISOString()}]`, ...args);
    }
}

// ========== WHATSAPP BRIDGE CLASS ==========
class WhatsAppBridge {
    constructor() {
        this.sock = null;
        this.isConnected = false;
        this.pollInterval = null;
        this.reconnectAttempts = 0;
        this.lastPollTime = 0;
        
        SafeConsole.log('🚀 WhatsApp Bridge initializing...');
        SafeConsole.log('📡 VPS:', CONFIG.VPS_URL);
        SafeConsole.log('⏱️ Poll Interval:', CONFIG.POLL_INTERVAL + 'ms');
        
        this.init();
    }

    async init() {
        try {
            // Buat auth folder jika belum ada
            if (!fs.existsSync(CONFIG.AUTH_FOLDER)) {
                fs.mkdirSync(CONFIG.AUTH_FOLDER, { recursive: true });
            }

            const { state, saveCreds } = await useMultiFileAuthState(CONFIG.AUTH_FOLDER);
            const { version } = await fetchLatestBaileysVersion();
            
            SafeConsole.log('📡 Creating WhatsApp socket...');
            
            this.sock = makeWASocket({
                version,
                logger: pino({ level: 'error' }),
                auth: {
                    creds: state.creds,
                    keys: makeCacheableSignalKeyStore(state.keys, pino({ level: 'fatal' })),
                },
                browser: ['Chrome (Linux)', '', ''],
                generateHighQualityLinkPreview: true,
                syncFullHistory: false,
                connectTimeoutMs: 60000,
                defaultQueryTimeoutMs: 60000,
                emitOwnEvents: true,
                markOnlineOnConnect: false,
                keepAliveIntervalMs: 60000,
            });

            // Setup event handlers
            this.sock.ev.on('creds.update', saveCreds);
            this.sock.ev.on('connection.update', this.handleConnectionUpdate.bind(this));

            // Start polling VPS
            this.startPolling();

        } catch (error) {
            SafeConsole.error('❌ Error initializing:', error.message);
            this.handleReconnect();
        }
    }

    handleConnectionUpdate(update) {
        const { connection, lastDisconnect, qr } = update;
        
        if (qr) {
            SafeConsole.log('📲 Scan QR Code dengan WhatsApp di HP Anda...');
            qrcode.generate(qr, { small: true });
            this.isConnected = false;
        }
        
        if (connection === 'open') {
            this.isConnected = true;
            this.reconnectAttempts = 0;
            SafeConsole.log('✅ WhatsApp CONNECTED!');
            SafeConsole.log('✅ Bridge is ready to send messages from VPS');
            this.updateVpsStatus('online');
        }
        
        if (connection === 'close') {
            SafeConsole.log('🔌 Connection closed');
            this.isConnected = false;
            
            const statusCode = lastDisconnect?.error?.output?.statusCode;
            
            if (statusCode === DisconnectReason.loggedOut) {
                SafeConsole.log('🚨 Logged out, cleaning auth...');
                this.cleanAuth();
                setTimeout(() => this.init(), 10000);
            } else if (statusCode === DisconnectReason.restartRequired) {
                SafeConsole.log('🔄 Restart required...');
                setTimeout(() => this.init(), 10000);
            } else if (statusCode === DisconnectReason.connectionClosed) {
                SafeConsole.log('🔌 Connection closed, reconnecting...');
                this.handleReconnect();
            } else {
                SafeConsole.log('🔄 Reconnecting...');
                this.handleReconnect();
            }
        }
        
        if (connection === 'connecting') {
            SafeConsole.log('🔄 Connecting to WhatsApp...');
        }
    }

    async handleReconnect() {
        if (this.reconnectAttempts >= 3) {
            SafeConsole.error('🚨 Max reconnection attempts reached, waiting 5 minutes...');
            this.updateVpsStatus('offline', 'Max reconnection attempts reached');
            setTimeout(() => {
                this.reconnectAttempts = 0;
                this.init();
            }, 300000); // 5 menit
            return;
        }
        
        this.reconnectAttempts++;
        const delay = Math.min(10000 * Math.pow(2, this.reconnectAttempts - 1), 60000);
        
        SafeConsole.log(`🔄 Reconnect attempt ${this.reconnectAttempts}/3 in ${delay/1000}s...`);
        this.updateVpsStatus('connecting', `Reconnect attempt ${this.reconnectAttempts}`);
        
        setTimeout(() => this.init(), delay);
    }

    cleanAuth() {
        try {
            if (fs.existsSync(CONFIG.AUTH_FOLDER)) {
                const files = fs.readdirSync(CONFIG.AUTH_FOLDER);
                files.forEach(file => {
                    if (file.endsWith('.json')) {
                        fs.unlinkSync(path.join(CONFIG.AUTH_FOLDER, file));
                    }
                });
            }
        } catch (error) {
            SafeConsole.error('Error cleaning auth:', error.message);
        }
    }

    // ========== POLLING VPS ==========
    startPolling() {
        SafeConsole.log('🔍 Starting VPS polling...');
        
        // Poll pertama kali setelah 30 detik
        setTimeout(() => this.pollVps(), 30000);
        
        // Poll berikutnya setiap interval
        this.pollInterval = setInterval(() => {
            this.pollVps();
        }, CONFIG.POLL_INTERVAL);
    }

    async pollVps() {
        if (!this.isConnected) {
            SafeConsole.log('⚠️ WhatsApp not connected, skipping poll...');
            return;
        }

        const now = Date.now();
        const timeSinceLastPoll = now - this.lastPollTime;
        
        // Rate limiting untuk polling
        if (timeSinceLastPoll < CONFIG.POLL_INTERVAL) {
            return;
        }
        
        this.lastPollTime = now;

        try {
            SafeConsole.log('📡 Polling VPS for pending messages...');
            
            const response = await axios.get(`${CONFIG.VPS_URL}/api/whatsapp-bridge/pending`, {
                headers: {
                    'X-Bot-Token': CONFIG.BOT_TOKEN,
                    'Content-Type': 'application/json'
                },
                timeout: 30000
            });

            if (!response.data.success) {
                SafeConsole.error('❌ VPS returned error:', response.data.error);
                return;
            }

            const messages = response.data.data;
            
            if (messages.length === 0) {
                SafeConsole.log('📭 No pending messages');
                return;
            }

            SafeConsole.log(`📨 Found ${messages.length} pending messages`);

            // Proses satu per satu dengan delay
            for (const message of messages) {
                await this.sendMessage(message);
                
                // Rate limiting: tunggu 5 detik antar pesan
                if (messages.indexOf(message) < messages.length - 1) {
                    SafeConsole.log(`⏳ Waiting ${CONFIG.RATE_LIMIT_DELAY/1000}s before next message...`);
                    await this.sleep(CONFIG.RATE_LIMIT_DELAY);
                }
            }

        } catch (error) {
            if (error.code === 'ECONNREFUSED') {
                SafeConsole.error('❌ Cannot connect to VPS. Check VPS_URL configuration.');
            } else if (error.response?.status === 401) {
                SafeConsole.error('❌ Invalid BOT_TOKEN. Check your token configuration.');
            } else {
                SafeConsole.error('❌ Error polling VPS:', error.message);
            }
        }
    }

    async sendMessage(message) {
        try {
            const { id, phone, message: text } = message;
            
            SafeConsole.log(`📤 Sending to ${phone}: ${text.substring(0, 50)}...`);

            // Format JID
            const jid = phone.includes('@') ? phone : `${phone}@s.whatsapp.net`;
            
            // Kirim pesan dengan timeout 60 detik
            const timeout = new Promise((_, reject) => 
                setTimeout(() => reject(new Error('Send timeout')), 60000)
            );
            
            const sendPromise = this.sock.sendMessage(jid, { text });
            const result = await Promise.race([sendPromise, timeout]);
            
            // Update VPS: message sent
            await this.markAsSent(id, result?.key?.id);
            
            SafeConsole.log(`✅ Message sent successfully (ID: ${result?.key?.id})`);

        } catch (error) {
            SafeConsole.error(`❌ Failed to send message:`, error.message);
            
            // Update VPS: message failed
            await this.markAsFailed(message.id, error.message);
            
            // Jika error berkaitan dengan banned, update status
            if (error.message.includes('banned') || error.message.includes('blocked')) {
                this.updateVpsStatus('banned', error.message);
            }
        }
    }

    // ========== API CALLS TO VPS ==========
    async markAsSent(messageId, whatsappMessageId) {
        try {
            await axios.post(`${CONFIG.VPS_URL}/api/whatsapp-bridge/mark-sent`, {
                id: messageId,
                whatsapp_message_id: whatsappMessageId
            }, {
                headers: {
                    'X-Bot-Token': CONFIG.BOT_TOKEN,
                    'Content-Type': 'application/json'
                },
                timeout: 10000
            });
        } catch (error) {
            SafeConsole.error('❌ Error marking as sent:', error.message);
        }
    }

    async markAsFailed(messageId, errorMessage) {
        try {
            await axios.post(`${CONFIG.VPS_URL}/api/whatsapp-bridge/mark-failed`, {
                id: messageId,
                error: errorMessage
            }, {
                headers: {
                    'X-Bot-Token': CONFIG.BOT_TOKEN,
                    'Content-Type': 'application/json'
                },
                timeout: 10000
            });
        } catch (error) {
            SafeConsole.error('❌ Error marking as failed:', error.message);
        }
    }

    async updateVpsStatus(status, message = null) {
        try {
            await axios.post(`${CONFIG.VPS_URL}/api/whatsapp-bridge/bot-status`, {
                status: status,
                message: message
            }, {
                headers: {
                    'X-Bot-Token': CONFIG.BOT_TOKEN,
                    'Content-Type': 'application/json'
                },
                timeout: 10000
            });
        } catch (error) {
            // Silent error untuk status update
        }
    }

    // ========== UTILITIES ==========
    sleep(ms) {
        return new Promise(resolve => setTimeout(resolve, ms));
    }

    shutdown() {
        SafeConsole.log('🛑 Shutting down WhatsApp Bridge...');
        
        if (this.pollInterval) {
            clearInterval(this.pollInterval);
        }
        
        if (this.sock) {
            this.sock.end();
        }
        
        this.updateVpsStatus('offline', 'Bot shutdown');
    }
}

// ========== START ==========
const bridge = new WhatsAppBridge();

// Handle graceful shutdown
process.on('SIGINT', () => {
    bridge.shutdown();
    process.exit(0);
});

process.on('SIGTERM', () => {
    bridge.shutdown();
    process.exit(0);
});

// Handle uncaught errors
process.on('uncaughtException', (error) => {
    SafeConsole.error('💥 Uncaught Exception:', error.message);
    bridge.shutdown();
    process.exit(1);
});

process.on('unhandledRejection', (reason) => {
    SafeConsole.error('💥 Unhandled Rejection:', reason);
});

SafeConsole.log('🚀 WhatsApp Bridge Client Started!');
SafeConsole.log('📝 Make sure to set VPS_URL and BOT_TOKEN environment variables');
SafeConsole.log('⏳ Waiting for WhatsApp connection...');
