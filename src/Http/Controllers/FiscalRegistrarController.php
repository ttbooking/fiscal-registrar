<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\Http\Controllers;

use Illuminate\Contracts\Config\Repository;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Response;
use TTBooking\FiscalRegistrar\Contracts\FiscalRegistrarFactory;
use TTBooking\FiscalRegistrar\Contracts\SupportsCallbacks;
use TTBooking\FiscalRegistrar\DTO\Receipt;
use TTBooking\FiscalRegistrar\DTO\Result;
use TTBooking\FiscalRegistrar\Enums\Operation;

class FiscalRegistrarController extends Controller
{
    protected Repository $config;

    protected FiscalRegistrarFactory $factory;

    /**
     * Create a new controller instance.
     */
    public function __construct(Repository $config, FiscalRegistrarFactory $factory)
    {
        $this->config = $config;
        $this->factory = $factory;
    }

    /**
     * @return array<string, array{display_name: string, test: bool, company: array<mixed>}>
     */
    public function connections(): array
    {
        /** @var array<string, array{display_name?: string, test?: bool, company?: array<mixed>}> $connections */
        $connections = $this->config->get('fiscal-registrar.connections');

        return array_combine(
            $names = array_keys($connections),
            array_map(static fn (string $name, array $data) => [
                'display_name' => $data['display_name'] ?? $name,
                'test' => $data['test'] ?? false,
                'company' => $data['company'] ?? [],
            ], $names, $connections)
        );
    }

    public function register(Request $request, string $connection, Operation $operation, string $externalId): string
    {
        return $this->factory->connection($connection)->register($operation, $externalId, new Receipt($request->all()));
    }

    public function report(string $connection, string $id): ?Result
    {
        return $this->factory->connection($connection)->report($id);
    }

    public function callback(Request $request, string $connection): \Illuminate\Http\Response
    {
        $connection = $this->factory->connection($connection);
        if ($connection instanceof SupportsCallbacks) {
            $connection->processCallback($request->all());
        }

        return Response::noContent();
    }
}
