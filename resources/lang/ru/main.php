<?php

return [

    'title' => 'Фискальный Регистратор',

    'receipt' => [

        'title' => 'кассовый чек',

        'client' => [
            'phone_or_email' => 'телефон или электронный адрес покупателя',
            'email' => 'электронная почта покупателя',
            'phone' => 'телефон покупателя',
            'name' => 'наименование покупателя (клиента)',
            'inn' => 'ИНН покупателя (клиента)',
        ],

        'company' => [
            'email' => 'электронная почта отправителя чека',
            'tax_system' => 'система налогообложения',
            'inn' => 'ИНН организации',
            'payment_address' => 'место расчетов',
        ],

        'agent_info' => [
            'type' => 'признак агента',
            'paying_agent' => [
                'operation' => 'наименование операции',
                'phones' => 'телефоны платежного агента',
            ],
            'receive_payments_operator' => [
                'phones' => 'телефоны оператора по приему платежей',
            ],
            'money_transfer_operator' => [
                'phones' => 'телефоны оператора перевода',
                'name' => 'наименование оператора перевода',
                'address' => 'адрес оператора перевода',
                'inn' => 'ИНН оператора перевода',
            ],
        ],

        'supplier_info' => [
            'phones' => 'телефоны поставщика',
        ],

        'items' => [
            'name' => 'наименование товара',
            'price' => 'цена в рублях',
            'quantity' => 'количество/вес',
            'sum' => 'сумма в рублях',
            'measurement_unit' => 'единица измерения',
            'nomenclature_code' => 'код товара',
            'payment_method' => 'способ расчета',
            'payment_object' => 'предмет расчета',
            'vat' => [
                'type' => 'ставка налога на позицию',
                'sum' => 'сумма налога позиции в рублях',
            ],
            'agent_info' => [
                'type' => 'признак агента по предмету расчета',
            ],
            'supplier_info' => [
                'phones' => 'телефоны поставщика',
                'name' => 'наименование поставщика',
                'inn' => 'ИНН поставщика',
            ],
            'user_data' => 'дополнительный реквизит предмета расчета',
            'excise' => 'сумма акциза в рублях',
            'country_code' => 'код страны происхождения товара',
            'declaration_number' => 'номер таможенной декларации',
        ],

        'payments' => [
            'cash' => 'наличными',
            'electronic' => 'безналичными',
            'prepaid' => 'зачет предоплаты (аванса)',
            'postpaid' => 'сумма по чеку (БСО) в кредит',
            'other' => 'сумма по чеку (БСО) встречным представлением',
        ],

        'vats' => [
            'vat20' => 'НДС 20%',
            'vat10' => 'НДС 10%',
            'with_vat0' => 'итого с НДС 0%',
            'without_vat' => 'итого без НДС',
            'vat120' => 'НДС 20/120',
            'vat110' => 'НДС 10/110',
        ],

        'total' => 'итоговая сумма чека в рублях',

        'additional_check_props' => 'дополнительный реквизит чека',

        'cashier' => 'ФИО кассира',

        'additional_user_props' => [
            'name' => 'наименование дополнительного реквизита пользователя',
            'value' => 'значение дополнительного реквизита пользователя',
        ],

    ],

    'result' => [
        'fiscal_receipt_number' => 'номер чека в смене',
        'shift_number' => 'номер смены',
        'receipt_datetime' => 'дата и время документа из ФН',
        'total' => 'итоговая сумма документа в рублях',
        'fn_number' => 'номер ФН',
        'ecr_registration_number' => 'регистрационный номер ККТ',
        'fiscal_document_number' => 'фискальный номер документа',
        'fiscal_document_attribute' => 'фискальный признак документа',
        'fns_site' => 'адрес сайта ФНС',
        'device_code' => 'номер автомата',
        'online_attribute' => 'признак расчетов в сети Интернет',
        'ffd_version' => 'версия ФФД',
    ],

    'shared' => [
        'yes' => 'да',
        'no' => 'нет',
        '#' => '№',
    ],
];
