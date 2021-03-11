<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar;

use Illuminate\Contracts\Events\Dispatcher;
use TTBooking\FiscalRegistrar\DTO\Register\Request;
use TTBooking\FiscalRegistrar\DTO\Register\Response;

/**
 * @method Contracts\FiscalRegistrar connection(string $name = null)
 */
class FiscalRegistrarManager extends Support\Manager implements Contracts\FiscalRegistrar
{
    protected string $configName = 'fiscal-registrar';

    public function sell(string $externalId, Request\Receipt $receipt): Response
    {
        return $this->connection()->{__FUNCTION__}($externalId, $receipt);
    }

    public function sellRefund(string $externalId, Request\Receipt $receipt): Response
    {
        return $this->connection()->{__FUNCTION__}($externalId, $receipt);
    }

    public function buy(string $externalId, Request\Receipt $receipt): Response
    {
        return $this->connection()->{__FUNCTION__}($externalId, $receipt);
    }

    public function buyRefund(string $externalId, Request\Receipt $receipt): Response
    {
        return $this->connection()->{__FUNCTION__}($externalId, $receipt);
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
     * @return AtolFiscalRegistrar
     */
    protected function createAtolDriver(array $config): Contracts\FiscalRegistrar
    {
        return $this->configureInstance(
            $this->container->make(AtolFiscalRegistrar::class, compact('config'))
        );
    }

    /**
     * @param  Contracts\FiscalRegistrar  $fiscalRegistrar
     * @return Contracts\FiscalRegistrar
     */
    protected function configureInstance(Contracts\FiscalRegistrar $fiscalRegistrar): Contracts\FiscalRegistrar
    {
        if ($fiscalRegistrar instanceof Contracts\DispatchesEvents) {
            $this->setEventDispatcher($fiscalRegistrar);
        }

        return $fiscalRegistrar;
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
