<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\DTO\Receipt\AgentInfo;

final class PayingAgent
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
     */
    public function __construct(string $operation = null, array $phones = null)
    {
        $this->operation = $operation;
        $this->phones = $phones;
    }
}
