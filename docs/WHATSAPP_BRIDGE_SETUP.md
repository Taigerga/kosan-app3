# WhatsApp Hybrid Bridge - Setup Guide

## 🏗️ Arsitektur Hybrid (VPS → PC Rumah → WhatsApp)

```
┌─────────────────────────────────────────────────────────────┐
│  VPS HOSTINGER (Laravel)                                    │
│  ┌─────────────────────────────────────────┐               │
│  │  Backend / API                          │               │
│  │  ┌─────────────────────────────────┐   │               │
│  │  │  WhatsAppMessage Model          │   │               │
│  │  │  (Database Queue)               │   │               │
│  │  └─────────────────────────────────┘   │               │
│  │                                          │               │
│  │  ┌─────────────────────────────────┐   │               │
│  │  │  WhatsAppBridgeController       │   │               │
│  │  │  (API Endpoints)                │   │               │
│  │  └─────────────────────────────────┘   │               │
│  │                                          │               │
│  │  ┌─────────────────────────────────┐   │               │
│  │  │  WhatsAppService                │   │               │
│  │  │  (Mode: hybrid=true)            │   │               │
│  │  └─────────────────────────────────┘   │               │
│  └─────────────────────────────────────────┘               │
│                         │                                   │
│                         │ HTTP API                          │
│                         ▼                                   │
└─────────────────────────────────────────────────────────────┘
                          │
                          │ Internet (HTTPS)
                          ▼
┌─────────────────────────────────────────────────────────────┐
│  PC RUMAH / LAPTOP / ANDROID (Termux)                       │
│  ┌─────────────────────────────────────────┐               │
│  │  WhatsApp Bridge Client (Node.js)       │               │
│  │  ┌─────────────────────────────────┐   │               │
│  │  │  whatsapp-bridge-client.js      │   │               │
│  │  │  (Polling VPS setiap 30s)       │   │               │
│  │  └─────────────────────────────────┘   │               │
│  │                                          │               │
│  │  ┌─────────────────────────────────┐   │               │
│  │  │  Baileys Library                │   │               │
│  │  │  (WhatsApp Web Connection)      │   │               │
│  │  └─────────────────────────────────┘   │               │
│  │                                          │               │
│  │  ┌─────────────────────────────────┐   │               │
│  │  │  Auth Session (auth_info/)      │   │               │
│  │  │  (QR Code Login)                │   │               │
│  │  └─────────────────────────────────┘   │               │
│  └─────────────────────────────────────────┘               │
│                         │                                   │
│                         │ WhatsApp Protocol                 │
│                         ▼                                   │
└─────────────────────────────────────────────────────────────┘
                          │
                          ▼
┌─────────────────────────────────────────────────────────────┐
│  WHATSAPP SERVER (Meta/Facebook)                           │
│                                                             │
│  ✅ IP Residential (PC Rumah) - Terlihat Natural           │
│  ✅ Device Windows/Chrome - Tidak Mencurigakan             │
│  ✅ Rate Limited (5 pesan/menit) - Aman dari Ban           │
└─────────────────────────────────────────────────────────────┘
```

---

## 🚀 Quick Start

### **Step 1: Setup VPS Hostinger**

#### 1.1 Jalankan Migration

```bash
cd /path/to/laravel-project
php artisan migrate
```

Ini akan membuat tabel `whatsapp_messages` di database.

#### 1.2 Update Environment Variables

Edit `.env` file di VPS:

```env
# Mode Hybrid (true = PC rumah sebagai bridge)
WHATSAPP_HYBRID_MODE=true

# Token untuk autentikasi bot (buat yang kuat!)
WHATSAPP_BOT_TOKEN=your-super-secret-token-min-32-characters

# URL VPS (untuk bot bridge)
WHATSAPP_VPS_URL=https://your-vps-hostinger.com
```

#### 1.3 Clear Config Cache

```bash
php artisan config:clear
php artisan cache:clear
```

#### 1.4 Setup HTTPS (WAJIB!)

Bot bridge harus mengakses VPS via HTTPS. Pastikan SSL sudah aktif:

```bash
# Jika pakai Let's Encrypt (Certbot)
sudo certbot --nginx -d your-domain.com
```

---

### **Step 2: Setup PC Rumah**

#### 2.1 Install Node.js

**Windows:**
- Download dari https://nodejs.org/ (LTS version)
- Install dengan default settings

**Linux (Ubuntu/Debian):**
```bash
curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
sudo apt-get install -y nodejs
```

**Mac:**
```bash
brew install node
```

**Android (Termux):**
```bash
pkg update
pkg install nodejs
```

#### 2.2 Download Bot Client

Copy file `whatsapp-bridge-client.js` dari project Laravel ke PC rumah:

