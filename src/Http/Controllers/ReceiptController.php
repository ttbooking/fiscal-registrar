<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use TTBooking\FiscalRegistrar\Models\FiscalRecord;

class ReceiptController extends Controller
{
    /**
     * Display a listing of the fiscal records.
     *
     * @return Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created fiscal record in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified fiscal record.
     *
     * @param  FiscalRecord  $fiscalRecord
     * @return Response
     */
    public function show(FiscalRecord $fiscalRecord)
    {
        //
    }

    /**
     * Update the specified fiscal record in storage.
     *
     * @param  Request  $request
     * @param  FiscalRecord  $fiscalRecord
     * @return Response
     */
    public function update(Request $request, FiscalRecord $fiscalRecord)
    {
        //
    }

    /**
     * Remove the specified fiscal record from storage.
     *
     * @param  FiscalRecord  $fiscalRecord
     * @return Response
     */
    public function destroy(FiscalRecord $fiscalRecord)
    {
        //
    }
}
