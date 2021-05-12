<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\Concerns;

use Closure;

trait ResolvesConnections
{
    /** @var Closure():string */
    protected Closure $connectionResolver;

    /**
     * @return Closure():string
     */
    public function connectionResolver(): Closure
    {
        return $this->connectionResolver;
    }

    /**
     * @param  null|callable():string  $connectionResolver
     * @return $this
     */
    public function resolveConnectionsUsing(?callable $connectionResolver): static
    {
        if (isset($connectionResolver)) {
            $this->connectionResolver = Closure::fromCallable($connectionResolver);
        }

        return $this;
    }

    /**
     * @return string
     */
    protected function resolveConnection(): string
    {
        return call_user_func($this->connectionResolver());
    }
}
