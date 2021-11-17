<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\Http\Controllers;

use Illuminate\Contracts\Config\Repository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\View;
use Illuminate\Validation\Rule;
use TTBooking\FiscalRegistrar\Enums\Operation;
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
        $receipt = $this->receipt->newQuery()->create(static::validateRequest($request));

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
        $receipt->update(static::validateRequest($request));

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

    /**
     * @param  Receipt  $receipt
     * @return \Illuminate\Contracts\View\View
     */
    public function preview(Repository $config, Receipt $receipt): \Illuminate\Contracts\View\View
    {
        $connectionConfig = $config->get("fiscal-registrar.connections.{$receipt->connection}", []);

        $template = $connectionConfig['receipt_template'] ?? (
            View::exists("fiscal-registrar::receipt.{$receipt->connection}") ? $receipt->connection : 'default'
        );

        return View::make("fiscal-registrar::receipt.$template", compact('receipt', 'connectionConfig'));
    }

    /**
     * @param  Receipt  $receipt
     * @return JsonResponse
     */
    public function register(Receipt $receipt): JsonResponse
    {
        return Response::json($receipt->register());
    }

    /**
     * @param  Request  $request
     * @param  Receipt  $receipt
     * @return JsonResponse
     */
    public function report(Request $request, Receipt $receipt): JsonResponse
    {
        return Response::json($receipt->report(null, (bool) $request->query('force')));
    }

    /**
     * Validate the request.
     *
     * @param  Request  $request
     * @return array
     */
    protected static function validateRequest(Request $request): array
    {
        return $request->validate([
            'connection' => 'sometimes|nullable|string|max:32',
            'operation' => ['sometimes', 'nullable', 'string', Rule::in(Operation::toArray())],
            'external_id' => 'sometimes|nullable|string|max:128',
            //'internal_id' => 'sometimes|nullable|string|max:128',
            'data' => ['array', new Rules\Receipt],
            //'result' => ['array', new Rules\Result],
        ]);
    }
}
