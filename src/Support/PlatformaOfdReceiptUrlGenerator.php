<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\Support;

use TTBooking\FiscalRegistrar\DTO\Result;
use TTBooking\FiscalRegistrar\Contracts\ReceiptUrlGenerator;

class PlatformaOfdReceiptUrlGenerator implements ReceiptUrlGenerator
{
    public function fromResult(Result $result): ?string
    {
        if (! isset($result->payload)) {
            return null;
        }

        return sprintf(
            "https://lk.platformaofd.ru/web/noauth/cheque?fn=%s&fp=%d&i=%d",
            $result->payload->fn_number,
            $result->payload->fiscal_document_attribute,
            $result->payload->fiscal_document_number
        );
    }
}
