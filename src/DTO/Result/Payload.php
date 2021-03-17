<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\DTO\Result;

use DateTimeInterface;

final class Payload
{
    // 1042
    public int $fiscalReceiptNumber;

    // 1038
    public int $shiftNumber;

    // 1012
    public DateTimeInterface $receiptDateTime;

    // 1020
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
     * @param  float  $total
     * @param  string  $fnNumber
     * @param  string  $ecrRegistrationNumber
     * @param  int  $fiscalDocumentNumber
     * @param  int  $fiscalDocumentAttribute
     * @param  string  $fnsSite
     */
    public function __construct(
        int $fiscalReceiptNumber,
        int $shiftNumber,
        DateTimeInterface $receiptDateTime,
        float $total,
        string $fnNumber,
        string $ecrRegistrationNumber,
        int $fiscalDocumentNumber,
        int $fiscalDocumentAttribute,
        string $fnsSite = 'www.nalog.ru'
    ) {
        $this->fiscalReceiptNumber = $fiscalReceiptNumber;
        $this->shiftNumber = $shiftNumber;
        $this->receiptDateTime = $receiptDateTime;
        $this->total = $total;
        $this->fnNumber = $fnNumber;
        $this->ecrRegistrationNumber = $ecrRegistrationNumber;
        $this->fiscalDocumentNumber = $fiscalDocumentNumber;
        $this->fiscalDocumentAttribute = $fiscalDocumentAttribute;
        $this->fnsSite = $fnsSite;
    }
}
