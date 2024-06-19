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
    | Resource Api Base URL
    |--------------------------------------------------------------------------
    |
    | This config variable sets the base API url path.
    |
    */

    'api_base_url' => env('APP_URL') . '/api',

    /*
    |--------------------------------------------------------------------------
    | Krait Internal API Configurations
    |--------------------------------------------------------------------------
    |
    | This config variable sets the internal Krait API settings.
    |
    */
    'krait_api' => [
        'use_csrf' => true,
        'auth_token' => null,
    ],

    /*
    |--------------------------------------------------------------------------
    | Resource API Configurations
    |--------------------------------------------------------------------------
    |
    | This config variable sets the Resource API settings.
    |
    */

    'resource_api' => [
        'use_csrf' => true,
        'auth_token' => null,
    ],
];
