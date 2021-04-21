<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\DTO;

use DateTimeInterface;
use Spatie\DataTransferObject\Attributes\CastWith;
use TTBooking\FiscalRegistrar\DTO\Casters\ResultExtraCaster;

final class Result extends DataTransferObject
{
    public string $internal_id;

    public DateTimeInterface $timestamp;

    public string $status;

    public ?string $ofd_receipt_url = null;

    public ?Result\Payload $payload = null;

    #[CastWith(ResultExtraCaster::class)]
    public ?object $extra = null;
}
