<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar;

use Faker\Generator;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use TTBooking\FiscalRegistrar\Faker\Extension;
use TTBooking\FiscalRegistrar\Models\Receipt;

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
        $this->registerEvents();
        $this->registerRoutes();
        $this->registerResources();

        if ($this->app->runningInConsole()) {
            $this->offerPublishing();
            $this->registerMigrations();
            $this->registerCommands();
        }
    }

    /**
     * Register the Fiscal Registrar event listeners.
     *
     * @return void
     */
    protected function registerEvents(): void
    {
        Event::listen([
            Events\Registering::class,
            Events\Registered::class,
            //Events\Processed::class,
        ], Listeners\StoreReceipt::class);
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
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'fiscal-registrar');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'fiscal-registrar');
    }

    protected function offerPublishing(): void
    {
        $this->publishes([
            __DIR__.'/../config/fiscal-registrar.php' => $this->app->configPath('fiscal-registrar.php'),
        ], 'config');

        $this->publishes([
            __DIR__.'/../database/migrations' => $this->app->databasePath('migrations'),
        ], 'migrations');

        $this->publishes([
            __DIR__.'/../stubs/Receipt.stub' => $this->app->path('Models/Receipt.php'),
        ], 'model');

        $this->publishes([
            __DIR__.'/../resources/views' => $this->app->resourcePath('views/vendor/fiscal-registrar'),
        ], 'views');

        $this->publishes([
            __DIR__.'/../public' => public_path('vendor/fiscal-registrar'),
        ], 'assets');
    }

    protected function registerMigrations(): void
    {
        //$this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }

    protected function registerCommands(): void
    {
        $this->commands([
            Console\ReceiptSellCommand::class,
            Console\ReceiptSellRefundCommand::class,
            Console\ReceiptBuyCommand::class,
            Console\ReceiptBuyRefundCommand::class,
            Console\ReceiptCloneCommand::class,
            Console\ReceiptDeleteCommand::class,
        ]);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->configure();
        $this->registerServices();
        $this->registerFakerProviders();
    }

    protected function configure(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/fiscal-registrar.php', 'fiscal-registrar');
    }

    protected function registerServices(): void
    {
        $this->app->alias('fiscal-registrar', Contracts\FiscalRegistrarFactory::class);
        $this->app->alias('fiscal-registrar', Contracts\FiscalRegistrar::class);
        $this->app->singleton('fiscal-registrar.connection', fn ($app) => $app['fiscal-registrar']->connection());
        $this->app->bind(Receipt::class, $this->app['config']['fiscal-registrar.model'] ?? Receipt::class);
    }

    protected function registerFakerProviders(): void
    {
        $this->callAfterResolving(Generator::class,
            fn ($faker, $app) => Extension::extend($faker, $app['config']['app.faker_locale'] ?? 'en_US')
        );
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
                Receipt::class,
            ]
        );
    }
}
