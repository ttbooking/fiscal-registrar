<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\DTO;

use DateTimeInterface;
use TTBooking\FiscalRegistrar\DTO\Result\Payload;

final class Result
{
    public string $externalId;

    public string $internalId;

    public DateTimeInterface $timestamp;

    public string $status;

    public ?Payload $payload;

    public ?object $extra;

    /**
     * Result constructor.
     *
     * @param  string  $externalId
     * @param  string  $internalId
     * @param  DateTimeInterface  $timestamp
     * @param  string  $status
     * @param  Payload|null  $payload
     * @param  object|null  $extra
     */
    public function __construct(
        string $externalId,
        string $internalId,
        DateTimeInterface $timestamp,
        string $status,
        Payload $payload = null,
        object $extra = null
    ) {
        $this->externalId = $externalId;
        $this->internalId = $internalId;
        $this->timestamp = $timestamp;
        $this->status = $status;
        $this->payload = $payload;
        $this->extra = $extra;
    }
}
