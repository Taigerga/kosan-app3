<?php
// app/Http/Controllers/API/WhatsAppController.php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;

class WhatsAppController extends Controller
{
    protected $whatsappService;

    public function __construct(WhatsAppService $whatsappService)
    {
        $this->whatsappService = $whatsappService;
    }

    public function getStatus()
    {
        return response()->json([
            'status' => $this->whatsappService->getConnectionStatus()
        ]);
    }

    public function testConnection(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
            'message' => 'required|string'
        ]);

        try {
            $result = $this->whatsappService->sendMessage(
                $request->phone,
                $request->message
            );

            return response()->json([
                'success' => $result,
                'message' => $result ? 'Test message sent successfully' : 'Failed to send test message'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}