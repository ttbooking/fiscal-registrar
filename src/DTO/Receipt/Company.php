<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\DTO\Receipt;

use Spatie\DataTransferObject\Attributes\CastWith;
use TTBooking\FiscalRegistrar\DTO\Casters\TaxSystemCaster;
use TTBooking\FiscalRegistrar\DTO\DataTransferObject;
use TTBooking\FiscalRegistrar\Enums\TaxSystem;

final class Company extends DataTransferObject
{
    // 1117
    public ?string $email = null;

    // 1018
    public ?string $inn = null;

    // 1187
    public ?string $payment_address = null;

    // 1055
    #[CastWith(TaxSystemCaster::class)]
    public ?TaxSystem $tax_system = null;
}
