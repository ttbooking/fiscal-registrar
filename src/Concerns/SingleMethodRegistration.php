<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\Concerns;

use TTBooking\FiscalRegistrar\DTO\Receipt;
use TTBooking\FiscalRegistrar\DTO\Result;
use TTBooking\FiscalRegistrar\Exceptions;

trait SingleMethodRegistration
{
    public function sell(string $externalId, Receipt $receipt): Result
    {
        return $this->register(__FUNCTION__, $externalId, $receipt);
    }

    public function sellRefund(string $externalId, Receipt $receipt): Result
    {
        return $this->register(__FUNCTION__, $externalId, $receipt);
    }

    public function buy(string $externalId, Receipt $receipt): Result
    {
        return $this->register(__FUNCTION__, $externalId, $receipt);
    }

    public function buyRefund(string $externalId, Receipt $receipt): Result
    {
        return $this->register(__FUNCTION__, $externalId, $receipt);
    }

    /**
     * @param  string  $operation
     * @param  string  $externalId
     * @param  Receipt  $receipt
     * @return Result
     *
     * @throws Exceptions\FiscalRegistrarException
     */
    abstract protected function register(string $operation, string $externalId, Receipt $receipt): Result;
}
