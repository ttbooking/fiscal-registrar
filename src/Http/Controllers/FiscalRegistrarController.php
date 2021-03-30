<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use TTBooking\FiscalRegistrar\Contracts\FiscalRegistrarFactory;
use TTBooking\FiscalRegistrar\Models\Receipt;

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

    public function sell(string $connection)
    {
        return $this->factory->connection($connection)->{__FUNCTION__}();
    }

    public function sellRefund(string $connection)
    {
        return $this->factory->connection($connection)->{__FUNCTION__}();
    }

    public function buy(string $connection)
    {
        return $this->factory->connection($connection)->{__FUNCTION__}();
    }

    public function buyRefund(string $connection)
    {
        return $this->factory->connection($connection)->{__FUNCTION__}();
    }

    public function list(string $connection)
    {
        return Receipt::query()->where(compact('connection'))->get();
    }

    public function report(string $connection, string $id)
    {
        return $this->factory->connection($connection)->{__FUNCTION__}($id);
    }

    public function callback(Request $request, string $connection)
    {
        return $this->factory->connection($connection)->processCallback($request->all());
    }
}
