<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\DTO;

use DateTimeInterface;

final class Result extends DataTransferObject
{
    public string $externalId;

    public string $internalId;

    public DateTimeInterface $timestamp;

    public string $status;

    public ?Result\Payload $payload;

    public ?object $extra;

    /**
     * Result constructor.
     *
     * @param  string  $externalId
     * @param  string  $internalId
     * @param  DateTimeInterface  $timestamp
     * @param  string  $status
     * @param  Result\Payload|null  $payload
     * @param  object|null  $extra
     * @return self
     */
    public static function new(
        string $externalId,
        string $internalId,
        DateTimeInterface $timestamp,
        string $status,
        Result\Payload $payload = null,
        object $extra = null
    ): self {
        return new self(compact('externalId', 'internalId', 'timestamp', 'status', 'payload', 'extra'));
    }
}
