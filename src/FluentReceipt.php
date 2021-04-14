<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar;

use Closure;
use Illuminate\Support\Str;
use RuntimeException;
use TTBooking\FiscalRegistrar\Enums\Operation;
use TTBooking\FiscalRegistrar\Exceptions\ResolverException;
use TTBooking\FiscalRegistrar\Models\Receipt;

class FluentReceipt implements Contracts\ReceiptFactory, Contracts\Receipt
{
    protected Receipt $model;

    /** @var null|Closure(Receipt):string */
    protected ?Closure $idGenerator;

    /**
     * @param  Receipt  $model
     * @param  null|Closure(Receipt):string  $idGenerator
     */
    final public function __construct(Receipt $model, Closure $idGenerator = null)
    {
        $this->model = $model;
        $this->idGenerator = $idGenerator;
    }

    public function for(string $connection = null): self
    {
        $this->model->checkState(Receipt::STATE_CREATED);

        if (! is_null($connection)) {
            $this->model->connection = $connection;
        }

        return $this;
    }

    public function do(Operation $operation = null): self
    {
        $this->model->checkState(Receipt::STATE_CREATED);

        if (! is_null($operation)) {
            $this->model->operation = $operation;
        }

        return $this;
    }

    public function as(string $id = null): self
    {
        $this->model->checkState(Receipt::STATE_CREATED);

        if (! is_null($id)) {
            $this->model->external_id = $id;
        }

        return $this;
    }

    public function with(string $key, $value): self
    {
        $this->model->checkState(Receipt::STATE_CREATED);

        $this->model->setAttribute($key, $value);

        return $this;
    }

    public function save(): bool
    {
        return $this->model->save();
    }

    public function clone(): self
    {
        return $this->newInstance($this->model->replicate(['state', 'external_id', 'internal_id', 'result']));
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

    public function make(DTO\Receipt $data): self
    {
        return $this->newInstance($this->model->newInstance(compact('data')));
    }

    public function resolve($id): self
    {
        try {
            return $this->newInstance($this->model->resolveRouteBinding($id, null, true));
        } catch (RuntimeException $e) {
            throw new ResolverException('Cannot resolve ['.static::class."] by identifier \"$id\".", $e->getCode(), $e);
        }
    }

    public function register(Operation $operation = null, string $externalId = null, DTO\Receipt $data = null): string
    {
        $externalId ??= $this->model->external_id ?? $this->generateIdentifier();

        return $this->model->register($operation, $externalId, $data);
    }

    public function report(string $id = null, bool $force = false): ?DTO\Result
    {
        return $this->model->report($id);
    }

    public function __call(string $method, array $parameters)
    {
        if (Str::startsWith($method, 'with')) {
            return $this->with(Str::snake(Str::after($method, 'with')), $parameters[0] ?? null);
        }
    }

    /**
     * @param  Receipt  $model
     * @return static
     */
    protected function newInstance(Receipt $model): self
    {
        return new static($model, $this->idGenerator);
    }

    protected function generateIdentifier(): ?string
    {
        return isset($this->idGenerator) ? call_user_func($this->idGenerator, $this->model) : null;
    }
}
