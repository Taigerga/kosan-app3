<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\WhatsAppMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * WhatsApp Bridge Controller
 * 
 * Controller ini menangani komunikasi antara VPS (Laravel) dan PC Rumah (Baileys Bot)
 * Endpoint untuk polling pesan dan update status
 */
class WhatsAppBridgeController extends Controller
{
    /**
     * Token autentikasi untuk bot (simpan di .env: WHATSAPP_BOT_TOKEN)
     */
    private function validateToken(Request $request)
    {
        $token = $request->header('X-Bot-Token');
        $expectedToken = config('services.whatsapp.bot_token');
        
        if (!$token || $token !== $expectedToken) {
            Log::warning('WhatsApp Bridge: Invalid token attempt', [
                'ip' => $request->ip(),
                'token' => substr($token, 0, 10) . '...'
            ]);
            return false;
        }
        
        return true;
    }

    /**
     * Get pending messages untuk bot
     * GET /api/whatsapp-bridge/pending
     * 
     * Bot akan poll endpoint ini setiap 10-30 detik
     */
    public function getPendingMessages(Request $request)
    {
        if (!$this->validateToken($request)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        try {
            // Ambil pesan dengan status pending, limit 5 per request (rate limiting)
            $messages = WhatsAppMessage::where('status', 'pending')
                ->where('attempts', '<', 3) // Max 3 retry attempts
                ->orderBy('created_at', 'asc')
                ->limit(5)
                ->get();

            // Update status menjadi 'processing' biar tidak diambil bot lain
            foreach ($messages as $message) {
                $message->update([
                    'status' => 'processing',
                    'processing_at' => now(),
                    'attempts' => $message->attempts + 1
                ]);
            }

            Log::info('WhatsApp Bridge: Pending messages fetched', [
                'count' => $messages->count(),
                'bot_ip' => $request->ip()
            ]);

            return response()->json([
                'success' => true,
                'data' => $messages,
                'count' => $messages->count()
            ]);

        } catch (\Exception $e) {
            Log::error('WhatsApp Bridge: Error fetching pending messages', [
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'error' => 'Internal server error'
            ], 500);
        }
    }

    /**
     * Mark message as sent
     * POST /api/whatsapp-bridge/mark-sent
     * 
     * Bot akan panggil ini setelah berhasil kirim pesan
     */
    public function markAsSent(Request $request)
    {
        if (!$this->validateToken($request)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $request->validate([
            'id' => 'required|integer',
            'whatsapp_message_id' => 'nullable|string' // ID dari WhatsApp (optional)
        ]);

        try {
            $message = WhatsAppMessage::find($request->id);
            
            if (!$message) {
                return response()->json([
                    'success' => false,
                    'error' => 'Message not found'
                ], 404);
            }

            $message->update([
                'status' => 'sent',
                'sent_at' => now(),
                'whatsapp_message_id' => $request->whatsapp_message_id,
                'error' => null
            ]);

            Log::info('WhatsApp Bridge: Message marked as sent', [
                'message_id' => $message->id,
                'phone' => $message->phone
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Message marked as sent'
            ]);

        } catch (\Exception $e) {
            Log::error('WhatsApp Bridge: Error marking message as sent', [
                'error' => $e->getMessage(),
                'message_id' => $request->id
            ]);
            
            return response()->json([
                'success' => false,
                'error' => 'Internal server error'
            ], 500);
        }
    }

    /**
     * Mark message as failed
     * POST /api/whatsapp-bridge/mark-failed
     * 
     * Bot akan panggil ini jika gagal kirim pesan
     */
    public function markAsFailed(Request $request)
    {
        if (!$this->validateToken($request)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $request->validate([
            'id' => 'required|integer',
            'error' => 'required|string'
        ]);

        try {
            $message = WhatsAppMessage::find($request->id);
            
            if (!$message) {
                return response()->json([
                    'success' => false,
                    'error' => 'Message not found'
                ], 404);
            }

            // Jika sudah 3x gagal, mark sebagai failed permanently
            $newStatus = $message->attempts >= 3 ? 'failed' : 'pending';
            
            $message->update([
                'status' => $newStatus,
                'error' => $request->error,
                'failed_at' => $newStatus === 'failed' ? now() : null
            ]);

            Log::warning('WhatsApp Bridge: Message marked as failed', [
                'message_id' => $message->id,
                'phone' => $message->phone,
                'error' => $request->error,
                'attempts' => $message->attempts,
                'final_status' => $newStatus
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Message marked as failed',
                'will_retry' => $newStatus === 'pending'
            ]);

        } catch (\Exception $e) {
            Log::error('WhatsApp Bridge: Error marking message as failed', [
                'error' => $e->getMessage(),
                'message_id' => $request->id
            ]);
            
            return response()->json([
                'success' => false,
                'error' => 'Internal server error'
            ], 500);
        }
    }

    /**
     * Bot status update
     * POST /api/whatsapp-bridge/bot-status
     * 
     * Bot akan kirim status periodik (online/offline/banned)
     */
    public function updateBotStatus(Request $request)
    {
        if (!$this->validateToken($request)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $request->validate([
            'status' => 'required|in:online,offline,connecting,banned,qr_ready',
            'message' => 'nullable|string'
        ]);

        try {
            // Simpan status bot (bisa pakai cache atau database)
            cache()->put('whatsapp_bot_status', [
                'status' => $request->status,
                'message' => $request->message,
                'updated_at' => now(),
                'ip' => $request->ip()
            ], now()->addMinutes(5));

            if ($request->status === 'banned') {
                Log::emergency('WhatsApp Bridge: BOT BANNED!', [
                    'message' => $request->message,
                    'ip' => $request->ip()
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Status updated'
            ]);

        } catch (\Exception $e) {
            Log::error('WhatsApp Bridge: Error updating bot status', [
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'error' => 'Internal server error'
            ], 500);
        }
    }

    /**
     * Get bot status (untuk admin panel)
     * GET /api/whatsapp-bridge/status
     */
    public function getBotStatus()
    {
        $status = cache()->get('whatsapp_bot_status', [
            'status' => 'unknown',
            'updated_at' => null
        ]);

        // Cek apakah bot masih aktif (last update < 2 menit)
        $isOnline = $status['updated_at'] && 
                    now()->diffInMinutes($status['updated_at']) < 2;

        return response()->json([
            'success' => true,
            'data' => [
                'bot_status' => $isOnline ? $status['status'] : 'offline',
                'last_seen' => $status['updated_at'],
                'details' => $status
            ]
        ]);
    }

    /**
     * Dashboard statistik (untuk admin)
     * GET /api/whatsapp-bridge/stats
     */
    public function getStats()
    {
        try {
            $stats = [
                'pending' => WhatsAppMessage::where('status', 'pending')->count(),
                'processing' => WhatsAppMessage::where('status', 'processing')->count(),
                'sent_today' => WhatsAppMessage::where('status', 'sent')
                    ->whereDate('sent_at', today())->count(),
                'failed_today' => WhatsAppMessage::where('status', 'failed')
                    ->whereDate('failed_at', today())->count(),
                'total_queue' => WhatsAppMessage::whereIn('status', ['pending', 'processing'])->count()
            ];

            return response()->json([
                'success' => true,
                'data' => $stats
            ]);

        } catch (\Exception $e) {
            Log::error('WhatsApp Bridge: Error getting stats', [
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'error' => 'Internal server error'
            ], 500);
        }
    }
}
