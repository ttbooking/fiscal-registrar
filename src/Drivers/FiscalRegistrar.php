<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\Drivers;

use TTBooking\FiscalRegistrar\Concerns;
use TTBooking\FiscalRegistrar\Contracts;

abstract class FiscalRegistrar implements Contracts\FiscalRegistrar, Contracts\DispatchesEvents
{
    use Concerns\HasEvents;

    /**
     * @var array
     */
    protected array $config;

    /**
     * @var string
     */
    protected string $connection;

    public function __construct(array $config = [], string $connection = 'default')
    {
        $this->config = $config;
        $this->connection = $connection;
    }
}
