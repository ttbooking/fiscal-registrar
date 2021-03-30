<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use TTBooking\FiscalRegistrar\DTO;
use TTBooking\FiscalRegistrar\Facades\FiscalRegistrar;

/**
 * @property string|null $connection
 * @property string|null $operation
 * @property string|null $external_id
 * @property string|null $internal_id
 * @property DTO\Receipt $data
 * @property DTO\Result|null $result
 */
class Receipt extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'data' => DTO\Receipt::class,
        'result' => DTO\Result::class,
    ];

    public function resolveRouteBinding($value, $field = null)
    {
        if (Str::contains($value, ':')) {
            [$connection, $external_id] = explode(':', $value, 2);
            return $this->newQuery()->where(array_filter(compact('connection', 'external_id')))->first();
        }

        return parent::resolveRouteBinding($value, $field);
    }

    public function report(): DTO\Result
    {
        return FiscalRegistrar::connection($this->connection)->report($this->internal_id);
    }
}
