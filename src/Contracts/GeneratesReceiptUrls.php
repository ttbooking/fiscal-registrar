<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\Contracts;

interface GeneratesReceiptUrls
{
    /**
     * Get the event dispatcher instance.
     *
     * @return ReceiptUrlGenerator|null
     */
    public function getUrlGenerator(): ?ReceiptUrlGenerator;

    /**
     * Set the event dispatcher instance.
     *
     * @param  ReceiptUrlGenerator|null  $urlGenerator
     * @return $this
     */
    public function setUrlGenerator(?ReceiptUrlGenerator $urlGenerator): self;
}
