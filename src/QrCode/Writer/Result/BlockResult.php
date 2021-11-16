<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\QrCode\Writer\Result;

use Endroid\QrCode\Matrix\MatrixInterface;
use Endroid\QrCode\Writer\Result\AbstractResult;

final class BlockResult extends AbstractResult
{
    public function __construct(
        protected MatrixInterface $matrix,
        protected string $charset
    ) {
    }

    public function getString(): string
    {
        $binaryString = '';
        for ($rowIndex = 0; $rowIndex < $this->matrix->getBlockCount(); $rowIndex += 2) {
            for ($columnIndex = 0; $columnIndex < $this->matrix->getBlockCount(); $columnIndex += 2) {
                $binaryString .= $this->charset[bindec(
                    $this->getBlockValue($rowIndex + 1, $columnIndex + 1).
                    $this->getBlockValue($rowIndex, $columnIndex + 1).
                    $this->getBlockValue($rowIndex + 1, $columnIndex).
                    $this->getBlockValue($rowIndex, $columnIndex)
                )];
            }
            $binaryString .= "\n";
        }

        return $binaryString;
    }

    public function getMimeType(): string
    {
        return 'text/plain';
    }

    protected function getBlockValue(int $rowIndex, int $columnIndex): int
    {
        $blockCount = $this->matrix->getBlockCount();

        if ($rowIndex >= $blockCount || $columnIndex >= $blockCount) {
            return 0;
        }

        return $this->matrix->getBlockValue($rowIndex, $columnIndex);
    }
}
