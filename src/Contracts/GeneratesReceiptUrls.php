<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\Contracts;

interface GeneratesReceiptUrls
{
    /**
     * Get the event dispatcher instance.
     */
    public function getUrlGenerator(): ?ReceiptUrlGenerator;

    /**
     * Set the event dispatcher instance.
     *
     * @return $this
     */
    public function setUrlGenerator(?ReceiptUrlGenerator $urlGenerator): self;
}
