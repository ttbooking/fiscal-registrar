<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\Contracts;

use InvalidArgumentException;

interface Factory
{
    /**
     * Get a connection instance.
     *
     * @param  string|null  $name
     * @return object
     *
     * @throws InvalidArgumentException
     */
    public function connection(string $name = null): object;

    /**
     * Get all of the created connections.
     *
     * @return array<string, object>
     */
    public function getConnections(): array;
}
