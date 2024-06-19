<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Debug Mode
    |--------------------------------------------------------------------------
    |
    | This config variable flags if the Krait should run under debug mode ot not.
    |
    */

    'debug' => false,

    /*
    |--------------------------------------------------------------------------
    | Krait API Prefix
    |--------------------------------------------------------------------------
    |
    | This config variable sets the internal Krait API prefix path.
    |
    */

    'path' => 'krait',


    /*
    |--------------------------------------------------------------------------
    | Krait API Endpoints-related Middlewares
    |--------------------------------------------------------------------------
    |
    | This config variables sets all internal Krait middlewares that should be used
    | for accessing the Krait API.
    |
    */

    'middleware' => null,

    /*
    |--------------------------------------------------------------------------
    | Api Base URL
    |--------------------------------------------------------------------------
    |
    | This config variable sets the base API url path.
    |
    */

    'api_base_url' => env('APP_URL') . '/api',


    /*
    |--------------------------------------------------------------------------
    | Api CSRF Token
    |--------------------------------------------------------------------------
    |
    | This config variable flags if the API requests should use the laravel
    | CSRF token.
    |
    */

    'api_use_csrf' => true,


    /*
    |--------------------------------------------------------------------------
    | Api Auth Token
    |--------------------------------------------------------------------------
    |
    | This config is for Bearer authorization token (if needed).
    |
    */

    'api_auth_token' => null,
];
