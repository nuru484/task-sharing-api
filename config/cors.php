<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Laravel CORS Options
    |--------------------------------------------------------------------------
    |
    */

    'paths' => ['api/*', 'sanctum/csrf-cookie'], // Paths that should allow CORS

    'allowed_methods' => ['GET', 'POST', 'PUT', 'DELETE', 'PATCH', 'OPTIONS'], // HTTP methods allowed

    'allowed_origins' => ['http://localhost:5173'], // Allowed origin(s)

    'allowed_origins_patterns' => [], // Patterns to match origins (useful for dynamic origins)

    'allowed_headers' => ['Content-Type', 'X-Requested-With', 'Authorization'], // Headers allowed

    'exposed_headers' => [], // Headers that the browser is allowed to access

    'max_age' => 0, // Cache duration for preflight requests in seconds

    'supports_credentials' => true, // Allow credentials like cookies, authorization headers, etc.

];
