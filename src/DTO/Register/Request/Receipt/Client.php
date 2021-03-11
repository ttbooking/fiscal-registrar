<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\DTO\Register\Request\Receipt;

final class Client
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
     */
    public function __construct(string $email = null, string $phone = null, string $name = null, string $inn = null)
    {
        $this->email = $email;
        $this->phone = $phone;
        $this->name = $name;
        $this->inn = $inn;
    }
}
