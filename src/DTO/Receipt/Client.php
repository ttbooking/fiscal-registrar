<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\DTO\Receipt;

use TTBooking\FiscalRegistrar\DTO\DataTransferObject;

final class Client extends DataTransferObject
{
    // 1008
    public ?string $email;
    public ?string $phone;

    // 1227
    public ?string $name;

    // 1228
    public ?string $inn;

    /**
     * Client constructor.
     *
     * @param  string|null  $email
     * @param  string|null  $phone
     * @param  string|null  $name
     * @param  string|null  $inn
     * @return self
     */
    public static function new(
        string $email = null,
        string $phone = null,
        string $name = null,
        string $inn = null
    ): self {
        return new self(compact('email', 'phone', 'name', 'inn'));
    }
}
