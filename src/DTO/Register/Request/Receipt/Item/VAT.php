<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\DTO\Register\Request\Receipt\Item;

final class VAT
{
    // 1199 (enum)
    public string $type;

    // 1200
    public ?float $sum;

    /**
     * VAT constructor.
     *
     * @param  string  $type
     * @param  float|null  $sum
     */
    public function __construct(string $type, float $sum = null)
    {
        $this->type = $type;
        $this->sum = $sum;
    }
}
