<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'payment' => [
        'base_url' => env('PAYMENT_BASE_URL', 'https://api.xendit.co'),
        'api_key' => env('PAYMENT_API_KEY'),
        'secret_key' => env('PAYMENT_SECRET_KEY'),
    ],

    'whatsapp' => [
        // Mode hybrid: true = PC rumah sebagai bridge, false = bot jalan di VPS
        'hybrid_mode' => env('WHATSAPP_HYBRID_MODE', false),
        
        // Token untuk autentikasi bot (penting untuk keamanan!)
        'bot_token' => env('WHATSAPP_BOT_TOKEN', 'default-secret-token-change-in-production'),
        
        // URL VPS (untuk bot bridge di PC rumah)
        'vps_url' => env('WHATSAPP_VPS_URL', 'https://your-vps-hostinger.com'),
    ],

];
