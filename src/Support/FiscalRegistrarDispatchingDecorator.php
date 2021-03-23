<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\Support;

use TTBooking\FiscalRegistrar\Concerns;
use TTBooking\FiscalRegistrar\Contracts;
use TTBooking\FiscalRegistrar\DTO\Receipt;
use TTBooking\FiscalRegistrar\DTO\Result;
use TTBooking\FiscalRegistrar\Events\ReceiptEvent;
use TTBooking\FiscalRegistrar\Exceptions;

class FiscalRegistrarDispatchingDecorator implements Contracts\FiscalRegistrar, Contracts\DispatchesEvents
{
    use Concerns\HasEvents, Concerns\SingleMethodRegistration;

    protected Contracts\FiscalRegistrar $fiscalRegistrar;

    public function __construct(Contracts\FiscalRegistrar $fiscalRegistrar)
    {
        $this->fiscalRegistrar = $fiscalRegistrar;
    }

    public function report(string $id): Result
    {
        return $this->fiscalRegistrar->{__FUNCTION__}($id);
    }

    public function processCallback($payload)
    {
        return $this->fiscalRegistrar->{__FUNCTION__}($payload);
    }

    /**
     * @param  string  $operation
     * @param  string  $externalId
     * @param  Receipt  $receipt
     * @return Result
     *
     * @throws Exceptions\FiscalRegistrarException
     */
    protected function register(string $operation, string $externalId, Receipt $receipt): Result
    {
        $this->event(new ReceiptEvent(
            $this->fiscalRegistrar->connection, $operation, $externalId, null, $receipt, null
        ));

        $result = $this->fiscalRegistrar->{$operation}($externalId, $receipt);

        $this->event(new ReceiptEvent(
            $this->fiscalRegistrar->connection, $operation, $externalId, $result->internalId, $receipt, $result
        ));

        return $result;
    }
}
