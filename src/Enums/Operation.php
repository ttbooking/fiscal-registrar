<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\Enums;

/**
 * @method static self Sell()
 * @method static self SellRefund()
 * @method static self Buy()
 * @method static self BuyRefund()
 */
final class Operation extends Enum
{
    private const Sell = 'sell';
    private const SellRefund = 'sell_refund';
    private const Buy = 'buy';
    private const BuyRefund = 'buy_refund';
}
