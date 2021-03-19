<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\DTO\Receipt\AgentInfo;

use TTBooking\FiscalRegistrar\DTO\DataTransferObject;

final class ReceivePaymentsOperator extends DataTransferObject
{
    // 1074
    /** @var string[]|null */
    public ?array $phones;

    /**
     * ReceivePaymentsOperator constructor.
     *
     * @param  string[]|null  $phones
     * @return self
     */
    public static function new(array $phones = null): self
    {
        return new self(compact('phones'));
    }
}
