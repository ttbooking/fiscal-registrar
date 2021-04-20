<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\DTO\Receipt;

use Spatie\DataTransferObject\Attributes\CastWith;
use TTBooking\FiscalRegistrar\DTO\Casters\SNOCaster;
use TTBooking\FiscalRegistrar\DTO\DataTransferObject;
use TTBooking\FiscalRegistrar\Enums\SNO;

final class Company extends DataTransferObject
{
    public function __construct(

        // 1117
        public string $email,

        // 1018
        public string $inn,

        // 1187
        public string $payment_address,

        // 1055
        #[CastWith(SNOCaster::class)]
        public ?SNO $sno = null,

    ) {
        parent::__construct(...func_get_args());
    }
}
