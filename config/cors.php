<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your settings for cross-origin resource sharing
    | or "CORS". This determines what cross-origin operations may execute
    | in web browsers. You are free to adjust these settings as needed.
    |
    | To learn more: https://developer.mozilla.org/en-US/docs/Web/HTTP/CORS
    |
    */

    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    'allowed_methods' => ['*'],

    'allowed_origins' => [
        'https://blognest-three.vercel.app',
        'https://blognest-qusu.vercel.app',
        'https://blognest-94tp.vercel.app',
        'https://blognest-94tp-expxzlca2-bola-nabils-projects.vercel.app',
        'https://blognest-qusu-avai6t37w-bola-nabils-projects.vercel.app',
        'http://localhost:5173',
    ],

    'allowed_origins_patterns' => [
        '#^https://blognest-[a-z0-9\-]+\.vercel\.app$#',
    ],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => true,

];
