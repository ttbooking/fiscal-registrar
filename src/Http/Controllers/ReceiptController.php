<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\Http\Controllers;

use Illuminate\Http\JsonResponse;
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
     * Display a listing of the receipts.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return Response::json($this->receipt->newQuery()->paginate());
    }

    /**
     * Store a newly created receipt in storage.
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $receipt = $this->receipt->newQuery()->create($request->all());

        return Response::json($receipt, JsonResponse::HTTP_CREATED);
    }

    /**
     * Display the specified receipt.
     *
     * @param  Receipt  $receipt
     * @return JsonResponse
     */
    public function show(Receipt $receipt): JsonResponse
    {
        return Response::json($receipt);
    }

    /**
     * Update the specified receipt in storage.
     *
     * @param  Request  $request
     * @param  Receipt  $receipt
     * @return JsonResponse
     */
    public function update(Request $request, Receipt $receipt): JsonResponse
    {
        $receipt->update($request->all());

        return Response::json($receipt);
    }

    /**
     * Remove the specified receipt from storage.
     *
     * @param  Receipt  $receipt
     * @return \Illuminate\Http\Response
     */
    public function destroy(Receipt $receipt): \Illuminate\Http\Response
    {
        $receipt->delete();

        return Response::noContent();
    }
}
