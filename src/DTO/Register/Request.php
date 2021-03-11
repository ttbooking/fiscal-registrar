<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\DTO\Register;

use DateTimeInterface;

final class Request
{
    public DateTimeInterface $timestamp;

    public string $externalId;

    public ?Request\Service $service;

    public Request\Receipt $receipt;

    /**
     * Request constructor.
     *
     * @param  string  $externalId
     * @param  Request\Receipt  $receipt
     * @param  Request\Service|null  $service
     * @param  DateTimeInterface|null  $timestamp
     */
    public function __construct(
        string $externalId,
        Request\Receipt $receipt,
        Request\Service $service = null,
        DateTimeInterface $timestamp = null
    ) {
        $this->timestamp = $timestamp ?? date_create();
        $this->externalId = $externalId;
        $this->service = $service;
        $this->receipt = $receipt;
    }
}
