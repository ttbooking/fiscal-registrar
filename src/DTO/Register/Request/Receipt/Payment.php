<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\DTO\Register\Request\Receipt;

final class Payment
{
    // 1031, 1081, 1215, 1216, 1217 (enum)
    public int $type;

    // 1031, 1081, 1215, 1216, 1217
    public float $sum;

    /**
     * Payment constructor.
     *
     * @param  int  $type
     * @param  float  $sum
     */
    public function __construct(int $type, float $sum)
    {
        $this->type = $type;
        $this->sum = $sum;
    }
}
