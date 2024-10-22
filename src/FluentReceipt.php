<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar;

use Illuminate\Support\Str;
use TTBooking\FiscalRegistrar\Enums\Operation;
use TTBooking\FiscalRegistrar\Exceptions\ResolverException;
use TTBooking\FiscalRegistrar\Models\Receipt;

class FluentReceipt implements Contracts\Receipt, Contracts\ReceiptFactory
{
    final public function __construct(protected Receipt $model) {}

    public function for(?string $connection = null): static
    {
        if ($this->model->state !== Receipt::STATE_CREATED) {
            throw new Exceptions\StateException('Receipt has invalid state for operation.');
        }

        if (! is_null($connection)) {
            $this->model->connection = $connection;
        }

        return $this;
    }

    public function do(?Operation $operation = null): static
    {
        if ($this->model->state !== Receipt::STATE_CREATED) {
            throw new Exceptions\StateException('Receipt has invalid state for operation.');
        }

        if (! is_null($operation)) {
            $this->model->operation = $operation;
        }

        return $this;
    }

    public function as(?string $id = null): static
    {
        if ($this->model->state !== Receipt::STATE_CREATED) {
            throw new Exceptions\StateException('Receipt has invalid state for operation.');
        }

        if (! is_null($id)) {
            $this->model->external_id = $id;
        }

        return $this;
    }

    public function with(string $key, mixed $value): static
    {
        if ($this->model->state !== Receipt::STATE_CREATED) {
            throw new Exceptions\StateException('Receipt has invalid state for operation.');
        }

        $this->model->setAttribute($key, $value);

        return $this;
    }

    public function save(): bool
    {
        return $this->model->save();
    }

    public function clone(): static
    {
        return new static($this->model->replicate([
            'state', 'external_id', 'internal_id', 'result',
            'fn_number', 'fiscal_document_number', 'fiscal_document_attribute', 'total',
        ]));
    }

    public function delete(bool $force = false): bool
    {
        if ($force || $this->model->state === Receipt::STATE_CREATED) {
            return (bool) $this->model->delete();
        }

        return false;
    }

    public function getModel(): Receipt
    {
        return $this->model;
    }

    public function make(DTO\Receipt $payload): static
    {
        return new static($this->model->newInstance(compact('payload')));
    }

    public function resolve(mixed $id): static
    {
        if (is_null($receipt = $this->model->resolveRouteBinding($id, null, true))) {
            throw new ResolverException('Cannot resolve ['.static::class."] by identifier \"$id\".");
        }

        return new static($receipt);
    }

    public function register(?Operation $operation = null, ?string $externalId = null, ?DTO\Receipt $payload = null): string
    {
        return $this->model->register($operation, $externalId, $payload);
    }

    public function report(?string $id = null, bool $force = false): ?DTO\Result
    {
        return $this->model->report($id, $force);
    }

    /**
     * @param  array<mixed>  $parameters
     * @return $this|null
     */
    public function __call(string $method, array $parameters)
    {
        if (Str::startsWith($method, 'with')) {
            return $this->with(Str::snake(Str::after($method, 'with')), $parameters[0] ?? null);
        }

        return null;
    }
}
