<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\DTO\Receipt;

use TTBooking\FiscalRegistrar\DTO\DataTransferObject;

final class Payments extends DataTransferObject
{
    // 1031
    public float|int $cash = 0;

    // 1081
    public float|int $electronic = 0;

    // 1215
    public float|int $prepaid = 0;

    // 1216
    public float|int $postpaid = 0;

    // 1217
    public float|int $other = 0;
}
