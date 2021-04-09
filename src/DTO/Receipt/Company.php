<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\DTO\Receipt;

use TTBooking\FiscalRegistrar\DTO\DataTransferObject;
use TTBooking\FiscalRegistrar\Enums\SNO;

final class Company extends DataTransferObject
{
    // 1117
    public string $email;

    // 1055
    public ?SNO $sno;

    // 1018
    public string $inn;

    // 1187
    public string $payment_address;

    /**
     * Company constructor.
     *
     * @param  string  $email
     * @param  string  $inn
     * @param  string  $payment_address
     * @param  SNO|null  $sno
     * @return self
     */
    public static function new(string $email, string $inn, string $payment_address, SNO $sno = null): self
    {
        return new self(compact('email', 'sno', 'inn', 'payment_address'));
    }

    protected static function transformSno($sno): SNO
    {
        return new SNO($sno);
    }
}
