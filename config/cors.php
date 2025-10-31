<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Laravel CORS Configuration
    |--------------------------------------------------------------------------
    |
    | Konfigurasi ini mengatur izin Cross-Origin supaya frontend bisa akses
    | API Laravel. Pastikan "supports_credentials" true untuk Sanctum.
    |
    */

    'paths' => [
        'api/*',
        'sanctum/csrf-cookie',
        'login',
        'logout',
        'register',
        'user',
    ],

    'allowed_methods' => ['*'],

    'allowed_origins' => [
        // 🧠 Tambahkan semua domain frontend yang butuh akses ke API
        'http://localhost:5173',
        'http://127.0.0.1:5173',
        'https://api.practic-doctor.biz.id',
        'https://orthognathous-robert-nonprejudicially.ngrok-free.dev',
    ],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    // 🟢 penting! biar cookie dan session Sanctum bisa ikut
    'supports_credentials' => true,

];
