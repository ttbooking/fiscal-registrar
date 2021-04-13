<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\Enums;

/**
 * @method static self Created
 * @method static self Registered
 * @method static self Processed
 */
final class State extends Enum
{
    private const Created = 0;
    private const Registered = 1;
    private const Processed = 2;
}
