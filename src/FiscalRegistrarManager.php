<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar;

use Illuminate\Contracts\Events\Dispatcher;

class FiscalRegistrarManager extends Support\Manager implements Contracts\FiscalRegistrar
{
    protected string $configName = 'fiscal-registrar';

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
        return $this->configureInstance(new AtolFiscalRegistrar($config));
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
