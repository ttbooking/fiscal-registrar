<?php

return [

    'title' => 'Fiscal Registrar',

    'connection' => 'connection',
    'operation' => 'operation',
    'external_id' => 'unique identifier',

    'receipt' => [

        'title' => 'receipt',

        'payments' => [
            'cash' => 'cash',
            'electronic' => 'electronic',
            'prepaid' => 'prepayment (advance) set off',
            'postpaid' => 'credit',
            'other' => 'counter submission',
        ],

    ],

    'notification' => [
        'subject' => 'Receipt',
        'intro' => 'Your electronic receipt.',
        'view_receipt' => 'View receipt at FDO',
        'sms' => 'Receipt for :total RUB.',
    ],

    'shared' => [
        'yes' => 'yes',
        'no' => 'no',
        '#' => '#',
        'sample' => 'sample',
    ],

];
