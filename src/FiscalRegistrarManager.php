<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar;

use Closure;
use Illuminate\Contracts\Events\Dispatcher;
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

    public function register(Operation $operation, string $externalId, Receipt $payload): string
    {
        return $this->connection()->register($operation, $externalId, $payload);
    }

    public function report(string $id): ?Result
    {
        return $this->connection()->report($id);
    }

    public function processCallback($payload, Closure $handler = null): void
    {
        $this->connection()->processCallback($payload, $handler);
    }

    /**
     * Re-set the event dispatcher on all resolved driver instances.
     *
     * @return $this
     */
    public function refreshEventDispatcher(): static
    {
        array_map([$this, 'setEventDispatcher'], $this->getConnections());

        return $this;
    }

    /**
     * Create an instance of the Atol fiscal registrar Driver.
     *
     * @param  array  $config
     * @param  string  $connection
     * @return Drivers\AtolDriver
     */
    protected function createAtolDriver(array $config, string $connection): Contracts\FiscalRegistrar
    {
        return $this->configureInstance(
            $this->container->make(Drivers\AtolDriver::class, compact('config', 'connection')), $config
        );
    }

    /**
     * Create an instance of the Proxy fiscal registrar Driver.
     *
     * @param  array  $config
     * @param  string  $connection
     * @return Drivers\ProxyDriver
     */
    protected function createProxyDriver(array $config, string $connection): Contracts\FiscalRegistrar
    {
        return $this->configureInstance(
            $this->container->make(Drivers\ProxyDriver::class, compact('config', 'connection')), $config
        );
    }

    /**
     * @param  Contracts\FiscalRegistrar  $fiscalRegistrar
     * @param  array  $config
     * @return Contracts\FiscalRegistrar
     */
    protected function configureInstance(
        Contracts\FiscalRegistrar $fiscalRegistrar,
        array $config
    ): Contracts\FiscalRegistrar {
        if ($fiscalRegistrar instanceof Contracts\GeneratesReceiptUrls && isset($config['url_generator'])) {
            $fiscalRegistrar->setUrlGenerator($this->container->make($config['url_generator']));
        }

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
    protected function setEventDispatcher(Contracts\DispatchesEvents $instance): static
    {
        if ($this->container->bound(Dispatcher::class)) {
            $instance->setEventDispatcher($this->container->make(Dispatcher::class));
        }

        return $this;
    }
}
