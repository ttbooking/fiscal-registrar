<?php

return [

    'agent_type' => [
        'bank_paying_agent' => 'банковский платежный агент',
        'bank_paying_subagent' => 'банковский платежный субагент',
        'paying_agent' => 'платежный агент',
        'paying_subagent' => 'платежный субагент',
        'attorney' => 'поверенный',
        'commission_agent' => 'комиссионер',
        'another' => 'другой',
    ],

    'operation' => [
        'sell' => 'приход',
        'sell_refund' => 'возврат прихода',
        'sell_correction' => 'коррекция прихода',
        'buy' => 'расход',
        'buy_refund' => 'возврат расхода',
        'buy_correction' => 'коррекция расхода',
    ],

    'payment_method' => [
        'full_prepayment' => 'предоплата 100%',
        'prepayment' => 'предоплата',
        'advance' => 'аванс',
        'full_payment' => 'полный расчет',
        'partial_payment' => 'частичный расчет и кредит',
        'credit' => 'передача в кредит',
        'credit_payment' => 'оплата кредита',
    ],

    'payment_object' => [
        'commodity' => 'товар',
        'excise' => 'подакцизный товар',
        'job' => 'работа',
        'service' => 'услуга',
        'gambling_bet' => 'ставка азартной игры',
        'gambling_prize' => 'выигрыш азартной игры',
        'lottery' => 'лотерейный билет',
        'lottery_prize' => 'выигрыш лотереи',
        'intellectual_activity' => 'предоставление РИД',
        'payment' => 'платеж',
        'agent_commission' => 'агентское вознаграждение',
        'award' => 'выплата',
        'composite' => 'составной предмет расчета',
        'another' => 'иной предмет расчета',
        'property_right' => 'имущественное право',
        'non-operating_gain' => 'внереализационный доход',
        'insurance_premium' => 'иные платежи и взносы',
        'sales_tax' => 'торговый сбор',
        'resort_fee' => 'курортный сбор',
        'deposit' => 'залог',
        'expense' => 'расход',
        'pension_insurance_ip' => 'взносы на ОПС ИП',
        'pension_insurance' => 'взносы на ОПС',
        'medical_insurance_ip' => 'взносы на ОМС ИП',
        'medical_insurance' => 'взносы на ОМС',
        'social_insurance' => 'взносы на ОСС',
        'casino_payment' => 'платеж казино',
    ],

    'tax_system' => [
        'osn' => 'общая система налогообложения',
        'usn_income' => 'упрощенная система налогообложения (доходы)',
        'usn_income_outcome' => 'упрощенная система налогообложения (доходы минус расходы)',
        'envd' => 'единый налог на вмененный доход',
        'esn' => 'единый сельскохозяйственный налог',
        'patent' => 'патентная система налогообложения',
    ],

    'tax_system_short' => [
        'osn' => 'ОСН',
        'usn_income' => 'УСН (доходы)',
        'usn_income_outcome' => 'УСН (доходы минус расходы)',
        'envd' => 'ЕНВД',
        'esn' => 'ЕСН',
        'patent' => 'ПСН',
    ],

    'vat_type' => [
        'none' => 'без НДС',
        'vat0' => 'НДС 0%',
        'vat5' => 'НДС 5%',
        'vat7' => 'НДС 7%',
        'vat10' => 'НДС 10%',
        'vat18' => 'НДС 18%',
        'vat20' => 'НДС 20%',
        'vat105' => 'НДС 5/105',
        'vat107' => 'НДС 7/107',
        'vat110' => 'НДС 10/110',
        'vat118' => 'НДС 18/118',
        'vat120' => 'НДС 20/120',
    ],

    'vat_type_short' => [
        'none' => 'не облагается',
        'vat0' => '0%',
        'vat10' => '10%',
        'vat18' => '18%',
        'vat20' => '20%',
        'vat110' => '10/110',
        'vat118' => '18/118',
        'vat120' => '20/120',
    ],

];