```bash
# Buat folder
mkdir ~/whatsapp-bridge
cd ~/whatsapp-bridge

# Copy file (via scp, flashdisk, atau download)
# File: whatsapp-bridge-client.js

# Buat .env file
cat > .env << 'EOF'
VPS_URL=https://your-vps-hostinger.com
BOT_TOKEN=your-super-secret-token-min-32-characters
POLL_INTERVAL=30000
EOF
```

#### 2.3 Install Dependencies

```bash
cd ~/whatsapp-bridge

# Install Baileys dan dependencies lainnya
npm install @whiskeysockets/baileys axios qrcode-terminal dotenv

# Buat package.json jika belum ada
cat > package.json << 'EOF'
{
  "name": "whatsapp-bridge",
  "version": "1.0.0",
  "type": "module",
  "dependencies": {
    "@whiskeysockets/baileys": "^6.7.0",
    "axios": "^1.6.0",
    "qrcode-terminal": "^0.12.0",
    "dotenv": "^16.3.0"
  }
}
EOF
```

#### 2.4 Jalankan Bot

```bash
# Load environment variables
export VPS_URL=https://your-vps-hostinger.com
export BOT_TOKEN=your-super-secret-token-min-32-characters
export POLL_INTERVAL=30000

# Jalankan bot
node whatsapp-bridge-client.js
```

**Expected Output:**
```
[2025-02-02T12:00:00.000Z] 🚀 WhatsApp Bridge initializing...
[2025-02-02T12:00:00.000Z] 📡 VPS: https://your-vps-hostinger.com
[2025-02-02T12:00:00.000Z] ⏱️ Poll Interval: 30000ms
[2025-02-02T12:00:00.000Z] 📡 Creating WhatsApp socket...
[2025-02-02T12:00:00.000Z] 🔄 Connecting to WhatsApp...
[2025-02-02T12:00:00.000Z] 📲 Scan QR Code dengan WhatsApp di HP Anda...
▇▇▇▇▇▇▇▇▇▇▇▇▇▇▇▇▇▇▇▇▇▇▇
▇▇▇▇▇▇▇▇▇▇▇▇▇▇▇▇▇▇▇▇▇▇▇
▇▇▇▇▇▇▇▇▇▇▇▇▇▇▇▇▇▇▇▇▇▇▇
▇▇▇▇▇▇▇▇▇▇▇▇▇▇▇▇▇▇▇▇▇▇▇
[QR CODE HERE]
▇▇▇▇▇▇▇▇▇▇▇▇▇▇▇▇▇▇▇▇▇▇▇
```

#### 2.5 Scan QR Code

1. Buka WhatsApp di HP
2. Menu → Perangkat Tertaut → Tautkan Perangkat
3. Scan QR code yang muncul di terminal
4. Tunggu sampai muncul: "✅ WhatsApp CONNECTED!"

---

### **Step 3: Auto-Start dengan PM2 (Opsional tapi Recommended)**

Agar bot otomatis jalan saat PC restart:

```bash
# Install PM2
npm install -g pm2

# Jalankan bot dengan PM2
pm2 start whatsapp-bridge-client.js --name "whatsapp-bridge"

# Simpan config
pm2 save

# Setup auto-start
pm2 startup

# Cek status
pm2 status
pm2 logs whatsapp-bridge
```

---

## 📋 Checklist Setup

### VPS Hostinger:
- [ ] Migration database dijalankan
- [ ] Environment variables diisi
- [ ] HTTPS/SSL aktif
- [ ] Firewall allow HTTP/HTTPS
- [ ] API endpoints bisa diakses dari luar

### PC Rumah:
- [ ] Node.js terinstall
- [ ] File `whatsapp-bridge-client.js` dicopy
- [ ] Dependencies terinstall (`npm install`)
- [ ] Environment variables diset
- [ ] Bot bisa jalan (`node whatsapp-bridge-client.js`)
- [ ] QR code berhasil discan
- [ ] Status "✅ WhatsApp CONNECTED!" muncul
- [ ] PM2 diinstall dan disetup (optional)

### Router (jika pakai port forwarding):
- [ ] Port forwarding untuk VPS (biasanya tidak perlu)
- [ ] DDNS setup (jika IP dinamis)

---

## 🔧 Testing

### Test dari VPS:

```bash
# Cek bot status
curl https://your-vps-hostinger.com/api/whatsapp-bridge/status

# Cek statistik
curl https://your-vps-hostinger.com/api/whatsapp-bridge/stats

# Kirim test message (dari Laravel Tinker)
php artisan tinker
>>> $wa = app(App\Services\WhatsAppService::class);
>>> $wa->sendMessage('08123456789', 'Test message from VPS!', 'test');
=> true
```

### Check di PC Rumah:

```bash
# Lihat log
pm2 logs whatsapp-bridge

# Expected output:
[2025-02-02T12:05:00.000Z] 📡 Polling VPS for pending messages...
[2025-02-02T12:05:00.000Z] 📨 Found 1 pending messages
[2025-02-02T12:05:00.000Z] 📤 Sending to 628123456789: Test message from VPS!...
[2025-02-02T12:05:02.000Z] ✅ Message sent successfully (ID: ABCD1234)
```

