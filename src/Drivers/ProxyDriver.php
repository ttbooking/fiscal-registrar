<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\Drivers;

use Closure;
use Illuminate\Contracts\Routing\UrlGenerator;
use TTBooking\FiscalRegistrar\Contracts\SupportsCallbacks;
use TTBooking\FiscalRegistrar\DTO\Receipt;
use TTBooking\FiscalRegistrar\DTO\Result;
use TTBooking\FiscalRegistrar\Enums\Operation;
use TTBooking\FiscalRegistrar\Support\Driver;

class ProxyDriver extends Driver implements SupportsCallbacks
{
    public function __construct(UrlGenerator $urlGenerator, array $config = [], string $connection = 'default')
    {
        parent::__construct($urlGenerator, $config, $connection);
    }

    public function register(Operation $operation, string $externalId, Receipt $payload): string
    {
        // TODO: Implement register() method.
    }

    public function report(string $id): ?Result
    {
        // TODO: Implement report() method.
    }

    public function processCallback(mixed $payload, Closure $handler = null): void
    {
        // TODO: Implement processCallback() method.
    }
}
