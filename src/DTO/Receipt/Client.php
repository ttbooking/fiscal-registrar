<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\DTO\Receipt;

use TTBooking\FiscalRegistrar\DTO\DataTransferObject;

final class Client extends DataTransferObject
{
    // 1008
    public ?string $email = null;
    public ?string $phone = null;

    // 1227
    public ?string $name = null;

    // 1228
    public ?string $inn = null;
}
