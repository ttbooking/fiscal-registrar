<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\Enums;

enum VatType: string
{
    use Translatable;

    case None = 'none';
    case VAT0 = 'vat0';
    case VAT10 = 'vat10';
    case VAT18 = 'vat18';
    case VAT20 = 'vat20';
    case VAT110 = 'vat110';
    case VAT118 = 'vat118';
    case VAT120 = 'vat120';

    public function getRate(): ?float
    {
        return match ($this) {
            self::VAT0 => 0,
            self::VAT10 => .10,
            self::VAT18 => .18,
            self::VAT20 => .20,
            self::VAT110 => 10 / 110,
            self::VAT118 => 18 / 118,
            self::VAT120 => 20 / 120,
            default => null,
        };
    }
}
