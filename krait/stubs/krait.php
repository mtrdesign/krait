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

    'krait_path' => 'krait',

    /*
    |--------------------------------------------------------------------------
    | Krait API Prefix
    |--------------------------------------------------------------------------
    |
    | This config variable sets the internal Krait API prefix path.
    |
    */

    'tables_path' => 'tables',

    /*
    |--------------------------------------------------------------------------
    | Krait API Endpoints-related Middlewares
    |--------------------------------------------------------------------------
    |
    | This config variables sets all internal Krait middlewares that should be used
    | for accessing the Krait API.
    |
    */

    'global_middlewares' => ['web', 'auth'],

    /*
    |--------------------------------------------------------------------------
    | CSRF Token
    |--------------------------------------------------------------------------
    |
    | This config variable flags if the CSRF token should be passed to the endpoints.
    |
    */

    'use_csrf' => true,

    /*
    |--------------------------------------------------------------------------
    | Formatting
    |--------------------------------------------------------------------------
    |
    | These config variables set the default front-end formatting of specific
    | data types.
    |
    */
    'date_format' => 'm/d/Y, g:i a',
];
