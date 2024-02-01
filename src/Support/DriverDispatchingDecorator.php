<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\Support;

use Closure;
use TTBooking\FiscalRegistrar\Concerns;
use TTBooking\FiscalRegistrar\Contracts;
use TTBooking\FiscalRegistrar\DTO;
use TTBooking\FiscalRegistrar\Enums\Operation;
use TTBooking\FiscalRegistrar\Events;
use TTBooking\FiscalRegistrar\Exceptions;
use TTBooking\FiscalRegistrar\Models\Receipt;

/**
 * @extends Decorator<Contracts\FiscalRegistrar>
 */
class DriverDispatchingDecorator extends Decorator implements Contracts\ConnectionAware, Contracts\DispatchesEvents, Contracts\FiscalRegistrar, Contracts\SupportsCallbacks
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
        if (self::instanceOf($instance = $this->getDecoratedInstance(), Contracts\ConnectionAware::class)) {
            /** @var Contracts\ConnectionAware $instance */
            return $instance->getConnectionName();
        }

        return 'unknown';
    }

    /**
     * @throws Exceptions\FiscalRegistrarException
     */
    public function register(Operation $operation, string $externalId, DTO\Receipt $payload): string
    {
        $receipt = $this->resolveOrMakeReceipt(...func_get_args());

        $this->event(new Events\Registering($receipt));

        $receipt->internal_id = $this->getDecoratedInstance()->register($operation, $externalId, $payload);
        $receipt->payload = $payload;
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

    public function processCallback(mixed $payload, ?Closure $handler = null): void
    {
        if (! self::instanceOf($instance = $this->getDecoratedInstance(), Contracts\SupportsCallbacks::class)) {
            return;
        }

        /** @var Contracts\SupportsCallbacks $instance */
        $instance->processCallback($payload, function (DTO\Result $result) use ($handler) {
            $handler && $handler($result);
            $this->event(new Events\Processed($this->updateReceipt($result)));
        });
    }

    protected function resolveOrMakeReceipt(Operation $operation, string $externalId, DTO\Receipt $payload): Receipt
    {
        /** @var Receipt */
        return $this->receipt->newQuery()->updateOrCreate([
            'connection' => $this->getConnectionName(),
            'external_id' => $externalId,
        ], compact('operation', 'payload'));
    }

    protected function updateReceipt(DTO\Result $result): Receipt
    {
        /** @var Receipt */
        return tap($this->receipt->newQuery()->where([
            'connection' => $this->getConnectionName(),
            'internal_id' => $result->internal_id,
        ])->firstOrFail())->update(
            compact('result') + ['state' => Receipt::STATE_PROCESSED]
        );
    }
}
