<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\QrCode\Writer\Result;

use Endroid\QrCode\Matrix\MatrixInterface;
use Endroid\QrCode\Writer\Result\AbstractResult;

final class BlockResult extends AbstractResult
{
    /**
     * @param  string[]|string  $charset
     */
    public function __construct(
        protected MatrixInterface $matrix,
        protected array|string $charset
    ) {
        if (is_string($this->charset)) {
            $this->charset = preg_split('//u', $this->charset, -1, PREG_SPLIT_NO_EMPTY) ?: [];
        }
    }

    public function getString(): string
    {
        $binaryString = '';
        for ($rowIndex = 0; $rowIndex < $this->matrix->getBlockCount(); $rowIndex += 2) {
            for ($columnIndex = 0; $columnIndex < $this->matrix->getBlockCount(); $columnIndex++) {
                $binaryString .= $this->charset[bindec(
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
