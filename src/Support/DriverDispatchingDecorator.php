<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\Support;

use Illuminate\Database\Eloquent\Model;
use TTBooking\FiscalRegistrar\Concerns;
use TTBooking\FiscalRegistrar\Contracts;
use TTBooking\FiscalRegistrar\DTO;
use TTBooking\FiscalRegistrar\Enums\Operation;
use TTBooking\FiscalRegistrar\Events;
use TTBooking\FiscalRegistrar\Exceptions;
use TTBooking\FiscalRegistrar\Models\Receipt;

class DriverDispatchingDecorator implements
    Contracts\ConnectionAware,
    Contracts\FiscalRegistrar,
    Contracts\SupportsCallbacks,
    Contracts\DispatchesEvents
{
    use Concerns\HasEvents;

    protected Contracts\FiscalRegistrar $fiscalRegistrar;

    protected Receipt $receipt;

    public function __construct(Contracts\FiscalRegistrar $fiscalRegistrar, Receipt $receipt)
    {
        $this->fiscalRegistrar = $fiscalRegistrar;
        $this->receipt = $receipt;
    }

    public function getConnectionName(): string
    {
        return $this->fiscalRegistrar instanceof Contracts\ConnectionAware
            ? $this->fiscalRegistrar->getConnectionName() : 'unknown';
    }

    /**
     * @param  Operation  $operation
     * @param  string  $externalId
     * @param  DTO\Receipt  $data
     * @return string
     *
     * @throws Exceptions\FiscalRegistrarException
     */
    public function register(Operation $operation, string $externalId, DTO\Receipt $data): string
    {
        $receipt = $this->resolveOrMakeReceipt(...func_get_args());

        $this->event(new Events\Registering($receipt));

        $receipt->internal_id = $this->fiscalRegistrar->register($operation, $externalId, $data);
        $receipt->save();

        $this->event(new Events\Registered($receipt));

        return $receipt->internal_id;
    }

    public function report(string $id): ?DTO\Result
    {
        if ($result = $this->fiscalRegistrar->report($id)) {
            $this->event(new Events\Processed($this->updateReceipt($result)));
        }

        return $result;
    }

    public function processCallback($payload): ?DTO\Result
    {
        if (! $this->fiscalRegistrar instanceof Contracts\SupportsCallbacks) {
            return null;
        }

        if (! is_null($result = $this->fiscalRegistrar->processCallback($payload))) {
            $this->event(new Events\Processed($this->updateReceipt($result)));
        }

        return $result;
    }

    /**
     * @param  Operation  $operation
     * @param  string  $externalId
     * @param  DTO\Receipt  $data
     * @return Receipt|Model
     */
    protected function resolveOrMakeReceipt(Operation $operation, string $externalId, DTO\Receipt $data): Receipt
    {
        return $this->receipt->newQuery()->updateOrCreate([
            'connection' => $this->getConnectionName(),
            'external_id' => $externalId,
        ], compact('operation', 'data'));
    }

    /**
     * @param  DTO\Result  $result
     * @return Receipt|Model
     */
    protected function updateReceipt(DTO\Result $result): Receipt
    {
        return tap($this->receipt->newQuery()->where([
            'connection' => $this->getConnectionName(),
            'internal_id' => $result->internal_id,
        ])->firstOrFail())->update(compact('result'));
    }
}
