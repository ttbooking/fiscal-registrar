<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\DTO\Receipt;

use TTBooking\FiscalRegistrar\DTO\DataTransferObject;

final class Company extends DataTransferObject
{
    // 1117
    public string $email;

    // 1055 (enum)
    public ?string $sno;

    // 1018
    public string $inn;

    // 1187
    public string $paymentAddress;

    /**
     * Company constructor.
     *
     * @param  string  $email
     * @param  string  $inn
     * @param  string  $paymentAddress
     * @param  string|null  $sno
     * @return self
     */
    public static function new(string $email, string $inn, string $paymentAddress, string $sno = null): self
    {
        return new self(compact('email', 'sno', 'inn', 'paymentAddress'));
    }
}
