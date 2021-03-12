<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\Http\Controllers;

use Illuminate\Routing\Controller;
use TTBooking\FiscalRegistrar\Contracts\FiscalRegistrarFactory;
use TTBooking\FiscalRegistrar\Models\FiscalRecord;

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
        return $this->factory->connection($connection)->sell();
    }

    public function sellRefund(string $connection)
    {
        return $this->factory->connection($connection)->sellRefund();
    }

    public function buy(string $connection)
    {
        return $this->factory->connection($connection)->buy();
    }

    public function buyRefund(string $connection)
    {
        return $this->factory->connection($connection)->buyRefund();
    }

    public function list(string $connection)
    {
        return FiscalRecord::query()->where(compact('connection'))->get();
    }

    public function report(string $connection, string $id)
    {
        return $this->factory->connection($connection)->report($id);
    }
}
