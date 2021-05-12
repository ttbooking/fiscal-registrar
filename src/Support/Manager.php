<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\Support;

use Closure;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Container\Container;
use Illuminate\Support\Str;
use InvalidArgumentException;
use TTBooking\FiscalRegistrar\Concerns\ResolvesConnections;
use TTBooking\FiscalRegistrar\Contracts\Factory;

abstract class Manager implements Factory
{
    use ResolvesConnections;

    /**
     * The container instance.
     *
     * @var Container
     */
    protected Container $container;

    /**
     * The configuration repository instance.
     *
     * @var Repository
     */
    protected Repository $config;

    /**
     * Configuration name.
     *
     * @var string
     */
    protected string $configName;

    /**
     * The registered custom driver creators.
     *
     * @var array<string, Closure>
     */
    protected array $customCreators = [];

    /**
     * The array of created connections.
     *
     * @var array<string, object>
     */
    protected array $connections = [];

    /**
     * Create a new manager instance.
     *
     * @param  Container  $container
     * @return void
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
        $this->config = $container->make('config');
        $this->resolveConnectionsUsing([$this, 'getDefaultDriver']);
    }

    /**
     * Get the default driver name.
     *
     * @return string
     */
    public function getDefaultDriver(): string
    {
        $configName = $this->getConfigName();

        return $this->config->get("{$configName}.default", 'default');
    }

    public function connection(string $name = null): object
    {
        $name ??= $this->resolveConnection();

        return $this->connections[$name] ??= $this->resolve($name);
    }

    public function getConnections(): array
    {
        return $this->connections;
    }

    /**
     * Dynamically call the default connection instance.
     *
     * @param  string  $method
     * @param  array  $parameters
     * @return mixed
     */
    public function __call(string $method, array $parameters)
    {
        return $this->connection()->$method(...$parameters);
    }

    /**
     * Register a custom driver creator Closure.
     *
     * @param  string  $driver
     * @param  Closure  $callback
     * @return $this
     */
    public function extend(string $driver, Closure $callback): static
    {
        $this->customCreators[$driver] = $callback->bindTo($this, $this);

        return $this;
    }

    /**
     * Resolve the given connection.
     *
     * @param  string  $name
     * @return object
     *
     * @throws InvalidArgumentException
     */
    protected function resolve(string $name): object
    {
        $config = $this->getConfig($name);

        if (isset($this->customCreators[$config['driver']])) {
            return $this->callCustomCreator($config);
        } else {
            $method = 'create'.Str::studly($config['driver']).'Driver';

            if (method_exists($this, $method)) {
                return $this->$method($config, $name);
            }
        }

        throw new InvalidArgumentException("Driver [{$config['driver']}] not supported.");
    }

    /**
     * Call a custom driver creator.
     *
     * @param  array  $config
     * @return object
     */
    protected function callCustomCreator(array $config): object
    {
        return $this->customCreators[$config['driver']]($this->container, $config);
    }

    /**
     * Get the configuration name.
     *
     * @return string
     */
    protected function getConfigName(): string
    {
        return $this->configName;
    }

    /**
     * Get the cache connection configuration.
     *
     * @param  string  $name
     * @return array
     */
    protected function getConfig(string $name): array
    {
        $configName = $this->getConfigName();

        return $this->config->get("{$configName}.connections.{$name}", []) + ['driver' => $name];
    }
}
