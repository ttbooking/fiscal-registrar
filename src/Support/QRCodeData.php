<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\Support;

use TTBooking\FiscalRegistrar\Contracts\QRCodeBuilder;
use TTBooking\FiscalRegistrar\DTO\Result\Payload;
use TTBooking\FiscalRegistrar\Enums\Operation;

class QRCodeData implements QRCodeBuilder
{
    public function make(Payload $payload, Operation $operation): string
    {
        return sprintf('t=%s&s=%.2f&fn=%s&i=%d&fp=%d&n=%d',
            $payload->receipt_datetime->format('Ymd\THi'),
            $payload->total,
            $payload->fn_number,
            $payload->fiscal_document_number,
            $payload->fiscal_document_attribute,
            match ($operation?->getValue()) {
                'sell' => 1,
                'sell_refund' => 2,
                'buy' => 3,
                'buy_refund' => 4,
            }
        );
    }
}
