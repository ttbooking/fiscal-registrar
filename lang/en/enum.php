<?php

return [

    'agent_type' => [
        'bank_paying_agent' => 'bank paying agent',
        'bank_paying_subagent' => 'bank paying subagent',
        'paying_agent' => 'paying agent',
        'paying_subagent' => 'paying subagent',
        'attorney' => 'attorney',
        'commission_agent' => 'commission agent',
        'another' => 'another',
    ],

    'operation' => [
        'sell' => 'sell',
        'sell_refund' => 'sell refund',
        'sell_correction' => 'sell correction',
        'buy' => 'buy',
        'buy_refund' => 'buy refund',
        'buy_correction' => 'buy correction',
    ],

    'payment_method' => [
        'full_prepayment' => 'full prepayment',
        'prepayment' => 'prepayment',
        'advance' => 'advance',
        'full_payment' => 'full payment',
        'partial_payment' => 'partial payment and credit',
        'credit' => 'credit',
        'credit_payment' => 'credit payment',
    ],

    'payment_object' => [
        'commodity' => 'commodity',
        'excise' => 'excise',
        'job' => 'job',
        'service' => 'service',
        'gambling_bet' => 'gambling bet',
        'gambling_prize' => 'gambling prize',
        'lottery' => 'lottery ticket',
        'lottery_prize' => 'lottery prize',
        'intellectual_activity' => 'intellectual activity',
        'payment' => 'payment',
        'agent_commission' => 'agent commission',
        'award' => 'award',
        'composite' => 'composite payment object',
        'another' => 'another payment object',
        'property_right' => 'property right',
        'non-operating_gain' => 'non-operating gain',
        'insurance_premium' => 'insurance premium',
        'sales_tax' => 'sales tax',
        'resort_fee' => 'resort fee',
        'deposit' => 'deposit',
        'expense' => 'expense',
        'pension_insurance_ip' => 'pension insurance fees (entrepreneur)',
        'pension_insurance' => 'pension insurance fees',
        'medical_insurance_ip' => 'medical insurance fees (entrepreneur)',
        'medical_insurance' => 'medical insurance fees',
        'social_insurance' => 'social insurance fees',
        'casino_payment' => 'casino payment',
    ],

    'tax_system' => [
        'osn' => 'general tax system',
        'usn_income' => 'simplified tax system (income)',
        'usn_income_outcome' => 'simplified tax system (income with costs deducted)',
        'envd' => 'unified tax on imputed income',
        'esn' => 'unified agricultural tax',
        'patent' => 'patent based simplified tax system',
    ],

    'tax_system_short' => [
        'osn' => 'GTS',
        'usn_income' => 'STS (income)',
        'usn_income_outcome' => 'STS (income w/ costs deducted)',
        'envd' => 'UTII',
        'esn' => 'UAT',
        'patent' => 'PTS',
    ],

    'vat_type' => [
        'none' => 'w/o VAT',
        'vat0' => 'VAT 0%',
        'vat5' => 'VAT 5%',
        'vat7' => 'VAT 7%',
        'vat10' => 'VAT 10%',
        'vat18' => 'VAT 18%',
        'vat20' => 'VAT 20%',
        'vat105' => 'VAT 5/105',
        'vat107' => 'VAT 7/107',
        'vat110' => 'VAT 10/110',
        'vat118' => 'VAT 18/118',
        'vat120' => 'VAT 20/120',
    ],

    'vat_type_short' => [
        'none' => 'none',
        'vat0' => '0%',
        'vat10' => '10%',
        'vat18' => '18%',
        'vat20' => '20%',
        'vat110' => '10/110',
        'vat118' => '18/118',
        'vat120' => '20/120',
    ],

];
