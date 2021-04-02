<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use TTBooking\FiscalRegistrar\Database\Factories\ReceiptFactory;
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
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'data' => DTO\Receipt::class,
        'result' => DTO\Result::class,
    ];

    public function resolveRouteBinding($value, $field = null, $sole = false)
    {
        $method = $sole ? 'sole' : 'first';

        if (Str::contains($value, ':')) {
            [$connection, $external_id] = explode(':', $value, 2);
            return $this->newQuery()->where(array_filter(compact('connection', 'external_id')))->$method();
        }

        return $this->newQuery()->where($field ?? $this->getRouteKeyName(), $value)->$method();
    }

    /**
     * @param  mixed  $id
     * @return Model|static
     */
    public function resolve($id): self
    {
        return $this->resolveRouteBinding($id, null, true);
    }

    /**
     * @return $this
     */
    public function sell(): self
    {
        FiscalRegistrar::connection($this->connection)->sell($this->external_id, $this->data);

        return $this;
    }

    /**
     * @return $this
     */
    public function sellRefund(): self
    {
        FiscalRegistrar::connection($this->connection)->sellRefund($this->external_id, $this->data);

        return $this;
    }

    /**
     * @return $this
     */
    public function buy(): self
    {
        FiscalRegistrar::connection($this->connection)->buy($this->external_id, $this->data);

        return $this;
    }

    /**
     * @return $this
     */
    public function buyRefund(): self
    {
        FiscalRegistrar::connection($this->connection)->buyRefund($this->external_id, $this->data);

        return $this;
    }

    public function report(): DTO\Result
    {
        return FiscalRegistrar::connection($this->connection)->report($this->internal_id);
    }

    protected static function newFactory(): ReceiptFactory
    {
        return ReceiptFactory::new();
    }
}
