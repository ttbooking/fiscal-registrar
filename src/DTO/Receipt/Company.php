<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\DTO\Receipt;

use TTBooking\FiscalRegistrar\DTO\DataTransferObject;
use TTBooking\FiscalRegistrar\Enums\TaxSystem;

final class Company extends DataTransferObject
{
    // 1048
    public ?string $name = null;

    // 1117
    public ?string $email = null;

    // 1018
    public ?string $inn = null;

    // 1009
    public ?string $payment_address = null;

    // 1187
    public ?string $payment_site = null;

    // 1055
    public ?TaxSystem $tax_system = null;
}
