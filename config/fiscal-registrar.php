<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Fiscal Registrar Domain
    |--------------------------------------------------------------------------
    */

    'domain' => env('FR_DOMAIN'),

    /*
    |--------------------------------------------------------------------------
    | Fiscal Registrar Path
    |--------------------------------------------------------------------------
    */

    'path' => env('FR_PATH', 'fiscal-registrar'),

    /*
    |--------------------------------------------------------------------------
    | Fiscal Registrar Route Middleware
    |--------------------------------------------------------------------------
    */

    'middleware' => ['web', 'auth'],

    /*
    |--------------------------------------------------------------------------
    | Fiscal Registrar Receipt Model
    |--------------------------------------------------------------------------
    */

    'model' => TTBooking\FiscalRegistrar\Models\Receipt::class,

    /*
    |--------------------------------------------------------------------------
    | Client Email for Testing
    |--------------------------------------------------------------------------
    */

    'test_email' => env('FR_TEST_EMAIL'),

    /*
    |--------------------------------------------------------------------------
    | Enable Client Notifications
    |--------------------------------------------------------------------------
    */

    'notify_client' => env('FR_NOTIFY_CLIENT', false),

    /*
    |--------------------------------------------------------------------------
    | Receipt State Synchronization Job Options
    |--------------------------------------------------------------------------
    */

    'sync_job' => [
        'schedule' => env('FR_SYNC_JOB_SCHEDULE', false),
        'older_than_minutes' => env('FR_SYNC_JOB_THRESHOLD', 5),
        'batch_size' => env('FR_SYNC_JOB_BATCH', 1),
    ],

    /*
    |--------------------------------------------------------------------------
    | Default Fiscal Registrar Connection Name
    |--------------------------------------------------------------------------
    */

    'default' => env('FR_CONNECTION', 'atol'),

    'connections' => [

        'atol' => [
            'display_name' => 'Тестовое подключение АТОЛ',
            'test' => true,
            'url' => 'https://testonline.atol.ru/possystem',
            'login' => 'v4-online-atol-ru',
            'password' => 'iGFFuihss',
            'name' => 'ООО "АТОЛ Онлайн Тест"',
            'inn' => '5544332219',
            'email' => 'info@atol.ru',
            'payment_site' => 'https://v4.online.atol.ru',
            'group_code' => 'v4-online-atol-ru_4179',
            'url_generator' => TTBooking\FiscalRegistrar\Support\PlatformaOfdReceiptUrlGenerator::class,
        ],

        'proxy' => [
            'test' => true,
            'url' => 'http://localhost/fiscal-registrar/api/v1/connection/atol',
            'login' => '',
            'password' => '',
        ],

    ],

];