---

## 🛡️ Keamanan

### Token Security:
- Gunakan token yang panjang dan random (min 32 karakter)
- Jangan hardcode token di kode, pakai environment variable
- Ganti token secara berkala
- Jangan share token ke siapapun

### HTTPS Only:
- WAJIB pakai HTTPS, jangan HTTP
- Bot akan mengirim data sensitif (nomor WA, pesan)
- Install SSL certificate (Let's Encrypt gratis)

### IP Whitelist (Opsional):
Jika IP PC rumah statis, whitelist di VPS:

```php
// di WhatsAppBridgeController.php
$allowedIps = ['123.45.67.89', '98.76.54.32'];
if (!in_array($request->ip(), $allowedIps)) {
    return response()->json(['error' => 'IP not allowed'], 403);
}
```

---

## 🔍 Troubleshooting

### Problem: Bot tidak bisa connect ke VPS

**Check:**
```bash
# Dari PC rumah
curl -H "X-Bot-Token: your-token" https://your-vps-hostinger.com/api/whatsapp-bridge/status

# Jika error:
# - Cek HTTPS certificate
# - Cek firewall
# - Cek DNS resolution
```

### Problem: QR code tidak muncul

**Solusi:**
```bash
# Hapus auth folder dan coba lagi
rm -rf ~/whatsapp-bridge/auth_info
node whatsapp-bridge-client.js
```

### Problem: "Invalid Token"

**Check:**
- Pastikan token di `.env` PC rumah sama dengan VPS
- Cek tidak ada spasi di awal/akhir token
- Restart bot setelah ganti token

### Problem: Bot connect tapi tidak kirim pesan

**Check:**
1. Cek log di PC rumah: `pm2 logs`
2. Cek status di VPS: `curl /api/whatsapp-bridge/stats`
3. Cek database: `SELECT * FROM whatsapp_messages WHERE status = 'pending';`

### Problem: WhatsApp number banned

**Solusi:**
1. Stop bot: `pm2 stop whatsapp-bridge`
2. Hapus auth: `rm -rf auth_info`
3. Tunggu 24-48 jam
4. Coba dengan nomor WA lain
5. Restart bot dan scan QR baru

---

## 📊 Monitoring

### Dashboard Admin

Akses di browser:
- Status Bot: `/api/whatsapp-bridge/status`
- Statistik: `/api/whatsapp-bridge/stats`

### Log Files

**VPS:**
```bash
tail -f storage/logs/laravel.log | grep "WhatsApp Bridge"
```

**PC Rumah:**
```bash
pm2 logs whatsapp-bridge
# atau
journalctl -u pm2-root -f
```

---

## 🔄 Switch Mode (Hybrid ↔ Local)

### Switch ke Mode Local (Bot di VPS):

```env
# .env di VPS
WHATSAPP_HYBRID_MODE=false
```

```bash
php artisan config:clear
# Bot akan pakai file queue (whatsapp_messages.json)
# Jangan lupa jalankan bot local: node app/Services/WhatsAppBot/whatsapp-bot.js
```

### Switch ke Mode Hybrid (Bot di PC Rumah):

```env
# .env di VPS
WHATSAPP_HYBRID_MODE=true
```

```bash
php artisan config:clear
# Pastikan bot bridge jalan di PC rumah
```

---

## 📝 File Structure

```
project/
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       └── API/
│   │           └── WhatsAppBridgeController.php  # NEW
│   ├── Models/
│   │   └── WhatsAppMessage.php                   # NEW
│   └── Services/
│       └── WhatsAppService.php                   # UPDATED
├── config/
│   └── services.php                              # UPDATED
├── database/
│   └── migrations/
│       └── 2025_02_02_120000_create_whatsapp_messages_table.php  # NEW
├── routes/
│   └── api.php                                   # UPDATED
├── whatsapp-bridge-client.js                     # NEW (copy ke PC rumah)
└── docs/
    └── WHATSAPP_BRIDGE_SETUP.md                  # THIS FILE
```

---

## 🎯 Keuntungan Arsitektur Hybrid

1. **✅ IP Residential** - PC rumah punya IP dinamis yang terlihat natural
2. **✅ Anti-Ban** - WhatsApp tidak deteksi sebagai bot/server
3. **✅ VPS Tetap Efisien** - Handle logic & queue, tidak perlu bot berat
4. **✅ Power Saving** - PC rumah bisa dimatikan kalau tidak ada pesan
5. **✅ Backup Ready** - Bisa ganti PC rumah lain kalau ada masalah
6. **✅ Cost Effective** - Tidak perlu bayar dedicated IP di VPS

---

## 📞 Support

Jika ada masalah:
1. Cek log di VPS dan PC rumah
2. Pastikan semua checklist sudah done
3. Test API endpoint dari PC rumah
4. Cek status bot di dashboard admin

---

**Happy Messaging! 🚀**
