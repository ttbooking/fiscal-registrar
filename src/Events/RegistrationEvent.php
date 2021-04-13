<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\Events;

use TTBooking\FiscalRegistrar\DTO\Receipt;
use TTBooking\FiscalRegistrar\Enums\Operation;

abstract class RegistrationEvent extends ReceiptEvent
{
    public string $reg_conn;

    public Operation $operation;

    public string $external_id;

    public Receipt $data;

    public function __construct(string $reg_conn, Operation $operation, string $external_id, Receipt $data)
    {
        $this->reg_conn = $reg_conn;
        $this->operation = $operation;
        $this->external_id = $external_id;
        $this->data = $data;
    }
}
