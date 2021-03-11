<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\DTO\Register\Request\Receipt;

final class Company
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
     */
    public function __construct(string $email, string $inn, string $paymentAddress, string $sno = null)
    {
        $this->email = $email;
        $this->sno = $sno;
        $this->inn = $inn;
        $this->paymentAddress = $paymentAddress;
    }
}
