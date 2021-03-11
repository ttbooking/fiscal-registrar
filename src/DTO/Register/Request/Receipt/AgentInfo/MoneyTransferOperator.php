<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\DTO\Register\Request\Receipt\AgentInfo;

final class MoneyTransferOperator
{
    // 1075
    /** @var string[]|null */
    public ?array $phones;

    // 1026
    public ?string $name;

    // 1005
    public ?string $address;

    // 1016
    public ?string $inn;

    /**
     * MoneyTransferOperator constructor.
     *
     * @param  string[]|null  $phones
     * @param  string|null  $name
     * @param  string|null  $address
     * @param  string|null  $inn
     */
    public function __construct(array $phones = null, string $name = null, string $address = null, string $inn = null)
    {
        $this->phones = $phones;
        $this->name = $name;
        $this->address = $address;
        $this->inn = $inn;
    }
}
