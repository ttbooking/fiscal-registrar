<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar;

use Illuminate\Contracts\Events\Dispatcher;
use TTBooking\FiscalRegistrar\Drivers\Atol\AtolFiscalRegistrar;
use TTBooking\FiscalRegistrar\DTO\Receipt;
use TTBooking\FiscalRegistrar\DTO\Result;

class FiscalRegistrarManager extends Support\Manager implements
    Contracts\FiscalRegistrarFactory,
    Contracts\FiscalRegistrar
{
    protected string $configName = 'fiscal-registrar';

    public function sell(string $externalId, Receipt $receipt): Result
    {
        return $this->connection()->{__FUNCTION__}($externalId, $receipt);
    }

    public function sellRefund(string $externalId, Receipt $receipt): Result
    {
        return $this->connection()->{__FUNCTION__}($externalId, $receipt);
    }

    public function buy(string $externalId, Receipt $receipt): Result
    {
        return $this->connection()->{__FUNCTION__}($externalId, $receipt);
    }

    public function buyRefund(string $externalId, Receipt $receipt): Result
    {
        return $this->connection()->{__FUNCTION__}($externalId, $receipt);
    }

    public function report(string $id): Result
    {
        return $this->connection()->{__FUNCTION__}($id);
    }

    public function processCallback($payload)
    {
        return $this->connection()->{__FUNCTION__}($payload);
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
     * @return AtolFiscalRegistrar
     */
    protected function createAtolDriver(array $config, string $connection): Contracts\FiscalRegistrar
    {
        return $this->configureInstance(
            $this->container->make(AtolFiscalRegistrar::class, compact('config', 'connection'))
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
        return new Support\FiscalRegistrarDispatchingDecorator($fiscalRegistrar);
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
