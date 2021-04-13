<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\Events;

use TTBooking\FiscalRegistrar\DTO\Result;

class Processed extends ReceiptEvent
{
    public string $reg_conn;

    public string $internal_id;

    public Result $result;

    public function __construct(string $reg_conn, string $internal_id, Result $result)
    {
        $this->reg_conn = $reg_conn;
        $this->internal_id = $internal_id;
        $this->result = $result;
    }
}
