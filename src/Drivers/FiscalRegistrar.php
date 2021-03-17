<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\Drivers;

use Illuminate\Contracts\Routing\UrlGenerator;
use TTBooking\FiscalRegistrar\Concerns;
use TTBooking\FiscalRegistrar\Contracts;

abstract class FiscalRegistrar implements Contracts\FiscalRegistrar, Contracts\DispatchesEvents
{
    use Concerns\HasEvents;

    protected UrlGenerator $urlGenerator;

    protected array $config;

    protected string $connection;

    public function __construct(UrlGenerator $urlGenerator, array $config = [], string $connection = 'default')
    {
        $this->urlGenerator = $urlGenerator;
        $this->config = $config;
        $this->connection = $connection;
    }

    public function getCallbackUrl(): ?string
    {
        return ! is_string($callback = $this->config['callback'] ?? true) && $callback
            ? $this->urlGenerator->route('fiscal-registrar.callback', ['connection' => $this->connection])
            : ($callback ?: null);
    }
}
