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
    /** @var float|int */
    public float $total;

    // 1041
    public string $fn_number;

    // 1037
    public string $ecr_registration_number;

    // 1040
    public int $fiscal_document_number;

    // 1077
    public int $fiscal_document_attribute;

    // 1060
    public string $fns_site;

    /**
     * Payload constructor.
     *
     * @param  int  $fiscal_receipt_number
     * @param  int  $shift_number
     * @param  DateTimeInterface  $receipt_datetime
     * @param  float|int  $total
     * @param  string  $fn_number
     * @param  string  $ecr_registration_number
     * @param  int  $fiscal_document_number
     * @param  int  $fiscal_document_attribute
     * @param  string  $fns_site
     * @return self
     */
    public static function new(
        int $fiscal_receipt_number,
        int $shift_number,
        DateTimeInterface $receipt_datetime,
        float $total,
        string $fn_number,
        string $ecr_registration_number,
        int $fiscal_document_number,
        int $fiscal_document_attribute,
        string $fns_site = 'www.nalog.ru'
    ): self {
        return new self(compact(
            'fiscal_receipt_number', 'shift_number', 'receipt_datetime', 'total', 'fn_number',
            'ecr_registration_number', 'fiscal_document_number', 'fiscal_document_attribute', 'fns_site'
        ));
    }
}
