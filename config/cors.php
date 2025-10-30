<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Laravel CORS Configuration
    |--------------------------------------------------------------------------
    |
    | Konfigurasi ini mengatur CORS (Cross-Origin Resource Sharing) agar
    | frontend (misalnya Vite di localhost:5173) bisa komunikasi dengan backend.
    | Jangan lupa set supports_credentials = true kalau pakai Sanctum.
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
        'http://localhost:5173',
        'http://127.0.0.1:5173',
        // Tambahkan juga jika nanti pakai ngrok atau domain hosting
        // 'https://orthognathous-robert-nonprejudicially.ngrok-free.dev',
        // 'https://example.practic-doctor.biz.id',
    ],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    // 🟢 penting! biar cookie/session bisa dikirim antar domain
    'supports_credentials' => true,

];
