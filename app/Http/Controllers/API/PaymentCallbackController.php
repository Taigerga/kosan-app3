<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentCallbackController extends Controller
{
    protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    /**
     * Handle payment gateway callback
     */
    public function handleCallback(Request $request)
    {
        Log::info('Payment callback received:', $request->all());

        $result = $this->paymentService->handleCallback($request->all());

        if ($result['success']) {
            return response()->json(['status' => 'success']);
        }

        return response()->json(['status' => 'error', 'message' => $result['message']], 400);
    }

    /**
     * Simulate payment success (for demo)
     */
    public function simulatePayment($externalId)
    {
        $pembayaran = \App\Models\Pembayaran::where('external_id', $externalId)->first();

        if (!$pembayaran) {
            abort(404);
        }

        // For demo purposes - simulate successful payment
        $pembayaran->markAsSuccess();

        return view('public.payment_success', compact('pembayaran'));
    }
}