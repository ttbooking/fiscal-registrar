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
 * @property DTO\Receipt $payload
 * @property DTO\Result|null $result
 * @property-read string|null $fn_number
 * @property-read int|null $fiscal_document_number
 * @property-read int|null $fiscal_document_attribute
 * @property-read float|null $total
 */
class Receipt extends Model implements StatefulFiscalRegistrar
{
    use HasFactory;

    const STATE_CREATED = 0;
    const STATE_REGISTERED = 1;
    const STATE_PROCESSED = 2;

    protected const VIRTUAL_FIELDS = ['fn_number', 'fiscal_document_number', 'fiscal_document_attribute', 'total'];

    protected $hidden = self::VIRTUAL_FIELDS;

    protected $guarded = ['id', ...self::VIRTUAL_FIELDS, self::CREATED_AT, self::UPDATED_AT];

    /** @var array<string, mixed> */
    protected $attributes = [
        'state' => self::STATE_CREATED,
    ];

    protected $casts = [
        'operation' => Enums\Operation::class,
        'payload' => DTO\Receipt::class,
        'result' => DTO\Result::class,
    ];

    /**
     * Perform any actions required after the model boots.
     */
    public static function booted(): void
    {
        static::saving(function (self $receipt) {
            $receipt->setAttribute('connection',
                $receipt->getAttribute('connection') ?? $receipt->resolveConnectionName()
            );
        });
    }

    public function resolveRouteBinding($value, $field = null, bool $sole = false): ?self
    {
        $method = $sole ? 'sole' : 'first';

        if (is_string($value)) {
            if (Str::startsWith($value, '@')) {
                return $this->newQuery()->where(array_filter(array_combine(
                    ['fn_number', 'fiscal_document_number', 'fiscal_document_attribute'],
                    explode(':', ltrim($value, '@'), 3) + array_fill(0, 3, '')
                )))->$method();
            }

            if (Str::contains($value, ':')) {
                return $this->newQuery()->where(array_filter(array_combine(
                    ['connection', 'external_id'], explode(':', $value, 2)
                )))->$method();
            }
        }

        return $this->newQuery()->where($field ?? $this->getRouteKeyName(), $value)->$method();
    }

    public function register(
        Enums\Operation $operation = null,
        string $externalId = null,
        DTO\Receipt $payload = null
    ): string {
        if ($this->state !== self::STATE_CREATED) {
            throw new StateException('Receipt has already been registered.');
        }

        $this->operation = $operation ?? $this->operation;
        $this->external_id = $externalId ?? $this->external_id ?? $this->generateId();
        $this->payload = $payload ?? $this->payload;

        if (! isset($this->operation, $this->external_id, $this->payload)) {
            throw new StateException('Insufficient parameters for operation.');
        }

        $this->save();

        /** @var string|null $connection */
        $connection = $this->getAttribute('connection');

        return FiscalRegistrar::connection($connection)->register($this->operation, $this->external_id, $this->payload);
    }

    public function report(string $id = null, bool $force = false): ?DTO\Result
    {
        if ($this->state === self::STATE_CREATED) {
            throw new StateException('Receipt is unregistered.');
        }

        if ($force || $this->state < self::STATE_PROCESSED) {
            $id ??= $this->internal_id;

            if (! isset($id)) {
                throw new StateException('Required parameter missing.');
            }

            /** @var string|null $connection */
            $connection = $this->getAttribute('connection');

            return FiscalRegistrar::connection($connection)->report($id);
        }

        return $this->result;
    }

    public function resolveConnectionName(): ?string
    {
        return null;
    }

    protected function generateId(): ?string
    {
        return (string) Str::uuid();
    }

    protected static function newFactory(): ReceiptFactory
    {
        return ReceiptFactory::new();
    }
}
