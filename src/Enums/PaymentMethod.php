<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\Enums;

use MyCLabs\Enum\Enum;

/**
 * @method static self FullPrepayment
 * @method static self Prepayment
 * @method static self Advance
 * @method static self FullPayment
 * @method static self PartialPayment
 * @method static self Credit
 * @method static self CreditPayment
 */
final class PaymentMethod extends Enum
{
    private const FullPrepayment = 'full_prepayment';
    private const Prepayment = 'prepayment';
    private const Advance = 'advance';
    private const FullPayment = 'full_payment';
    private const PartialPayment = 'partial_payment';
    private const Credit = 'credit';
    private const CreditPayment = 'credit_payment';
}
