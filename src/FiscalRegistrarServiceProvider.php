<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar;

use Faker\Generator;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use TTBooking\FiscalRegistrar\Faker\Extension;
use TTBooking\FiscalRegistrar\Models\Receipt;
use TTBooking\FiscalRegistrar\Support\QRCodeBlock;
use TTBooking\FiscalRegistrar\Support\QRCodePng;

class FiscalRegistrarServiceProvider extends ServiceProvider //implements DeferrableProvider
{
    /**
     * All of the container singletons that should be registered.
     *
     * @var array<string, string>
     */
    public array $singletons = [
        'fiscal-registrar' => FiscalRegistrarManager::class,
        'fiscal-registrar.receipt' => FluentReceipt::class,
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
        $this->registerCommands();

        if ($this->app->runningInConsole()) {
            $this->offerPublishing();
            $this->registerMigrations();
        }
    }

    /**
     * Register the Fiscal Registrar event listeners.
     *
     * @return void
     */
    protected function registerEvents(): void
    {
        if ($this->app['config']['fiscal-registrar.notify_client'] &&
            $this->app->bound(\Illuminate\Contracts\Notifications\Dispatcher::class)) {
            Event::listen(Events\Processed::class, Listeners\SendNotification::class);
        }
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
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }

    protected function registerCommands(): void
    {
        $this->commands([
            Console\ReceiptSellCommand::class,
            Console\ReceiptSellRefundCommand::class,
            Console\ReceiptBuyCommand::class,
            Console\ReceiptBuyRefundCommand::class,
            Console\ReceiptSyncCommand::class,
            Console\ReceiptCloneCommand::class,
            Console\ReceiptDeleteCommand::class,
            Console\ReceiptShowCommand::class,
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
        $this->registerSyncJobSchedule();
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
        $this->app->alias('fiscal-registrar.receipt', Contracts\ReceiptFactory::class);
        $this->app->alias('fiscal-registrar.receipt', Contracts\Receipt::class);
        $this->app->bind(Receipt::class, $this->app['config']['fiscal-registrar.model'] ?? Receipt::class);
        $this->app->singleton('fiscal-registrar.qr-code', function (Application $app) {
            return $app->make($app->runningInConsole() ? QRCodeBlock::class : QRCodePng::class);
        });
        $this->app->alias('fiscal-registrar.qr-code', Contracts\QRCodeBuilder::class);
    }

    protected function registerSyncJobSchedule(): void
    {
        $options = $this->app['config']['fiscal-registrar.sync_job'] ?? [];
        if ($this->app->runningInConsole() && ($options['schedule'] ?? false)) {
            $this->callAfterResolving(Schedule::class,
                fn (Schedule $schedule) => $schedule->job(new Jobs\SyncReceipts(
                    (int) $options['older_than_minutes'] ?? 5,
                    (int) $options['batch_size'] ?? 1
                ))->cron($options['schedule'])
            );
        }
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
                Contracts\ReceiptFactory::class,
                Contracts\Receipt::class,
                Receipt::class,
            ]
        );
    }
}
