<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\DTO\Receipt;

final class VAT
{
    // 1105, 1104, 1103, 1102, 1107, 1106 (enum)
    public string $type;

    // 1105, 1104, 1103, 1102, 1107, 1106
    public float $sum;

    /**
     * VAT constructor.
     *
     * @param  string  $type
     * @param  float  $sum
     */
    public function __construct(string $type, float $sum)
    {
        $this->type = $type;
        $this->sum = $sum;
    }
}
