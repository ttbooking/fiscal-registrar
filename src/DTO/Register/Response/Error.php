<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\DTO\Register\Response;

final class Error
{
    public int $code;

    public string $errorId;

    public string $text;

    public string $type;

    /**
     * Error constructor.
     *
     * @param  int  $code
     * @param  string  $errorId
     * @param  string  $text
     * @param  string  $type
     */
    public function __construct(int $code, string $errorId, string $text = '', string $type = 'unknown')
    {
        $this->code = $code;
        $this->errorId = $errorId;
        $this->text = $text;
        $this->type = $type;
    }
}
