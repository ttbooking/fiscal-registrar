<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\Support;

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
    Contracts\SupportsCallbacks,
    Contracts\DispatchesEvents
{
    use Concerns\HasEvents;

    protected Contracts\FiscalRegistrar $fiscalRegistrar;

    public function __construct(Contracts\FiscalRegistrar $fiscalRegistrar)
    {
        $this->fiscalRegistrar = $fiscalRegistrar;
    }

    public function getConnectionName(): string
    {
        return $this->fiscalRegistrar instanceof Contracts\ConnectionAware
            ? $this->fiscalRegistrar->getConnectionName() : 'unknown';
    }

    /**
     * @param  Operation  $operation
     * @param  string  $externalId
     * @param  Receipt  $data
     * @return Result
     *
     * @throws Exceptions\FiscalRegistrarException
     */
    public function register(Operation $operation, string $externalId, Receipt $data): Result
    {
        $this->event(new Events\Registering($this->getConnectionName(), $operation, $externalId, $data));

        $result = $this->fiscalRegistrar->register($operation, $externalId, $data);

        $this->event(new Events\Registered(
            $this->getConnectionName(), $operation, $externalId, $result->internal_id, $data
        ));

        return $result;
    }

    public function report(string $id): Result
    {
        return $this->fiscalRegistrar->report($id);
    }

    public function processCallback($payload): ?Result
    {
        if (! $this->fiscalRegistrar instanceof Contracts\SupportsCallbacks) {
            return null;
        }

        if (! is_null($result = $this->fiscalRegistrar->processCallback($payload))) {
            $this->event(new Events\Processed($this->getConnectionName(), $result->internal_id, $result));
        }

        return $result;
    }
}
