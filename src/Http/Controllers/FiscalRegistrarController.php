<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Response;
use TTBooking\FiscalRegistrar\Contracts\FiscalRegistrarFactory;
use TTBooking\FiscalRegistrar\DTO\Receipt;
use TTBooking\FiscalRegistrar\Enums\Operation;

class FiscalRegistrarController extends Controller
{
    protected FiscalRegistrarFactory $factory;

    /**
     * Create a new controller instance.
     *
     * @param FiscalRegistrarFactory $factory
     */
    public function __construct(FiscalRegistrarFactory $factory)
    {
        $this->factory = $factory;
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
