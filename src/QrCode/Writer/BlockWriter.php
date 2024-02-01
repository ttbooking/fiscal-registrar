<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\QrCode\Writer;

use Endroid\QrCode\Bacon\MatrixFactory;
use Endroid\QrCode\Label\LabelInterface;
use Endroid\QrCode\Logo\LogoInterface;
use Endroid\QrCode\QrCodeInterface;
use Endroid\QrCode\Writer\Result\ResultInterface;
use Endroid\QrCode\Writer\WriterInterface;
use TTBooking\FiscalRegistrar\QrCode\Writer\Result\BlockResult;

final class BlockWriter implements WriterInterface
{
    public const WRITER_OPTION_CHARSET = 'charset';

    public const BLOCK_CHARSET_DEFAULT = ' ▀▄█';

    public const BLOCK_CHARSET_INVERTED = '█▄▀ ';

    /**
     * @param  array{charset?: ?string}  $options
     */
    public function write(
        QrCodeInterface $qrCode,
        ?LogoInterface $logo = null,
        ?LabelInterface $label = null,
        array $options = []
    ): ResultInterface {
        $matrixFactory = new MatrixFactory;
        $matrix = $matrixFactory->create($qrCode);

        return new BlockResult($matrix, $options[self::WRITER_OPTION_CHARSET] ?? self::BLOCK_CHARSET_DEFAULT);
    }
}
