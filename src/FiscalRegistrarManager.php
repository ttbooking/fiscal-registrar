<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar;

use Closure;
use Illuminate\Contracts\Events\Dispatcher;
use TTBooking\FiscalRegistrar\Contracts\ReceiptUrlGenerator;
use TTBooking\FiscalRegistrar\Contracts\SupportsCallbacks;
use TTBooking\FiscalRegistrar\DTO\Receipt;
use TTBooking\FiscalRegistrar\DTO\Result;
use TTBooking\FiscalRegistrar\Enums\Operation;

/**
 * @extends Support\Manager<Contracts\FiscalRegistrar>
 */
class FiscalRegistrarManager extends Support\Manager implements Contracts\ConnectionAware, Contracts\FiscalRegistrar, Contracts\FiscalRegistrarFactory
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

    public function processCallback(mixed $payload, ?Closure $handler = null): void
    {
        if ($this->connection() instanceof SupportsCallbacks) {
            $this->connection()->processCallback($payload, $handler);
        }
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
     * @param  array<mixed>  $config
     * @return Drivers\AtolDriver
     */
    protected function createAtolDriver(array $config, string $connection): Contracts\FiscalRegistrar
    {
        /** @var Drivers\AtolDriver $driver */
        $driver = $this->container->make(Drivers\AtolDriver::class, compact('config', 'connection'));

        return $this->configureInstance($driver, $config);
    }

    /**
     * Create an instance of the Proxy fiscal registrar Driver.
     *
     * @param  array<mixed>  $config
     * @return Drivers\ProxyDriver
     */
    protected function createProxyDriver(array $config, string $connection): Contracts\FiscalRegistrar
    {
        /** @var Drivers\ProxyDriver $driver */
        $driver = $this->container->make(Drivers\ProxyDriver::class, compact('config', 'connection'));

        return $this->configureInstance($driver, $config);
    }

    /**
     * @template T of Contracts\FiscalRegistrar
     *
     * @param  T  $fiscalRegistrar
     * @param  array{url_generator?: ?class-string<ReceiptUrlGenerator>}  $config
     * @return Contracts\DispatchesEvents&T
     */
    protected function configureInstance(
        Contracts\FiscalRegistrar $fiscalRegistrar,
        array $config
    ): Contracts\FiscalRegistrar {
        if ($fiscalRegistrar instanceof Contracts\GeneratesReceiptUrls && isset($config['url_generator'])) {
            /** @var ReceiptUrlGenerator $urlGenerator */
            $urlGenerator = $this->container->make($config['url_generator']);
            $fiscalRegistrar->setUrlGenerator($urlGenerator);
        }

        if (! $fiscalRegistrar instanceof Contracts\DispatchesEvents) {
            $fiscalRegistrar = $this->decorateInstance($fiscalRegistrar);
        }

        $this->setEventDispatcher($fiscalRegistrar);

        return $fiscalRegistrar;
    }

    /**
     * @template T of Contracts\FiscalRegistrar
     *
     * @param  T  $fiscalRegistrar
     * @return Contracts\DispatchesEvents&T
     */
    protected function decorateInstance(Contracts\FiscalRegistrar $fiscalRegistrar): Contracts\FiscalRegistrar
    {
        /** @var Contracts\DispatchesEvents&T */
        return $this->container->make(Support\DriverDispatchingDecorator::class, compact('fiscalRegistrar'));
    }

    /**
     * Set the event dispatcher on the given driver instance.
     *
     * @return $this
     */
    protected function setEventDispatcher(Contracts\DispatchesEvents $instance): static
    {
        if ($this->container->bound(Dispatcher::class)) {
            /** @var Dispatcher $events */
            $events = $this->container->make(Dispatcher::class);
            $instance->setEventDispatcher($events);
        }

        return $this;
    }
}
