<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\Support;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;

class ReceiptQueryBuilder extends QueryBuilder
{
    public function __construct($subject, ?Request $request = null)
    {
        parent::__construct($subject, $request);

        $this->applyConfiguration();
    }

    private function applyConfiguration(): void
    {
        if ($filters = array_merge($this->configureBuiltinFilters(), $this->configureFilters())) {
            $this->allowedFilters($filters);
        }

        if ($sorts = array_merge($this->configureBuiltinSorts(), $this->configureSorts())) {
            $this->allowedSorts($sorts);
        }

        if ($includes = $this->configureIncludes()) {
            $this->allowedIncludes($includes);
        }

        if ($fields = $this->configureFields()) {
            $this->allowedFields($fields);
        }

        if ($appends = $this->configureAppends()) {
            $this->allowedAppends($appends);
        }
    }

    private function configureBuiltinFilters(): array
    {
        $receipt = $this->getModel();

        return [
            AllowedFilter::exact('id', $receipt->getKeyName()),
            'external_id',
            'internal_id',
            AllowedFilter::callback('created_from', function (Builder $query, string $value) use ($receipt) {
                $query->where($receipt::CREATED_AT, '>=', $value);
            }),
            AllowedFilter::callback('created_to', function (Builder $query, string $value) use ($receipt) {
                $query->where($receipt::CREATED_AT, '<=', $value);
            }),
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
            AllowedFilter::exact('fn', 'fn_number'),
            AllowedFilter::exact('i', 'fiscal_document_number'),
            AllowedFilter::exact('fd', 'fiscal_document_attribute'),
        ];
    }

    private function configureBuiltinSorts(): array
    {
        return [
            'id',
            'external_id',
            'internal_id',
            'created_at',
            'connection',
            'operation',
            AllowedSort::callback('total', function (Builder $query, bool $desc) {
                $query->orderBy(DB::raw('cast(payload->>"$.total" as decimal)'), $desc ? 'desc' : 'asc');
            }),
            'state',
        ];
    }

    protected function configureFilters(): array
    {
        return [];
    }

    protected function configureSorts(): array
    {
        return [];
    }

    protected function configureIncludes(): array
    {
        return [];
    }

    protected function configureFields(): array
    {
        return [];
    }

    protected function configureAppends(): array
    {
        return [];
    }
}
