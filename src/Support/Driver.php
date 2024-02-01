<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\Support;

use Illuminate\Contracts\Routing\UrlGenerator;
use TTBooking\FiscalRegistrar\Concerns\UtilizesReceiptUrlGenerator;
use TTBooking\FiscalRegistrar\Contracts;

abstract class Driver implements Contracts\ConnectionAware, Contracts\FiscalRegistrar, Contracts\GeneratesReceiptUrls
{
    use UtilizesReceiptUrlGenerator;

    protected UrlGenerator $urlGenerator;

    /** @var array<string, mixed> */
    protected array $config;

    protected string $connection;

    /**
     * @param  array<string, mixed>  $config
     */
    public function __construct(UrlGenerator $urlGenerator, array $config = [], string $connection = 'default')
    {
        $this->urlGenerator = $urlGenerator;
        $this->config = $config;
        $this->connection = $connection;
    }

    public function getConnectionName(): string
    {
        return $this->connection;
    }

    protected function getCallbackUrl(): ?string
    {
        return ! is_string($callback = $this->config['callback'] ?? true) && $callback
            ? $this->urlGenerator->route('fiscal-registrar.callback', ['connection' => $this->connection])
            : ($callback ?: null);
    }
}
