<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\Contracts;

use TTBooking\FiscalRegistrar\Exceptions\ResolverException;

interface SelfResolvable
{
    /**
     * @param  mixed  $id
     * @return static
     *
     * @throws ResolverException
     */
    public function resolve($id): self;
}
