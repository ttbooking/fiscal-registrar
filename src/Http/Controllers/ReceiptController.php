<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\Http\Controllers;

use Illuminate\Contracts\Config\Repository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\View;
use TTBooking\FiscalRegistrar\Http\Requests\ReceiptStoreRequest;
use TTBooking\FiscalRegistrar\Models\Receipt;
use TTBooking\FiscalRegistrar\Support\ReceiptQueryBuilder;

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

    public function preview(Repository $config, Receipt $receipt): \Illuminate\Contracts\View\View
    {
        /** @var array{receipt_template?: string} $connectionConfig */
        $connectionConfig = $config->get("fiscal-registrar.connections.{$receipt->connection}", []);

        $template = $connectionConfig['receipt_template'] ?? (
            View::exists("fiscal-registrar::receipt.{$receipt->connection}") ? $receipt->connection : 'default'
        );

        return View::make("fiscal-registrar::receipt.$template", compact('receipt', 'connectionConfig'));
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
