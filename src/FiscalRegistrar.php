<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar;

abstract class FiscalRegistrar implements Contracts\FiscalRegistrar, Contracts\DispatchesEvents
{
    use Concerns\HasEvents;

    /**
     * @var array
     */
    protected array $config;

    public function __construct(array $config = [])
    {
        $this->config = $config;
    }
}
