<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\Enums;

/**
 * @method static self Cash
 * @method static self Electronic
 * @method static self Prepaid
 * @method static self Postpaid
 * @method static self Other
 * @method static self Extended5
 * @method static self Extended6
 * @method static self Extended7
 * @method static self Extended8
 * @method static self Extended9
 */
final class PaymentType extends Enum
{
    private const Cash = 0;
    private const Electronic = 1;
    private const Prepaid = 2;
    private const Postpaid = 3;
    private const Other = 4;
    private const Extended5 = 5;
    private const Extended6 = 6;
    private const Extended7 = 7;
    private const Extended8 = 8;
    private const Extended9 = 9;
}
