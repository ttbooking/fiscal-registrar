<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\Support;

use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\RoundBlockSizeMode\RoundBlockSizeModeMargin;
use Endroid\QrCode\Writer\PngWriter;
use TTBooking\FiscalRegistrar\Contracts\QRCodeBuilder;
use TTBooking\FiscalRegistrar\DTO\Result\Payload;
use TTBooking\FiscalRegistrar\Enums\Operation;

class QRCodePng implements QRCodeBuilder
{
    public function __construct(
        protected QRCodeBuilder $dataProvider,
    ) {
    }

    public function make(Payload $payload, Operation $operation): string
    {
        $data = $this->dataProvider->make($payload, $operation);

        $qrCode = QrCode::create($data)
            ->setEncoding(new Encoding('UTF-8'))
            ->setErrorCorrectionLevel(new ErrorCorrectionLevelHigh)
            ->setSize(100)
            ->setMargin(10)
            ->setRoundBlockSizeMode(new RoundBlockSizeModeMargin)
            ->setForegroundColor(new Color(0, 0, 0))
            ->setBackgroundColor(new Color(255, 255, 255));

        return (new PngWriter)->write($qrCode)->getDataUri();
    }
}
