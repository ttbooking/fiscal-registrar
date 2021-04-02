<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\Tests;

use Orchestra\Testbench\TestCase as OrchestraTestCase;
use TTBooking\FiscalRegistrar\FiscalRegistrarServiceProvider;

abstract class TestCase extends OrchestraTestCase
{
    protected function getPackageProviders($app): array
    {
        return [FiscalRegistrarServiceProvider::class];
    }

    protected function getEnvironmentSetUp($app): void
    {
        //
    }
}
