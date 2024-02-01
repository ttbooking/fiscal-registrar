<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\Concerns;

use TTBooking\FiscalRegistrar\Contracts\GeneratesReceiptUrls;
use TTBooking\FiscalRegistrar\Contracts\ReceiptUrlGenerator;
use TTBooking\FiscalRegistrar\DTO\Result;

trait UtilizesReceiptUrlGenerator
{
    /**
     * The receipt URL generator implementation.
     */
    protected ?ReceiptUrlGenerator $receiptUrlGenerator;

    /**
     * Get the receipt URL generator instance.
     */
    public function getUrlGenerator(): ?ReceiptUrlGenerator
    {
        return $this->receiptUrlGenerator;
    }

    /**
     * Set the receipt URL generator instance.
     *
     * @return $this
     */
    public function setUrlGenerator(?ReceiptUrlGenerator $receiptUrlGenerator): GeneratesReceiptUrls
    {
        $this->receiptUrlGenerator = $receiptUrlGenerator;

        return $this;
    }

    /**
     * Retrieve receipt URL or make it if URL generator instance is set.
     */
    protected function getReceiptUrl(Result $result): ?string
    {
        return $result->payload->ofd_receipt_url
            ?? (isset($this->receiptUrlGenerator) ? $this->receiptUrlGenerator->fromResult($result) : null);
    }
}
