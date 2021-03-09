<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\Concerns;

use Illuminate\Contracts\Events\Dispatcher;
use TTBooking\FiscalRegistrar\Contracts\DispatchesEvents;

trait HasEvents
{
    /**
     * The event dispatcher implementation.
     *
     * @var Dispatcher|null
     */
    protected ?Dispatcher $events;

    /**
     * Get the event dispatcher instance.
     *
     * @return Dispatcher|null
     */
    public function getEventDispatcher(): ?Dispatcher
    {
        return $this->events;
    }

    /**
     * Set the event dispatcher instance.
     *
     * @param  Dispatcher|null  $events
     * @return $this
     */
    public function setEventDispatcher(?Dispatcher $events): DispatchesEvents
    {
        $this->events = $events;

        return $this;
    }

    /**
     * Fire an event if dispatcher instance is set.
     *
     * @param  string|object  $event
     * @param  mixed  $payload
     * @param  bool  $halt
     * @return array|null
     */
    protected function event($event, $payload = [], bool $halt = false) : ?array
    {
        return isset($this->events) ? $this->events->dispatch($event, $payload, $halt) : null;
    }
}
