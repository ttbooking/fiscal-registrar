<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\DTO\Receipt\AgentInfo;

final class ReceivePaymentsOperator
{
    // 1074
    /** @var string[]|null */
    public ?array $phones;

    /**
     * ReceivePaymentsOperator constructor.
     *
     * @param  string[]|null  $phones
     */
    public function __construct(array $phones = null)
    {
        $this->phones = $phones;
    }
}
