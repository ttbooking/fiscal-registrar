<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\DTO\Receipt\AgentInfo;

use TTBooking\FiscalRegistrar\DTO\DataTransferObject;

final class MoneyTransferOperator extends DataTransferObject
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
     * @return self
     */
    public static function new(
        array $phones = null,
        string $name = null,
        string $address = null,
        string $inn = null
    ): self {
        return new self(compact('phones', 'name', 'address', 'inn'));
    }
}
