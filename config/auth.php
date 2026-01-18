<?php

return [
    'defaults' => [
        'guard' => 'web',
        'passwords' => 'penghuni',
    ],

    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'penghuni',
        ],

        'penghuni' => [
            'driver' => 'session',
            'provider' => 'penghuni',
            'remember' => true, 
        ],

        'pemilik' => [
            'driver' => 'session',
            'provider' => 'pemilik',
            'remember' => true, 
        ],
    ],

    'providers' => [
        'penghuni' => [
            'driver' => 'eloquent',
            'model' => App\Models\Penghuni::class,
        ],

        'pemilik' => [
            'driver' => 'eloquent',
            'model' => App\Models\Pemilik::class,
        ],
    ],

    'passwords' => [
        'penghuni' => [
            'provider' => 'penghuni',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],

        'pemilik' => [
            'provider' => 'pemilik',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],
    ],
];