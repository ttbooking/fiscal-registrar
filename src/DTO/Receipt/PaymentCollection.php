<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\DTO\Receipt;

use Illuminate\Support\Collection;
use Spatie\DataTransferObject\Attributes\CastWith;
use TTBooking\FiscalRegistrar\DTO\Casters\PaymentCollectionCaster;

/**
 * @method \TTBooking\FiscalRegistrar\DTO\Receipt\Payment offsetGet(mixed $key)
 */
#[CastWith(PaymentCollectionCaster::class)]
class PaymentCollection extends Collection
{
}
