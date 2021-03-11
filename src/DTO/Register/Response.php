<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\DTO\Register;

use DateTimeInterface;

final class Response
{
    public ?string $uuid;

    public DateTimeInterface $timestamp;

    public string $status;

    public ?Response\Error $error;

    /**
     * Response constructor.
     *
     * @param  string|null  $uuid
     * @param  DateTimeInterface|null  $timestamp
     * @param  string  $status
     * @param  Response\Error|null  $error
     */
    public function __construct(
        string $uuid = null,
        DateTimeInterface $timestamp = null,
        string $status = 'wait',
        Response\Error $error = null
    ) {
        $this->uuid = $uuid;
        $this->timestamp = $timestamp ?? date_create();
        $this->status = $status;
        $this->error = $error;
    }
}
