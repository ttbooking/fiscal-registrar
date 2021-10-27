<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\Http\Controllers;

use Illuminate\Contracts\Config\Repository;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Response;
use TTBooking\FiscalRegistrar\Contracts\FiscalRegistrarFactory;
use TTBooking\FiscalRegistrar\DTO\Receipt;
use TTBooking\FiscalRegistrar\Enums\Operation;

class FiscalRegistrarController extends Controller
{
    protected Repository $config;

    protected FiscalRegistrarFactory $factory;

    /**
     * Create a new controller instance.
     *
     * @param Repository $config
     * @param FiscalRegistrarFactory $factory
     */
    public function __construct(Repository $config, FiscalRegistrarFactory $factory)
    {
        $this->config = $config;
        $this->factory = $factory;
    }

    public function connections()
    {
        /** @var array<string, array> $connections */
        $connections = $this->config->get('fiscal-registrar.connections');

        return array_map(
            static fn (string $name, array $data) => [
                'value' => $name,
                'text' => $data['display_name'] ?? $name,
                'test' => $data['test'] ?? false,
            ],
            array_keys($connections), $connections
        );
    }

    public function sell(Request $request, string $connection, string $externalId)
    {
        return $this->factory->connection($connection)->register(
            Operation::Sell(),
            $externalId,
            new Receipt($request->all())
        );
    }

    public function sellRefund(Request $request, string $connection, string $externalId)
    {
        return $this->factory->connection($connection)->register(
            Operation::SellRefund(),
            $externalId,
            new Receipt($request->all())
        );
    }

    public function buy(Request $request, string $connection, string $externalId)
    {
        return $this->factory->connection($connection)->register(
            Operation::Buy(),
            $externalId,
            new Receipt($request->all())
        );
    }

    public function buyRefund(Request $request, string $connection, string $externalId)
    {
        return $this->factory->connection($connection)->register(
            Operation::BuyRefund(),
            $externalId,
            new Receipt($request->all())
        );
    }

    public function report(string $connection, string $id)
    {
        return $this->factory->connection($connection)->report($id);
    }

    public function callback(Request $request, string $connection): \Illuminate\Http\Response
    {
        $this->factory->connection($connection)->processCallback($request->all());

        return Response::noContent();
    }
}
