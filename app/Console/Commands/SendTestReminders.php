<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\KontrakSewa;
use App\Services\NotificationService;
use App\Services\NotificationEmailService;
use Carbon\Carbon;

class SendTestReminders extends Command
{
    protected $signature = 'send:reminders {id : ID kontrak} {--wa : Kirim via WhatsApp} {--email : Kirim via Email} {--force : Force send tanpa cek tanggal}';

    protected $description = 'Simulasi pengiriman pengingat (WhatsApp dan/atau Email) untuk satu kontrak';

    public function handle()
    {
        $id = $this->argument('id');
        $sendWa = $this->option('wa');
        $sendEmail = $this->option('email');
        $force = $this->option('force');

        $kontrak = KontrakSewa::with(['penghuni', 'kos', 'kos.pemilik', 'kamar'])->find($id);

        if (!$kontrak) {
            $this->error("Kontrak dengan ID {$id} tidak ditemukan.");
            return 1;
        }

        // Debug info
        $this->info("=== DEBUG INFO ===");
        $this->info("Kontrak ID: {$kontrak->id_kontrak}");
        $this->info("Status: {$kontrak->status_kontrak}");
        $this->info("Tanggal Mulai: " . ($kontrak->tanggal_mulai ? $kontrak->tanggal_mulai->format('Y-m-d') : 'NULL'));
        $this->info("Tanggal Selesai: " . ($kontrak->tanggal_selesai ? $kontrak->tanggal_selesai->format('Y-m-d') : 'NULL'));
        $this->info("Durasi: {$kontrak->durasi_sewa} bulan");
        
        if ($kontrak->tanggal_selesai) {
            $today = Carbon::now();
            $hariSisa = $today->diffInDays($kontrak->tanggal_selesai, false);
            $this->info("Sisa hari: {$hariSisa} (negatif = sudah lewat)");
        } else {
            $this->warn("WARNING: Kontrak tidak memiliki tanggal selesai!");
            $hariSisa = null;
        }

        if ($sendWa) {
            $this->info("\n=== WHATSAPP NOTIFICATION ===");
            $svc = app(NotificationService::class);
            
            // Cek method yang tersedia di NotificationService
            $methods = get_class_methods($svc);
            $this->info("Method yang tersedia di NotificationService:");
            foreach ($methods as $method) {
                if (strpos($method, 'send') !== false || strpos($method, 'notif') !== false) {
                    $this->info("  - {$method}");
                }
            }
            
            if (!$kontrak->penghuni) {
                $this->error("ERROR: Kontrak tidak memiliki data penghuni!");
                return 1;
            }
            
            if (!$kontrak->penghuni->no_hp) {
                $this->error("ERROR: Penghuni tidak memiliki nomor WhatsApp!");
                return 1;
            }
            
            $this->info("Nomor WhatsApp: {$kontrak->penghuni->no_hp}");
            $this->info("Nama Penghuni: {$kontrak->penghuni->nama}");
            
            try {
                // Coba method yang mungkin ada
                if (method_exists($svc, 'triggerAllReminders')) {
                    $this->info("Menggunakan triggerAllReminders()...");
                    $svc->triggerAllReminders($kontrak->id);
                    $this->info("triggerAllReminders dipanggil.");
                } 
                elseif (method_exists($svc, 'sendKontrakReminder')) {
                    $this->info("Menggunakan sendKontrakReminder()...");
                    $svc->sendKontrakReminder($kontrak);
                    $this->info("sendKontrakReminder dipanggil.");
                }
                elseif (method_exists($svc, 'sendWhatsAppNotification')) {
                    $this->info("Menggunakan sendWhatsAppNotification()...");
                    
                    // Tentukan tipe berdasarkan status
                    $tipe = 'kontrak_baru'; // default
                    if ($kontrak->status_kontrak === 'aktif') {
                        $tipe = 'kontrak_aktif';
                    } elseif ($kontrak->status_kontrak === 'pending') {
                        $tipe = 'kontrak_pending';
                    }
                    
                    $svc->sendWhatsAppNotification($kontrak, $tipe);
                    $this->info("sendWhatsAppNotification dipanggil dengan tipe: {$tipe}");
                }
                else {
                    $this->error("Tidak ada method WhatsApp yang ditemukan di NotificationService!");
                    $this->info("Coba gunakan WhatsAppService langsung...");
                    
                    // Coba akses WhatsAppService langsung
                    try {
                        $whatsappService = app('App\Services\WhatsAppService');
                        if (method_exists($whatsappService, 'sendMessage')) {
                            $pemilikNama = $kontrak->kos->pemilik->nama ?? 'N/A';
                            $message = "Test reminder untuk kontrak #{$kontrak->id_kontrak}\n";
                            $message .= "Status: {$kontrak->status_kontrak}\n";
                            $message .= "Pemilik: {$pemilikNama}\n";
                            $message .= "Kos: {$kontrak->kos->nama_kos}";
                            
                            $result = $whatsappService->sendMessage($kontrak->penghuni->no_hp, $message);
                            $this->info("WhatsApp direct result: " . json_encode($result));
                        }
                    } catch (\Exception $e) {
                        $this->error("Error WhatsAppService: " . $e->getMessage());
                    }
                }
                
                $this->info("\nWhatsApp test selesai. Cek logs dan WhatsApp.");
            } catch (\Exception $e) {
                $this->error('Gagal mengirim WhatsApp: ' . $e->getMessage());
            }
        }

        if ($sendEmail) {
            $this->info("\n=== EMAIL NOTIFICATION ===");
            $emailSvc = app(NotificationEmailService::class);

            try {
                // Cek method email service
                $emailMethods = get_class_methods($emailSvc);
                $this->info("Method di NotificationEmailService:");
                foreach ($emailMethods as $method) {
                    $this->info("  - {$method}");
                }
                
                // Coba kirim email
                if (method_exists($emailSvc, 'sendTenggatWaktuToPenghuni')) {
                    $this->info("Mengirim email test...");
                    
                    // Tentukan tipe
                    $tipe = 'test';
                    if ($kontrak->status_kontrak === 'aktif') {
                        $tipe = 'kontrak_aktif';
                    } elseif ($kontrak->status_kontrak === 'pending') {
                        $tipe = 'kontrak_pending';
                    }
                    
                    if ($kontrak->penghuni && $kontrak->penghuni->email) {
                        $this->info("Email ke penghuni: {$kontrak->penghuni->email}");
                        $emailSvc->sendTenggatWaktuToPenghuni($kontrak, $tipe);
                    }
                    
                    if ($kontrak->kos && $kontrak->kos->pemilik && $kontrak->kos->pemilik->email) {
                        $this->info("Email ke pemilik: {$kontrak->kos->pemilik->email}");
                        $emailSvc->sendTenggatWaktuToPemilik($kontrak, $tipe);
                    }
                    
                    $this->info('Email dikirim.');
                } else {
                    $this->warn('Method email tidak ditemukan.');
                }
            } catch (\Exception $e) {
                $this->error('Gagal mengirim email: ' . $e->getMessage());
            }
        }

        if (!$sendWa && !$sendEmail) {
            $this->warn('Tidak ada opsi dipilih. Gunakan --wa, --email, atau keduanya.');
            return 1;
        }

        $this->info("\n=== SELESAI ===");
        return 0;
    }
}