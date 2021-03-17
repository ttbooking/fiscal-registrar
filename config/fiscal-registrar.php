<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Fiscal Registrar Domain
    |--------------------------------------------------------------------------
    */

    'domain' => null,

    /*
    |--------------------------------------------------------------------------
    | Fiscal Registrar Path
    |--------------------------------------------------------------------------
    */

    'path' => 'fiscal-registrar',

    /*
    |--------------------------------------------------------------------------
    | Fiscal Registrar Route Middleware
    |--------------------------------------------------------------------------
    */

    'middleware' => ['web'],

    /*
    |--------------------------------------------------------------------------
    | Default Fiscal Registrar Connection Name
    |--------------------------------------------------------------------------
    */

    'default' => env('FISCAL_REGISTRAR_CONNECTION', 'atol'),

    'connections' => [

        'atol' => [
            'url' => 'https://testonline.atol.ru/possystem',
            'login' => 'v4-online-atol-ru',
            'password' => 'iGFFuihss',
            'inn' => '5544332219',
            'email' => 'info@atol.ru',
            'payment_address' => 'https://v4.online.atol.ru',
            'group_code' => 'v4-online-atol-ru_4179',
            'callback' => false,
        ],

    ],

];
