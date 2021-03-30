<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Response;
use TTBooking\FiscalRegistrar\Models\Receipt;

class ReceiptController extends Controller
{
    protected Receipt $receipt;

    public function __construct(Receipt $receipt)
    {
        $this->receipt = $receipt;
    }

    /**
     * Display a listing of the fiscal records.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->receipt->newQuery()->paginate();
    }

    /**
     * Store a newly created fiscal record in storage.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified fiscal record.
     *
     * @param  Receipt  $receipt
     * @return \Illuminate\Http\Response
     */
    public function show(Receipt $receipt)
    {
        return $receipt;
    }

    /**
     * Update the specified fiscal record in storage.
     *
     * @param  Request  $request
     * @param  Receipt  $receipt
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Receipt $receipt)
    {
        //
    }

    /**
     * Remove the specified fiscal record from storage.
     *
     * @param  Receipt  $receipt
     * @return \Illuminate\Http\Response
     */
    public function destroy(Receipt $receipt)
    {
        $receipt->delete();

        return Response::noContent();
    }
}
