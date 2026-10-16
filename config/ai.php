<?php

return [

    'provider' => env('AI_PROVIDER', 'gemini'),

    'base_url' => rtrim(env('AI_BASE_URL', 'https://generativelanguage.googleapis.com/v1beta'), '/'),

    'api_key' => env('AI_API_KEY'),

    'model' => env('AI_MODEL', 'gemini-flash-latest'),

    'timeout' => (int) env('AI_TIMEOUT', 30),

];
