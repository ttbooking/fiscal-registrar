<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\Tests\Drivers;

use Lamoda\AtolClient\V4\AtolApi;
use Lamoda\AtolClient\V4\DTO\Register as AtolRegister;
use TTBooking\FiscalRegistrar\Drivers\AtolDriver;
use TTBooking\FiscalRegistrar\DTO\Receipt;

class TestAtolDriver extends AtolDriver
{
    public function setApi(AtolApi $api): static
    {
        $this->api = $api;

        return $this;
    }

    public function exposedMakeRequest(string $externalId, Receipt $receipt): AtolRegister\RegisterRequest
    {
        return $this->makeRequest($externalId, $receipt);
    }
}
