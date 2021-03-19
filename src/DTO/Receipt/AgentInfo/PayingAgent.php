<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\DTO\Receipt\AgentInfo;

use TTBooking\FiscalRegistrar\DTO\DataTransferObject;

final class PayingAgent extends DataTransferObject
{
    // 1044
    public ?string $operation;

    // 1073
    /** @var string[]|null */
    public ?array $phones;

    /**
     * PayingAgent constructor.
     *
     * @param  string|null  $operation
     * @param  string[]|null  $phones
     * @return self
     */
    public static function new(string $operation = null, array $phones = null): self
    {
        return new self(compact('operation', 'phones'));
    }
}
