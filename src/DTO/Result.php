<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\DTO;

use Carbon\Carbon;
use DateTimeInterface;

final class Result extends DataTransferObject
{
    public string $external_id;

    public string $internal_id;

    public DateTimeInterface $timestamp;

    public string $status;

    public ?string $ofd_receipt_url;

    public ?Result\Payload $payload;

    public ?object $extra;

    /**
     * Result constructor.
     *
     * @param  string  $external_id
     * @param  string  $internal_id
     * @param  DateTimeInterface  $timestamp
     * @param  string  $status
     * @param  string|null  $ofd_receipt_url
     * @param  Result\Payload|null  $payload
     * @param  object|null  $extra
     * @return self
     */
    public static function new(
        string $external_id,
        string $internal_id,
        DateTimeInterface $timestamp,
        string $status,
        string $ofd_receipt_url = null,
        Result\Payload $payload = null,
        object $extra = null
    ): self {
        return new self(
            compact('external_id', 'internal_id', 'timestamp', 'status', 'ofd_receipt_url', 'payload', 'extra')
        );
    }

    protected static function transformTimestamp($timestamp): DateTimeInterface
    {
        return Carbon::parse($timestamp)->settings(['toJsonFormat' => 'c']);
    }

    protected static function transformExtra($extra): ?object
    {
        return isset($extra) ? (object) $extra : null;
    }
}
