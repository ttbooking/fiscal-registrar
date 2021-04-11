<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar;

use Illuminate\Support\Str;
use RuntimeException;
use TTBooking\FiscalRegistrar\Enums\Operation;
use TTBooking\FiscalRegistrar\Exceptions\ResolverException;
use TTBooking\FiscalRegistrar\Models\Receipt;

class FluentReceipt implements Contracts\ReceiptFactory, Contracts\Receipt
{
    protected Receipt $model;

    final public function __construct(Receipt $model)
    {
        $this->model = $model;
    }

    public function for(string $connection = null): self
    {
        if (! is_null($connection)) {
            $this->model->connection = $connection;
        }

        return $this;
    }

    public function do(Operation $operation = null): self
    {
        if (! is_null($operation)) {
            $this->model->operation = $operation;
        }

        return $this;
    }

    public function as(string $id = null): self
    {
        if (! is_null($id)) {
            $this->model->external_id = $id;
        }

        return $this;
    }

    public function with(string $key, $value): self
    {
        $this->model->setAttribute($key, $value);

        return $this;
    }

    public function save(): bool
    {
        return $this->model->save();
    }

    public function clone(): self
    {
        return new static($this->model->replicate(['external_id', 'internal_id', 'result']));
    }

    public function delete(): bool
    {
        return (bool) $this->model->delete();
    }

    public function getModel(): Receipt
    {
        return $this->model;
    }

    public function make(DTO\Receipt $data): self
    {
        return new static($this->model->newInstance(compact('data')));
    }

    public function resolve($id): self
    {
        try {
            return new static($this->model->resolveRouteBinding($id, null, true));
        } catch (RuntimeException $e) {
            throw new ResolverException('Cannot resolve ['.static::class."] by identifier \"$id\".", $e->getCode(), $e);
        }
    }

    public function register(Operation $operation = null, string $externalId = null, DTO\Receipt $data = null): DTO\Result
    {
        return $this->model->register($operation, $externalId, $data);
    }

    public function report(string $id = null): DTO\Result
    {
        return $this->model->report($id);
    }

    public function __call(string $method, array $parameters)
    {
        if (Str::startsWith($method, 'with')) {
            return $this->with(Str::snake(Str::after($method, 'with')), $parameters[0] ?? null);
        }
    }
}
