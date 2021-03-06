<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\Support;

use Closure;
use Illuminate\Database\Eloquent\Model;
use TTBooking\FiscalRegistrar\Concerns;
use TTBooking\FiscalRegistrar\Contracts;
use TTBooking\FiscalRegistrar\DTO;
use TTBooking\FiscalRegistrar\Enums\Operation;
use TTBooking\FiscalRegistrar\Events;
use TTBooking\FiscalRegistrar\Exceptions;
use TTBooking\FiscalRegistrar\Models\Receipt;

/**
 * @method Contracts\FiscalRegistrar getDecoratedInstance
 */
class DriverDispatchingDecorator extends Decorator implements
    Contracts\ConnectionAware,
    Contracts\FiscalRegistrar,
    Contracts\SupportsCallbacks,
    Contracts\DispatchesEvents
{
    use Concerns\HasEvents;

    protected Receipt $receipt;

    public function __construct(Contracts\FiscalRegistrar $fiscalRegistrar, Receipt $receipt)
    {
        parent::__construct($fiscalRegistrar);
        $this->receipt = $receipt;
    }

    public function getConnectionName(): string
    {
        return self::instanceOf($this->getDecoratedInstance(), Contracts\ConnectionAware::class)
            ? $this->getDecoratedInstance()->getConnectionName() : 'unknown';
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

        $receipt->internal_id = $this->getDecoratedInstance()->register($operation, $externalId, $data);
        $receipt->data = $data;
        $receipt->state = Receipt::STATE_REGISTERED;
        $receipt->save();

        $this->event(new Events\Registered($receipt));

        return $receipt->internal_id;
    }

    public function report(string $id): ?DTO\Result
    {
        if ($result = $this->getDecoratedInstance()->report($id)) {
            $this->event(new Events\Processed($this->updateReceipt($result)));
        }

        return $result;
    }

    public function processCallback($payload, Closure $handler = null): void
    {
        if (! self::instanceOf($this->getDecoratedInstance(), Contracts\SupportsCallbacks::class)) {
            return;
        }

        $this->getDecoratedInstance()->processCallback($payload, function (DTO\Result $result) use ($handler) {
            $handler && $handler($result);
            $this->event(new Events\Processed($this->updateReceipt($result)));
        });
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
        ])->firstOrFail())->update(
            compact('result') + ['state' => Receipt::STATE_PROCESSED]
        );
    }
}
