<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class FiscalRegistrarServiceProvider extends ServiceProvider //implements DeferrableProvider
{
    /**
     * All of the container singletons that should be registered.
     *
     * @var array<string, string>
     */
    public array $singletons = [
        'fiscal-registrar' => FiscalRegistrarManager::class,
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
                __DIR__.'/../resources/views' => $this->app->resourcePath('views/vendor/fiscal-registrar'),
            ], 'views');

            $this->publishes([
                __DIR__.'/../public' => public_path('vendor/fiscal-registrar'),
            ], 'assets');

            $this->publishes([
                __DIR__.'/../config/fiscal-registrar.php' => $this->app->configPath('fiscal-registrar.php'),
            ], 'config');

            $this->publishes([
                __DIR__.'/../database/migrations' => $this->app->databasePath('migrations'),
            ], 'migrations');

            $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        }

        $this->registerRoutes();
        $this->registerResources();
        $this->registerListeners();
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/fiscal-registrar.php', 'fiscal-registrar');

        $this->app->alias('fiscal-registrar', Contracts\FiscalRegistrarFactory::class);
        $this->app->alias('fiscal-registrar', Contracts\FiscalRegistrar::class);
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
            array_keys($this->singletons), [
                Contracts\FiscalRegistrarFactory::class,
                Contracts\FiscalRegistrar::class,
                'fiscal-registrar.connection',
            ]
        );
    }

    /**
     * Register the Fiscal Registrar routes.
     *
     * @return void
     */
    protected function registerRoutes(): void
    {
        Route::group([
            'domain' => $this->app['config']['fiscal-registrar.domain'] ?? null,
            'prefix' => $this->app['config']['fiscal-registrar.path'] ?? null,
            'namespace' => 'TTBooking\\FiscalRegistrar\\Http\\Controllers',
            'middleware' => $this->app['config']['fiscal-registrar.middleware'] ?? 'web',
        ], function () {
            $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        });
    }

    /**
     * Register the Fiscal Registrar resources.
     *
     * @return void
     */
    protected function registerResources(): void
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'fiscal-registrar');
    }

    /**
     * Register the Fiscal Registrar event listeners.
     *
     * @return void
     */
    protected function registerListeners(): void
    {
        Event::listen([Events\Registering::class, Events\Registered::class], Listeners\StoreReceipt::class);
    }
}
