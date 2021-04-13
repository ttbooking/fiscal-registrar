<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\Events;

use TTBooking\FiscalRegistrar\DTO\Receipt;
use TTBooking\FiscalRegistrar\Enums\Operation;

class Registered extends RegistrationEvent
{
    public string $internal_id;

    public function __construct(
        string $reg_conn,
        Operation $operation,
        string $external_id,
        string $internal_id,
        Receipt $data
    ) {
        parent::__construct($reg_conn, $operation, $external_id, $data);

        $this->internal_id = $internal_id;
    }
}
