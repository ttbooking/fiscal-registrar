<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\Contracts;

use Illuminate\Contracts\Events\Dispatcher;

interface DispatchesEvents
{
    /**
     * Get the event dispatcher instance.
     *
     * @return Dispatcher|null
     */
    public function getEventDispatcher(): ?Dispatcher;

    /**
     * Set the event dispatcher instance.
     *
     * @param  Dispatcher|null  $events
     * @return $this
     */
    public function setEventDispatcher(?Dispatcher $events): self;
}
