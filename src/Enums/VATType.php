<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\Enums;

/**
 * @method static self None
 * @method static self VAT0
 * @method static self VAT10
 * @method static self VAT18
 * @method static self VAT20
 * @method static self VAT110
 * @method static self VAT118
 * @method static self VAT120
 */
final class VATType extends Enum
{
    private const None = 'none';
    private const VAT0 = 'vat0';
    private const VAT10 = 'vat10';
    private const VAT18 = 'vat18';
    private const VAT20 = 'vat20';
    private const VAT110 = 'vat110';
    private const VAT118 = 'vat118';
    private const VAT120 = 'vat120';

    public function getRate(): ?float
    {
        return match ($this->getValue()) {
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
