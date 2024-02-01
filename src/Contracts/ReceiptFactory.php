<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\Contracts;

use TTBooking\FiscalRegistrar\DTO;
use TTBooking\FiscalRegistrar\Exceptions\ResolverException;

interface ReceiptFactory
{
    public function make(DTO\Receipt $payload): Receipt;

    /**
     * @param  scalar  $id
     *
     * @throws ResolverException
     */
    public function resolve(mixed $id): Receipt;
}
