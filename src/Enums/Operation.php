<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\Enums;

enum Operation: string
{
    use Translatable;

    case Sell = 'sell';
    case SellRefund = 'sell_refund';
    case Buy = 'buy';
    case BuyRefund = 'buy_refund';
}
