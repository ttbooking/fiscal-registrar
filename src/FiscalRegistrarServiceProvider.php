<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;
use Lamoda\AtolClient\V4\AtolApi;

class FiscalRegistrarServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * All of the container singletons that should be registered.
     *
     * @var array<string, string>
     */
    public array $singletons = [
        Contracts\FiscalRegistrar::class => FiscalRegistrarManager::class,
        AtolApi::class => AtolApi::class,
    ];

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/fiscal-registrar.php' => $this->app->configPath('fiscal-registrar.php'),
            ], 'config');
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/fiscal-registrar.php', 'fiscal-registrar');

        $this->app->alias(Contracts\FiscalRegistrar::class, 'fiscal-registrar');
        $this->app->singleton('fiscal-registrar.connection', fn ($app) => $app['fiscal-registrar']->connection());
    }

    /**
     * Get the services provided by the provider.
     *
     * @return string[]
     */
    public function provides(): array
    {
        return array_merge(
            array_keys($this->singletons),
            ['fiscal-registrar', 'fiscal-registrar.connection']
        );
    }
}
