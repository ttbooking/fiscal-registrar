<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\Contracts;

use InvalidArgumentException;

/**
 * @template TConnection of object
 */
interface Factory
{
    /**
     * Get a connection instance.
     *
     * @return TConnection
     *
     * @throws InvalidArgumentException
     */
    public function connection(?string $name = null): object;

    /**
     * Get all of the created connections.
     *
     * @return array<string, TConnection>
     */
    public function getConnections(): array;
}
