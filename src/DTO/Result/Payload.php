<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\DTO\Result;

use DateTimeInterface;
use TTBooking\FiscalRegistrar\DTO\DataTransferObject;

final class Payload extends DataTransferObject
{
    // 1042
    public int $fiscalReceiptNumber;

    // 1038
    public int $shiftNumber;

    // 1012
    public DateTimeInterface $receiptDateTime;

    // 1020
    /** @var float|int */
    public float $total;

    // 1041
    public string $fnNumber;

    // 1037
    public string $ecrRegistrationNumber;

    // 1040
    public int $fiscalDocumentNumber;

    // 1077
    public int $fiscalDocumentAttribute;

    // 1060
    public string $fnsSite;

    /**
     * Payload constructor.
     *
     * @param  int  $fiscalReceiptNumber
     * @param  int  $shiftNumber
     * @param  DateTimeInterface  $receiptDateTime
     * @param  float|int  $total
     * @param  string  $fnNumber
     * @param  string  $ecrRegistrationNumber
     * @param  int  $fiscalDocumentNumber
     * @param  int  $fiscalDocumentAttribute
     * @param  string  $fnsSite
     * @return self
     */
    public static function new(
        int $fiscalReceiptNumber,
        int $shiftNumber,
        DateTimeInterface $receiptDateTime,
        float $total,
        string $fnNumber,
        string $ecrRegistrationNumber,
        int $fiscalDocumentNumber,
        int $fiscalDocumentAttribute,
        string $fnsSite = 'www.nalog.ru'
    ): self {
        return new self(compact(
            'fiscalReceiptNumber', 'shiftNumber', 'receiptDateTime', 'total', 'fnNumber',
            'ecrRegistrationNumber', 'fiscalDocumentNumber', 'fiscalDocumentAttribute', 'fnsSite'
        ));
    }
}
