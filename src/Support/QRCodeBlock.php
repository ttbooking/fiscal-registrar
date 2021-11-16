<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\Support;

use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelLow;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\RoundBlockSizeMode\RoundBlockSizeModeMargin;
use Endroid\QrCode\Writer\Result\ResultInterface;
use TTBooking\FiscalRegistrar\DTO\Result\Payload;
use TTBooking\FiscalRegistrar\Enums\Operation;
use TTBooking\FiscalRegistrar\QrCode\Writer\BlockWriter;

class QRCodeBlock extends QRCodeAbstract
{
    public function make(Payload $payload, Operation $operation): ResultInterface
    {
        $qrCode = QrCode::create($this->getData($payload, $operation))
            ->setEncoding(new Encoding('UTF-8'))
            ->setErrorCorrectionLevel(new ErrorCorrectionLevelLow)
            ->setSize(100)
            ->setMargin(10)
            ->setRoundBlockSizeMode(new RoundBlockSizeModeMargin)
            ->setForegroundColor(new Color(0, 0, 0))
            ->setBackgroundColor(new Color(255, 255, 255));

        return (new BlockWriter)->write($qrCode);
    }
}
