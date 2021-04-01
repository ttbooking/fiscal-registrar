<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Response;
use TTBooking\FiscalRegistrar\Models\Receipt;
use TTBooking\FiscalRegistrar\Rules;

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
        $attributes = $request->validate([
            'connection' => 'sometimes|nullable|string|max:32',
            'operation' => 'sometimes|nullable|string|max:32',
            'external_id' => 'sometimes|nullable|string|max:128',
            'internal_id' => 'sometimes|nullable|string|max:128',
            'data' => ['array', new Rules\Receipt],
            'result' => ['array', new Rules\Result],
        ]);

        $receipt = $this->receipt->newQuery()->create($attributes);

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
