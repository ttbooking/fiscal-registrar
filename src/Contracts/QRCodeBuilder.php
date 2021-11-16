<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\Contracts;

use Endroid\QrCode\Writer\Result\ResultInterface;
use TTBooking\FiscalRegistrar\DTO\Result\Payload;
use TTBooking\FiscalRegistrar\Enums\Operation;

interface QRCodeBuilder
{
    public function make(Payload $payload, Operation $operation): ResultInterface;
}
