<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\Tests;

use Orchestra\Testbench\TestCase as OrchestraTestCase;
use TTBooking\FiscalRegistrar\Facades\FiscalRegistrar;
use TTBooking\FiscalRegistrar\Facades\Receipt;
use TTBooking\FiscalRegistrar\FiscalRegistrarServiceProvider;
use TTBooking\FiscalRegistrar\Support\ReceiptQRCode;

abstract class TestCase extends OrchestraTestCase
{
    protected function getPackageProviders($app): array
    {
        return [FiscalRegistrarServiceProvider::class];
    }

    protected function getPackageAliases($app): array
    {
        return [
            'FiscalRegistrar' => FiscalRegistrar::class,
            'Receipt' => Receipt::class,
            'ReceiptQRCode' => ReceiptQRCode::class,
        ];
    }

    protected function getEnvironmentSetUp($app): void
    {
        //
    }
}
