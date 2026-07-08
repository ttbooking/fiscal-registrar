<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Response;
use TTBooking\FiscalRegistrar\Http\Requests\ReceiptStoreRequest;
use TTBooking\FiscalRegistrar\Models\Receipt;
use TTBooking\FiscalRegistrar\Support\ReceiptQueryBuilder;
use TTBooking\FiscalRegistrar\Support\ReceiptView;

class ReceiptController extends Controller
{
    public function __construct(protected Receipt $receipt) {}

    /**
     * Display a listing of the receipts.
     */
    public function index(ReceiptQueryBuilder $query): JsonResponse
    {
        return Response::json($query->paginate());
    }

    /**
     * Store a newly created receipt in storage.
     */
    public function store(ReceiptStoreRequest $request): JsonResponse
    {
        $receipt = $this->receipt->newQuery()->create($request->validated());

        return Response::json($receipt, JsonResponse::HTTP_CREATED);
    }

    /**
     * Display the specified receipt.
     */
    public function show(Receipt $receipt): JsonResponse
    {
        return Response::json($receipt);
    }

    /**
     * Update the specified receipt in storage.
     */
    public function update(ReceiptStoreRequest $request, Receipt $receipt): JsonResponse
    {
        $receipt->update($request->validated());

        return Response::json($receipt);
    }

    /**
     * Remove the specified receipt from storage.
     */
    public function destroy(Receipt $receipt): \Illuminate\Http\Response
    {
        $receipt->delete();

        return Response::noContent();
    }

    public function preview(Receipt $receipt): View
    {
        return ReceiptView::for($receipt);
    }

    public function register(Receipt $receipt): JsonResponse
    {
        return Response::json($receipt->register());
    }

    public function report(Request $request, Receipt $receipt): JsonResponse
    {
        return Response::json($receipt->report(null, (bool) $request->query('force')));
    }
}
