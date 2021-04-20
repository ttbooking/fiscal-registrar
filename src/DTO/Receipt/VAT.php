<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\DTO\Receipt;

use Spatie\DataTransferObject\Attributes\CastWith;
use TTBooking\FiscalRegistrar\DTO\Casters\VATTypeCaster;
use TTBooking\FiscalRegistrar\DTO\DataTransferObject;
use TTBooking\FiscalRegistrar\Enums\VATType;

final class VAT extends DataTransferObject
{
    public function __construct(

        // 1105, 1104, 1103, 1102, 1107, 1106
        #[CastWith(VATTypeCaster::class)]
        public VATType $type,

        // 1105, 1104, 1103, 1102, 1107, 1106
        public float|int $sum,

    ) {
        parent::__construct(...func_get_args());
    }
}
