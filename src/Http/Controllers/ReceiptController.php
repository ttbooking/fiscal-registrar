<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\Http\Controllers;

use Illuminate\Contracts\Config\Repository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\View;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use TTBooking\FiscalRegistrar\Http\Requests\ReceiptStoreRequest;
use TTBooking\FiscalRegistrar\Models\Receipt;

class ReceiptController extends Controller
{
    public function __construct(protected Receipt $receipt)
    {
    }

    /**
     * Display a listing of the receipts.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $receipts = QueryBuilder::for($this->receipt->newQuery())
            ->allowedFilters(
                AllowedFilter::exact('connection'),
                AllowedFilter::exact('operation'),
                AllowedFilter::callback('min_total', function (Builder $query, float $value) {
                    $query->where('payload->total', '>=', DB::raw($value));
                }),
                AllowedFilter::callback('max_total', function (Builder $query, float $value) {
                    $query->where('payload->total', '<=', DB::raw($value));
                }),
                AllowedFilter::exact('state'),
                AllowedFilter::callback('email', function (Builder $query, string $value) {
                    $query->where('payload->client->email', 'like', $value.'%');
                }),
                AllowedFilter::callback('phone', function (Builder $query, string $value) {
                    $query->where('payload->client->phone', 'like', $value.'%');
                }),
            )
            ->paginate();

        return Response::json($receipts);
    }

    /**
     * Store a newly created receipt in storage.
     *
     * @param  ReceiptStoreRequest  $request
     * @return JsonResponse
     */
    public function store(ReceiptStoreRequest $request): JsonResponse
    {
        $receipt = $this->receipt->newQuery()->create($request->validated());

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
     * @param  ReceiptStoreRequest  $request
     * @param  Receipt  $receipt
     * @return JsonResponse
     */
    public function update(ReceiptStoreRequest $request, Receipt $receipt): JsonResponse
    {
        $receipt->update($request->validated());

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
}
