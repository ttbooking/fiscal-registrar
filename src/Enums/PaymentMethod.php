<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\Enums;

enum PaymentMethod: string
{
    use Translatable;

    case FullPrepayment = 'full_prepayment';
    case Prepayment = 'prepayment';
    case Advance = 'advance';
    case FullPayment = 'full_payment';
    case PartialPayment = 'partial_payment';
    case Credit = 'credit';
    case CreditPayment = 'credit_payment';
}
