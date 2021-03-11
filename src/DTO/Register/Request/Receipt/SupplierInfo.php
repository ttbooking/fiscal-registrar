<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\DTO\Register\Request\Receipt;

final class SupplierInfo
{
    // 1171
    /** @var string[]|null */
    public ?array $phones;

    /**
     * SupplierInfo constructor.
     *
     * @param  string[]|null  $phones
     */
    public function __construct(array $phones = null)
    {
        $this->phones = $phones;
    }
}
