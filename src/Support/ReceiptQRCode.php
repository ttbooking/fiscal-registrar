<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\Support;

use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Writer\Result\ResultInterface;
use TTBooking\FiscalRegistrar\DTO\Result\Payload;
use TTBooking\FiscalRegistrar\Enums\Operation;
use TTBooking\FiscalRegistrar\QrCode\Writer\BlockWriter;

class ReceiptQRCode
{
    protected string $data;

    private function __construct(Payload $payload, Operation $operation)
    {
        $this->data = sprintf('t=%s&s=%.2f&fn=%s&i=%d&fp=%d&n=%d',
            $payload->receipt_datetime->format('Ymd\THi'),
            $payload->total,
            $payload->fn_number,
            $payload->fiscal_document_number,
            $payload->fiscal_document_attribute,
            match ($operation) {
                Operation::Sell => 1,
                Operation::SellRefund => 2,
                Operation::Buy => 3,
                Operation::BuyRefund => 4,
            }
        );
    }

    final public static function for(Payload $payload, Operation $operation): self
    {
        return new self($payload, $operation);
    }

    public function block(): ResultInterface
    {
        return (new BlockWriter)->write(new QrCode($this->data));
    }

    public function png(): ResultInterface
    {
        $qrCode = new QrCode(
            data: $this->data,
            encoding: new Encoding('UTF-8'),
            errorCorrectionLevel: ErrorCorrectionLevel::High,
            size: 100,
            margin: 10,
            roundBlockSizeMode: RoundBlockSizeMode::Margin,
            foregroundColor: new Color(0, 0, 0),
            backgroundColor: new Color(255, 255, 255, 127),
        );

        return (new PngWriter)->write($qrCode);
    }
}
