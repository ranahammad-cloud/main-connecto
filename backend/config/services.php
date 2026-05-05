<?php

return [
    'stripe' => ['key' => env('STRIPE_KEY'), 'secret' => env('STRIPE_SECRET'), 'webhook_secret' => env('STRIPE_WEBHOOK_SECRET')],
    'agora' => ['app_id' => env('AGORA_APP_ID')],
    'video' => ['provider' => env('VIDEO_PROVIDER', 'agora')],
    'google' => ['client_id' => env('GOOGLE_CLIENT_ID'), 'client_secret' => env('GOOGLE_CLIENT_SECRET'), 'redirect' => env('GOOGLE_REDIRECT_URI')],
    'linkedin' => ['client_id' => env('LINKEDIN_CLIENT_ID'), 'client_secret' => env('LINKEDIN_CLIENT_SECRET'), 'redirect' => env('LINKEDIN_REDIRECT_URI')],
];
