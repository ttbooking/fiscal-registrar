<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Response;
use TTBooking\FiscalRegistrar\Models\Receipt;

class ReceiptController extends Controller
{
    protected string $receiptModel;

    public function __construct(string $receiptModel)
    {
        if (! is_a($receiptModel, Receipt::class, true)) {
            throw new \InvalidArgumentException('Custom receipt model must extend '.Receipt::class.' class.');
        }

        $this->receiptModel = $receiptModel;
    }

    /**
     * Display a listing of the fiscal records.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->receiptModel::query()->paginate();
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
