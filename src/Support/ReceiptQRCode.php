<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\Support;

use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\RoundBlockSizeMode\RoundBlockSizeModeMargin;
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
            match ($operation->getValue()) {
                'sell' => 1,
                'sell_refund' => 2,
                'buy' => 3,
                'buy_refund' => 4,
            }
        );
    }

    final public static function for(Payload $payload, Operation $operation): self
    {
        return new self($payload, $operation);
    }

    public function block(): ResultInterface
    {
        return (new BlockWriter)->write(QrCode::create($this->data));
    }

    public function png(): ResultInterface
    {
        $qrCode = QrCode::create($this->data)
            ->setEncoding(new Encoding('UTF-8'))
            ->setErrorCorrectionLevel(new ErrorCorrectionLevelHigh)
            ->setSize(100)
            ->setMargin(10)
            ->setRoundBlockSizeMode(new RoundBlockSizeModeMargin)
            ->setForegroundColor(new Color(0, 0, 0))
            ->setBackgroundColor(new Color(255, 255, 255));

        return (new PngWriter)->write($qrCode);
    }
}
