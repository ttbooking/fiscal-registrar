<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\Support;

use Illuminate\Contracts\Routing\UrlGenerator;
use TTBooking\FiscalRegistrar\Contracts;

abstract class FiscalRegistrar implements Contracts\FiscalRegistrar
{
    protected UrlGenerator $urlGenerator;

    protected array $config;

    protected string $connection;

    public function __construct(UrlGenerator $urlGenerator, array $config = [], string $connection = 'default')
    {
        $this->urlGenerator = $urlGenerator;
        $this->config = $config;
        $this->connection = $connection;
    }

    protected function getCallbackUrl(): ?string
    {
        return ! is_string($callback = $this->config['callback'] ?? true) && $callback
            ? $this->urlGenerator->route('fiscal-registrar.callback', ['connection' => $this->connection])
            : ($callback ?: null);
    }
}
