<?php

namespace App\Services;

use App\Services\WhatsAppService;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class ALLNotificationService
{
    protected $whatsappService;

    public function __construct(WhatsAppService $whatsappService)
    {
        $this->whatsappService = $whatsappService;
    }

    /**
     * Send WhatsApp notification
     */
    public function sendWhatsAppNotification($phoneNumber, $message, $type = 'general')
    {
        try {
            // Format phone number if needed
            $formattedNumber = $this->formatPhoneNumber($phoneNumber);
            
            // Send via WhatsAppService
            $result = $this->whatsappService->sendMessage($formattedNumber, $message);
            
            Log::info("ALLNotificationService: WhatsApp {$type} sent to {$formattedNumber}");
            
            return $result;
        } catch (\Exception $e) {
            Log::error("ALLNotificationService: Failed to send WhatsApp {$type} to {$phoneNumber}: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Send contract reminder via WhatsApp
     */
    public function sendContractWhatsAppReminder($phoneNumber, $kosName, $roomNumber, $daysLeft, $endDate, $type = 'before')
    {
        $message = $this->buildContractWhatsAppMessage($kosName, $roomNumber, $daysLeft, $endDate, $type);
        
        return $this->sendWhatsAppNotification($phoneNumber, $message, 'contract_reminder_' . $type);
    }

    /**
     * Send contract completion via WhatsApp
     */
    public function sendContractCompletionWhatsApp($phoneNumber, $kosName, $roomNumber, $endDate)
    {
        $message = $this->buildContractCompletionWhatsAppMessage($kosName, $roomNumber, $endDate);
        
        return $this->sendWhatsAppNotification($phoneNumber, $message, 'contract_completion');
    }

    /**
     * Send email notification
     */
    public function sendEmailNotification($to, $subject, $view, $data = [])
    {
        try {
            Mail::send($view, $data, function ($message) use ($to, $subject) {
                $message->to($to)
                    ->subject($subject);
            });
            
            Log::info("ALLNotificationService: Email sent to {$to}");
            
            return true;
        } catch (\Exception $e) {
            Log::error("ALLNotificationService: Failed to send email to {$to}: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Send contract reminder email
     */
    public function sendContractEmailReminder($to, $userName, $kosName, $roomNumber, $daysLeft, $endDate, $type = 'before', $isPemilik = false)
    {
        $subject = $this->buildContractEmailSubject($daysLeft, $type, $isPemilik);
        
        $data = [
            'userName' => $userName,
            'kosName' => $kosName,
            'roomNumber' => $roomNumber,
            'daysLeft' => $daysLeft,
            'endDate' => $endDate,
            'type' => $type,
            'isPemilik' => $isPemilik,
            // avoid using key 'message' because Mail exposes $message in views
            'emailMessage' => $this->buildContractEmailMessage($kosName, $roomNumber, $daysLeft, $endDate, $type, $isPemilik),
            // ensure view always has this variable to avoid undefined notices
            'isCompletion' => false,
        ];
        
        return $this->sendEmailNotification($to, $subject, 'emails.contract_reminder', $data);
    }

    /**
     * Send contract completion email
     */
    public function sendContractCompletionEmail($to, $userName, $kosName, $roomNumber, $endDate, $isPemilik = false)
    {
        $subject = $isPemilik ? "[PEMILIK] ✅ Kontrak Kos Telah Selesai" : "✅ Kontrak Kos Telah Selesai";
        
        $data = [
            'userName' => $userName,
            'kosName' => $kosName,
            'roomNumber' => $roomNumber,
            'endDate' => $endDate,
            'isPemilik' => $isPemilik,
            'isCompletion' => true,
            // avoid using key 'message' because Mail exposes $message in views
            'emailMessage' => $this->buildContractCompletionEmailMessage($kosName, $roomNumber, $endDate, $isPemilik)
        ];
        
        return $this->sendEmailNotification($to, $subject, 'emails.contract_reminder', $data);
    }

    /**
     * Send dual notification (WhatsApp + Email) for contract reminder
     */
    public function sendDualContractReminder($user, $kosName, $roomNumber, $daysLeft, $endDate, $type = 'before', $isPemilik = false)
    {
        $results = [];
        
        // Send WhatsApp
        if (!empty($user->no_hp)) {
            try {
                if ($type === 'completion') {
                    $results['whatsapp'] = $this->sendContractCompletionWhatsApp(
                        $user->no_hp, 
                        $kosName, 
                        $roomNumber, 
                        $endDate
                    );
                } else {
                    $results['whatsapp'] = $this->sendContractWhatsAppReminder(
                        $user->no_hp, 
                        $kosName, 
                        $roomNumber, 
                        $daysLeft, 
                        $endDate, 
                        $type
                    );
                }
            } catch (\Exception $e) {
                $results['whatsapp_error'] = $e->getMessage();
                Log::error("ALLNotificationService: Failed WhatsApp for {$user->nama}: " . $e->getMessage());
            }
        }
        
        // Send Email
        if (!empty($user->email)) {
            try {
                if ($type === 'completion') {
                    $results['email'] = $this->sendContractCompletionEmail(
                        $user->email,
                        $user->nama,
                        $kosName,
                        $roomNumber,
                        $endDate,
                        $isPemilik
                    );
                } else {
                    $results['email'] = $this->sendContractEmailReminder(
                        $user->email,
                        $user->nama,
                        $kosName,
                        $roomNumber,
                        $daysLeft,
                        $endDate,
                        $type,
                        $isPemilik
                    );
                }
            } catch (\Exception $e) {
                $results['email_error'] = $e->getMessage();
                Log::error("ALLNotificationService: Failed Email for {$user->email}: " . $e->getMessage());
            }
        }
        
        return $results;
    }

    /**
     * Build WhatsApp message for contract reminder
     */
    private function buildContractWhatsAppMessage($kosName, $roomNumber, $daysLeft, $endDate, $type)
    {
        $roomInfo = $roomNumber ? "Kamar: {$roomNumber}\n" : "";
        
        switch ($type) {
            case 'before':
                return "⏰ *PENGINGAT KONTRAK KOS*\n\n" .
                       "Kos: *{$kosName}*\n" .
                       $roomInfo .
                       "\n⏳ Akan berakhir dalam: *{$daysLeft} hari*\n" .
                       "📅 Tanggal berakhir: {$endDate}\n\n" .
                       "Silakan hubungi pemilik untuk perpanjangan atau persiapkan pengosongan kamar.";
            
            case 'today':
                return "⚠️ *KONTRAK BERAKHIR HARI INI!*\n\n" .
                       "Kos: *{$kosName}*\n" .
                       $roomInfo .
                       "\n📅 *BERAKHIR HARI INI: {$endDate}*\n\n" .
                       "Harap segera:\n" .
                       "1. Lakukan perpanjangan kontrak, ATAU\n" .
                       "2. Kosongkan kamar sesuai peraturan";
            
            case 'overdue':
                return "🚨 *KONTRAK TELAH MELEWATI TENGGAT WAKTU!*\n\n" .
                       "Kos: *{$kosName}*\n" .
                       $roomInfo .
                       "\n⏰ Telah berakhir: *{$daysLeft} hari yang lalu*\n" .
                       "📅 Berakhir pada: {$endDate}\n\n" .
                       "Segera hubungi pemilik atau kosongkan kamar.";
            
            default:
                return "📋 *INFORMASI KONTRAK*\n\n" .
                       "Kos: *{$kosName}*\n" .
                       $roomInfo .
                       "\nStatus: {$type}\n" .
                       "Tanggal: {$endDate}";
        }
    }

    /**
     * Build WhatsApp message for contract completion
     */
    private function buildContractCompletionWhatsAppMessage($kosName, $roomNumber, $endDate)
    {
        $roomInfo = $roomNumber ? "Kamar: {$roomNumber}\n" : "";
        
        return "✅ *KONTRAK TELAH SELESAI*\n\n" .
               "Kos: *{$kosName}*\n" .
               $roomInfo .
               "\n📅 Telah berakhir: {$endDate}\n\n" .
               "Terima kasih telah menggunakan layanan AyoKos!";
    }

    /**
     * Build email subject for contract reminder
     */
    private function buildContractEmailSubject($daysLeft, $type, $isPemilik)
    {
        $prefix = $isPemilik ? "[PEMILIK] " : "";
        
        switch ($type) {
            case 'before':
                return $prefix . "⏰ Pengingat Kontrak Kos - {$daysLeft} Hari Lagi";
            case 'today':
                return $prefix . "⚠️ Kontrak Kos Berakhir Hari Ini";
            case 'overdue':
                return $prefix . "🚨 Kontrak Kos Telah Melewati Tenggat Waktu";
            default:
                return $prefix . "📋 Informasi Kontrak Kos";
        }
    }

    /**
     * Build email message for contract reminder
     */
    private function buildContractEmailMessage($kosName, $roomNumber, $daysLeft, $endDate, $type, $isPemilik)
    {
        $userType = $isPemilik ? "penghuni" : "Anda";
        $roomInfo = $roomNumber ? " (Kamar {$roomNumber})" : "";
        
        switch ($type) {
            case 'before':
                return "Kontrak kos {$userType} di <strong>{$kosName}</strong>{$roomInfo} akan berakhir dalam <strong>{$daysLeft} hari</strong> (berakhir pada {$endDate}).<br><br>" .
                       "Silakan persiapkan perpanjangan kontrak atau pengosongan kamar sesuai peraturan kos.";
            
            case 'today':
                return "<strong>PERHATIAN!</strong> Kontrak kos {$userType} di <strong>{$kosName}</strong>{$roomInfo} <strong>berakhir hari ini</strong> ({$endDate}).<br><br>" .
                       "Segera lakukan perpanjangan kontrak atau kosongkan kamar sesuai peraturan kos.";
            
            case 'overdue':
                return "<strong>PENTING!</strong> Kontrak kos {$userType} di <strong>{$kosName}</strong>{$roomInfo} telah <strong>melewati tenggat waktu {$daysLeft} hari yang lalu</strong> (berakhir pada {$endDate}).<br><br>" .
                       "Segera hubungi " . ($isPemilik ? "penghuni" : "pemilik kos") . " atau kosongkan kamar.";
            
            default:
                return "Informasi kontrak kos di <strong>{$kosName}</strong>{$roomInfo}.";
        }
    }

    /**
     * Build email message for contract completion
     */
    private function buildContractCompletionEmailMessage($kosName, $roomNumber, $endDate, $isPemilik)
    {
        $userType = $isPemilik ? "penghuni" : "Anda";
        $roomInfo = $roomNumber ? " (Kamar {$roomNumber})" : "";
        
        return "Kontrak kos {$userType} di <strong>{$kosName}</strong>{$roomInfo} telah <strong>resmi selesai</strong> (berakhir pada {$endDate}).<br><br>" .
               "Terima kasih telah menggunakan layanan AyoKos.";
    }

    /**
     * Format phone number for WhatsApp
     */
    private function formatPhoneNumber($phone)
    {
        // Remove any non-digit characters
        $phone = preg_replace('/[^0-9]/', '', $phone);
        
        // If starts with 0, convert to 62
        if (substr($phone, 0, 1) === '0') {
            $phone = '62' . substr($phone, 1);
        }
        
        // If starts with 8, add 62
        if (substr($phone, 0, 1) === '8') {
            $phone = '62' . $phone;
        }
        
        return $phone . '@c.us'; // Baileys format
    }
}