<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\DTO\Report;

use DateTimeInterface;

final class Response
{
    public ?string $uuid;

    public DateTimeInterface $timestamp;

    public string $status;

    public ?Response\Error $error;
}
