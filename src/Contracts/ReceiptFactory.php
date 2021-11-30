<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\Contracts;

use TTBooking\FiscalRegistrar\DTO;
use TTBooking\FiscalRegistrar\Exceptions\ResolverException;

interface ReceiptFactory
{
    /**
     * @param  DTO\Receipt  $payload
     * @return Receipt
     */
    public function make(DTO\Receipt $payload): Receipt;

    /**
     * @param  mixed  $id
     * @return Receipt
     *
     * @throws ResolverException
     */
    public function resolve($id): Receipt;
}
