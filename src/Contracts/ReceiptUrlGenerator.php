<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\Contracts;

use TTBooking\FiscalRegistrar\DTO\Result;

interface ReceiptUrlGenerator
{
    public function fromResult(Result $result): ?string;
}
