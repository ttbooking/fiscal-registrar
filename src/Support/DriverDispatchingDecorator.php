<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\Support;

use Illuminate\Support\Str;
use TTBooking\FiscalRegistrar\Concerns;
use TTBooking\FiscalRegistrar\Contracts;
use TTBooking\FiscalRegistrar\DTO\Receipt;
use TTBooking\FiscalRegistrar\DTO\Result;
use TTBooking\FiscalRegistrar\Enums\Operation;
use TTBooking\FiscalRegistrar\Events;
use TTBooking\FiscalRegistrar\Exceptions;

class DriverDispatchingDecorator implements
    Contracts\ConnectionAware,
    Contracts\FiscalRegistrar,
    Contracts\DispatchesEvents
{
    use Concerns\HasEvents, Concerns\SingleMethodRegistration;

    protected Contracts\FiscalRegistrar $fiscalRegistrar;

    public function __construct(Contracts\FiscalRegistrar $fiscalRegistrar)
    {
        $this->fiscalRegistrar = $fiscalRegistrar;
    }

    public function getConnectionName(): string
    {
        return $this->fiscalRegistrar instanceof Contracts\ConnectionAware
            ? $this->fiscalRegistrar->{__FUNCTION__}() : 'unknown';
    }

    public function report(string $id): Result
    {
        return $this->fiscalRegistrar->{__FUNCTION__}($id);
    }

    public function processCallback($payload): Result
    {
        $result = $this->fiscalRegistrar->{__FUNCTION__}($payload);

        $this->event(new Events\Processed(
            $this->getConnectionName(), '', '', $result->internalId, null, $result
        ));

        return $result;
    }

    /**
     * @param  Operation  $operation
     * @param  string  $externalId
     * @param  Receipt  $receipt
     * @return Result
     *
     * @throws Exceptions\FiscalRegistrarException
     */
    protected function register(Operation $operation, string $externalId, Receipt $receipt): Result
    {
        $operationMethod = Str::camel($operation->getValue());

        $this->event(new Events\Registering(
            $this->getConnectionName(), $operation, $externalId, null, $receipt, null
        ));

        $result = $this->fiscalRegistrar->{$operationMethod}($externalId, $receipt);

        $this->event(new Events\Registered(
            $this->getConnectionName(), $operation, $externalId, $result->internalId, $receipt, $result
        ));

        return $result;
    }
}