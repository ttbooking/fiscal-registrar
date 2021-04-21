<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\Support;

use Illuminate\Support\Traits\ForwardsCalls;

class Decorator
{
    use ForwardsCalls;

    protected object $instance;

    /**
     * Decorator constructor.
     *
     * @param  object  $instance
     * @return void
     */
    public function __construct(object $instance)
    {
        $this->setDecoratedInstance($instance);
    }

    /**
     * @param  object  $a
     * @param  mixed  $b
     * @return bool
     */
    final public static function instanceOf(object $a, $b): bool
    {
        return $a instanceof $b
            || $a instanceof self
            && self::instanceOf($a->instance, $b);
    }

    /**
     * @return object
     */
    public function getDecoratedInstance(): object
    {
        return $this->instance;
    }

    /**
     * @param  object  $instance
     * @return $this
     */
    public function setDecoratedInstance(object $instance): static
    {
        $this->instance = $instance;

        return $this;
    }

    /**
     * @param  string  $method
     * @param  array  $parameters
     * @return mixed
     */
    public function __call(string $method, array $parameters)
    {
        return $this->forwardCallTo($this->instance, $method, $parameters);
    }
}
