<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Response;
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
        return $this->factory->connection($connection)->register();
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
