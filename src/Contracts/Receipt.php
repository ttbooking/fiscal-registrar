<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\Contracts;

use TTBooking\FiscalRegistrar\Enums\Operation;
use TTBooking\FiscalRegistrar\Models\Receipt as Model;

interface Receipt extends StatefulFiscalRegistrar
{
    /**
     * @param  string|null  $connection
     * @return $this
     */
    public function for(string $connection = null): static;

    /**
     * @param  Operation|null  $operation
     * @return $this
     */
    public function do(Operation $operation = null): static;

    /**
     * @param  string|null  $id
     * @return $this
     */
    public function as(string $id = null): static;

    /**
     * @param  string  $key
     * @param  mixed  $value
     * @return $this
     */
    public function with(string $key, $value): static;

    /**
     * @return bool
     */
    public function save(): bool;

    /**
     * @return static
     */
    public function clone(): static;

    /**
     * @param  bool  $force
     * @return bool
     */
    public function delete(bool $force = false): bool;

    /**
     * @return Model
     */
    public function getModel(): Model;
}
