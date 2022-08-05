<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\Support;

use Illuminate\Support\Traits\ForwardsCalls;

/**
 * @template TDecorated of object
 */
class Decorator
{
    use ForwardsCalls;

    /** @var TDecorated */
    protected object $instance;

    /**
     * Decorator constructor.
     *
     * @param  TDecorated  $instance
     * @return void
     */
    public function __construct(object $instance)
    {
        $this->setDecoratedInstance($instance);
    }

    /**
     * @template TObject of object
     * @param  TObject  $a
     * @param  class-string<TObject>|TObject  $b
     * @return bool
     */
    final public static function instanceOf(object $a, string|object $b): bool
    {
        return $a instanceof $b
            || $a instanceof self
            && self::instanceOf($a->instance, $b);
    }

    /**
     * @return TDecorated
     */
    public function getDecoratedInstance(): object
    {
        return $this->instance;
    }

    /**
     * @param  TDecorated  $instance
     * @return $this
     */
    public function setDecoratedInstance(object $instance): static
    {
        $this->instance = $instance;

        return $this;
    }

    /**
     * @param  string  $method
     * @param  array<mixed>  $parameters
     * @return mixed
     */
    public function __call(string $method, array $parameters)
    {
        return $this->forwardCallTo($this->instance, $method, $parameters);
    }
}
