<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\Concerns;

use TTBooking\FiscalRegistrar\DTO\Receipt;
use TTBooking\FiscalRegistrar\DTO\Result;
use TTBooking\FiscalRegistrar\Enums\Operation;
use TTBooking\FiscalRegistrar\Exceptions;

trait SingleMethodRegistration
{
    public function sell(string $externalId, Receipt $receipt): Result
    {
        return $this->register(Operation::Sell(), $externalId, $receipt);
    }

    public function sellRefund(string $externalId, Receipt $receipt): Result
    {
        return $this->register(Operation::SellRefund(), $externalId, $receipt);
    }

    public function buy(string $externalId, Receipt $receipt): Result
    {
        return $this->register(Operation::Buy(), $externalId, $receipt);
    }

    public function buyRefund(string $externalId, Receipt $receipt): Result
    {
        return $this->register(Operation::BuyRefund(), $externalId, $receipt);
    }

    /**
     * @param  Operation  $operation
     * @param  string  $externalId
     * @param  Receipt  $receipt
     * @return Result
     *
     * @throws Exceptions\FiscalRegistrarException
     */
    abstract protected function register(Operation $operation, string $externalId, Receipt $receipt): Result;
}
