<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\Enums;

enum VatType: string
{
    use Translatable;

    case None = 'none';
    case VAT0 = 'vat0';
    case VAT5 = 'vat5';
    case VAT7 = 'vat7';
    case VAT10 = 'vat10';
    case VAT18 = 'vat18';
    case VAT20 = 'vat20';
    case VAT22 = 'vat22';
    case VAT105 = 'vat105';
    case VAT107 = 'vat107';
    case VAT110 = 'vat110';
    case VAT118 = 'vat118';
    case VAT120 = 'vat120';
    case VAT122 = 'vat122';

    public function getRate(): ?float
    {
        return match ($this) {
            self::VAT0 => 0,
            self::VAT5 => .05,
            self::VAT7 => .07,
            self::VAT10 => .10,
            self::VAT18 => .18,
            self::VAT20 => .20,
            self::VAT22 => .22,
            self::VAT105 => 5 / 105,
            self::VAT107 => 7 / 107,
            self::VAT110 => 10 / 110,
            self::VAT118 => 18 / 118,
            self::VAT120 => 20 / 120,
            self::VAT122 => 22 / 122,
            default => null,
        };
    }
}
