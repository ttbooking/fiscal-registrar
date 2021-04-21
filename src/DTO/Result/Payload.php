<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\DTO\Result;

use DateTimeInterface;
use TTBooking\FiscalRegistrar\DTO\DataTransferObject;

final class Payload extends DataTransferObject
{
    // 1042
    public int $fiscal_receipt_number;

    // 1038
    public int $shift_number;

    // 1012
    public DateTimeInterface $receipt_datetime;

    // 1020
    public float|int $total;

    // 1041
    public string $fn_number;

    // 1037
    public string $ecr_registration_number;

    // 1040
    public int $fiscal_document_number;

    // 1077
    public int $fiscal_document_attribute;

    // 1060
    public string $fns_site = 'www.nalog.ru';
}
