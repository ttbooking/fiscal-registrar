<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\DTO;

use DateTimeInterface;
use Spatie\LaravelData\Attributes\WithCast;
use TTBooking\FiscalRegistrar\DTO\Casters\ResultExtraCaster;
use TTBooking\FiscalRegistrar\DTO\Casters\TimestampCaster;

final class Result extends DataTransferObject
{
    public function __construct(
        public string $internal_id,

        #[WithCast(TimestampCaster::class)]
        public DateTimeInterface $timestamp,

        public string $status,

        public ?Result\Payload $payload = null,

        #[WithCast(ResultExtraCaster::class)]
        public ?object $extra = null,
    ) {}
}
