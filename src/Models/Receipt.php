<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use RuntimeException;
use TTBooking\FiscalRegistrar\Concerns\FluentOperation;
use TTBooking\FiscalRegistrar\Contracts;
use TTBooking\FiscalRegistrar\Database\Factories\ReceiptFactory;
use TTBooking\FiscalRegistrar\DTO;
use TTBooking\FiscalRegistrar\Enums\Operation;
use TTBooking\FiscalRegistrar\Exceptions\ResolverException;

/**
 * @property string|null $connection
 * @property Operation|null $operation
 * @property string|null $external_id
 * @property string|null $internal_id
 * @property DTO\Receipt $data
 * @property DTO\Result|null $result
 */
class Receipt extends Model implements Contracts\Receipt, Contracts\SelfResolvable
{
    use FluentOperation, HasFactory;

    protected $fillable = ['connection', 'operation', 'external_id', 'data'];

    protected $casts = [
        'operation' => Operation::class,
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
     *
     * @throws ResolverException
     */
    public function resolve($id): self
    {
        try {
            return $this->resolveRouteBinding($id, null, true);
        } catch (RuntimeException $e) {
            throw new ResolverException('Cannot resolve ['.static::class."] by identifier \"$id\".", $e->getCode(), $e);
        }
    }

    protected static function newFactory(): ReceiptFactory
    {
        return ReceiptFactory::new();
    }
}
