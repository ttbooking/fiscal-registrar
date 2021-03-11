<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\DTO\Register\Request;

final class Service
{
    public string $callbackUrl;

    /**
     * Service constructor.
     *
     * @param  string  $callbackUrl
     */
    public function __construct(string $callbackUrl)
    {
        $this->callbackUrl = $callbackUrl;
    }
}
