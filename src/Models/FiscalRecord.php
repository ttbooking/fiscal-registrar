<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use TTBooking\FiscalRegistrar\DTO\Receipt;
use TTBooking\FiscalRegistrar\DTO\Result;
use TTBooking\FiscalRegistrar\Facades\FiscalRegistrar;

/**
 * @property string $connection
 * @property string $operation
 * @property string $external_id
 * @property string $internal_id
 * @property Receipt $receipt
 * @property Result|null $result
 */
class FiscalRecord extends Model
{
    protected $table = 'fiscal_registry';

    protected $guarded = ['id'];

    protected $casts = [
        'receipt' => Receipt::class,
        'result' => Result::class,
    ];

    public function resolveRouteBinding($value, $field = null)
    {
        if (Str::contains($value, ':')) {
            [$connection, $external_id] = explode(':', $value, 2);
            return $this->newQuery()->where(array_filter(compact('connection', 'external_id')))->first();
        }

        return parent::resolveRouteBinding($value, $field);
    }

    public function report(): Result
    {
        return FiscalRegistrar::connection($this->connection)->report($this->internal_id);
    }
}
