<?php

declare(strict_types=1);

return [
    'center' => [
        'ip' => env('MESSAGE_CENTER_IP', '127.0.0.1'),
        'domain' => env('MESSAGE_CENTER_DOMAIN', 'http://localhost'),
        'proxy' => env('MESSAGE_CENTER_PROXY'),
        'proxy_port' => env('MESSAGE_CENTER_PROXY_PORT', 80),
    ]
];
