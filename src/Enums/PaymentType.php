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
    public const Cash = 0;
    public const Electronic = 1;
    public const Prepaid = 2;
    public const Postpaid = 3;
    public const Other = 4;
    public const Extended5 = 5;
    public const Extended6 = 6;
    public const Extended7 = 7;
    public const Extended8 = 8;
    public const Extended9 = 9;
}
