<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use TTBooking\FiscalRegistrar\Contracts\StatefulFiscalRegistrar;
use TTBooking\FiscalRegistrar\Database\Factories\ReceiptFactory;
use TTBooking\FiscalRegistrar\DTO;
use TTBooking\FiscalRegistrar\Enums;
use TTBooking\FiscalRegistrar\Exceptions\StateException;
use TTBooking\FiscalRegistrar\Facades\FiscalRegistrar;

/**
 * @property int $state
 * @property string|null $connection
 * @property Enums\Operation|null $operation
 * @property string|null $external_id
 * @property string|null $internal_id
 * @property DTO\Receipt $data
 * @property DTO\Result|null $result
 */
class Receipt extends Model implements StatefulFiscalRegistrar
{
    use HasFactory;

    const STATE_CREATED = 0;
    const STATE_REGISTERED = 1;
    const STATE_PROCESSED = 2;

    protected $fillable = ['state', 'connection', 'operation', 'external_id', 'internal_id', 'data', 'result'];

    protected $attributes = [
        'state' => self::STATE_CREATED,
    ];

    protected $casts = [
        'operation' => Enums\Operation::class,
        'data' => DTO\Receipt::class,
        'result' => DTO\Result::class,
    ];

    public function resolveRouteBinding($value, $field = null, $sole = false): ?self
    {
        $method = $sole ? 'sole' : 'first';

        if (Str::contains($value, ':')) {
            [$connection, $external_id] = explode(':', $value, 2);
            return $this->newQuery()->where(array_filter(compact('connection', 'external_id')))->$method();
        }

        return $this->newQuery()->where($field ?? $this->getRouteKeyName(), $value)->$method();
    }

    public function register(
        Enums\Operation $operation = null,
        string $externalId = null,
        DTO\Receipt $data = null
    ): string {
        $this->checkState(self::STATE_CREATED);

        $operation ??= $this->operation;
        $externalId ??= $this->external_id;
        $data ??= $this->data;

        if (! isset($operation, $externalId, $data)) {
            throw new StateException('Insufficient parameters for operation.');
        }

        return FiscalRegistrar::connection($this->getAttribute('connection'))->register($operation, $externalId, $data);
    }

    public function report(string $id = null, bool $force = false): ?DTO\Result
    {
        $this->checkState(self::STATE_REGISTERED);

        if ($force || $this->state < self::STATE_PROCESSED) {
            $id ??= $this->internal_id;

            if (! isset($id)) {
                throw new StateException('Required parameter missing.');
            }

            return FiscalRegistrar::connection($this->getAttribute('connection'))->report($id);
        }

        return $this->result;
    }

    protected static function newFactory(): ReceiptFactory
    {
        return ReceiptFactory::new();
    }

    /**
     * @param  int  $state
     * @return void
     *
     * @throws StateException
     */
    public function checkState(int $state): void
    {
        if ($this->state > $state) {
            throw new StateException('Receipt has invalid state for operation.');
        }
    }
}
