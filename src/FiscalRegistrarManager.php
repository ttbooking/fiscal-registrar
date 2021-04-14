<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar;

use Illuminate\Contracts\Events\Dispatcher;
use TTBooking\FiscalRegistrar\Drivers\Atol\AtolDriver;
use TTBooking\FiscalRegistrar\DTO\Receipt;
use TTBooking\FiscalRegistrar\DTO\Result;
use TTBooking\FiscalRegistrar\Enums\Operation;

/**
 * @method Contracts\FiscalRegistrar connection(string $name = null)
 */
class FiscalRegistrarManager extends Support\Manager implements
    Contracts\ConnectionAware,
    Contracts\FiscalRegistrarFactory,
    Contracts\FiscalRegistrar
{
    protected string $configName = 'fiscal-registrar';

    public function getConnectionName(): string
    {
        return $this->getDefaultDriver();
    }

    public function register(Operation $operation, string $externalId, Receipt $data): string
    {
        return $this->connection()->register($operation, $externalId, $data);
    }

    public function report(string $id): ?Result
    {
        return $this->connection()->report($id);
    }

    public function processCallback($payload): Result
    {
        return $this->connection()->processCallback($payload);
    }

    /**
     * Re-set the event dispatcher on all resolved driver instances.
     *
     * @return $this
     */
    public function refreshEventDispatcher(): self
    {
        array_map([$this, 'setEventDispatcher'], $this->getConnections());

        return $this;
    }

    /**
     * Create an instance of the Atol fiscal registrar Driver.
     *
     * @param  array  $config
     * @param  string  $connection
     * @return AtolDriver
     */
    protected function createAtolDriver(array $config, string $connection): Contracts\FiscalRegistrar
    {
        return $this->configureInstance(
            $this->container->make(AtolDriver::class, compact('config', 'connection'))
        );
    }

    /**
     * @param  Contracts\FiscalRegistrar  $fiscalRegistrar
     * @return Contracts\FiscalRegistrar
     */
    protected function configureInstance(Contracts\FiscalRegistrar $fiscalRegistrar): Contracts\FiscalRegistrar
    {
        if (! $fiscalRegistrar instanceof Contracts\DispatchesEvents) {
            $fiscalRegistrar = $this->decorateInstance($fiscalRegistrar);
        }

        $this->setEventDispatcher($fiscalRegistrar);

        return $fiscalRegistrar;
    }

    /**
     * @param  Contracts\FiscalRegistrar  $fiscalRegistrar
     * @return Contracts\FiscalRegistrar|Contracts\DispatchesEvents
     */
    protected function decorateInstance(Contracts\FiscalRegistrar $fiscalRegistrar): Contracts\FiscalRegistrar
    {
        return $this->container->make(Support\DriverDispatchingDecorator::class, compact('fiscalRegistrar'));
    }

    /**
     * Set the event dispatcher on the given driver instance.
     *
     * @param  Contracts\DispatchesEvents  $instance
     * @return $this
     */
    protected function setEventDispatcher(Contracts\DispatchesEvents $instance): self
    {
        if ($this->container->bound(Dispatcher::class)) {
            $instance->setEventDispatcher($this->container->make(Dispatcher::class));
        }

        return $this;
    }
}
