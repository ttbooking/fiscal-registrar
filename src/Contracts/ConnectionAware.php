<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\Contracts;

interface ConnectionAware
{
    public function getConnectionName(): string;
}
