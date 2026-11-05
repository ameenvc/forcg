<?php

return [
    // Disabled until explicitly enabled by the application administrator.
    'organization_key' => env('RISK_AI_ORGANIZATION_KEY', 'installation'),
    'enabled' => env('RISK_AI_ENABLED', false),
    'mode' => env('RISK_AI_MODE', 'demo'),
    'key' => env('RISK_AI_API_KEY'),
    'model' => env('RISK_AI_MODEL', 'gpt-4.1-mini'),
];
