<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use TTBooking\FiscalRegistrar\Contracts\FiscalRegistrarFactory;

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

    public function register(string $connection)
    {
        return $this->factory->connection($connection)->{__FUNCTION__}();
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
