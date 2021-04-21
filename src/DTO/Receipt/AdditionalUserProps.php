<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\DTO\Receipt;

use TTBooking\FiscalRegistrar\DTO\DataTransferObject;

final class AdditionalUserProps extends DataTransferObject
{
    // 1085
    public string $name;

    // 1086
    public string $value;
}
