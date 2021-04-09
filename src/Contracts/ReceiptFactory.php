<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\Contracts;

use TTBooking\FiscalRegistrar\DTO;
use TTBooking\FiscalRegistrar\Exceptions\ResolverException;

interface ReceiptFactory
{
    /**
     * @param  DTO\Receipt  $data
     * @return Receipt
     */
    public function make(DTO\Receipt $data): Receipt;

    /**
     * @param  mixed  $id
     * @return Receipt
     *
     * @throws ResolverException
     */
    public function resolve($id): Receipt;
}
