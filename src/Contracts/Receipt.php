<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\Contracts;

use TTBooking\FiscalRegistrar\Enums\Operation;
use TTBooking\FiscalRegistrar\Models\Receipt as Model;

interface Receipt extends StatefulFiscalRegistrar
{
    /**
     * @param  string  $connection
     * @return $this
     */
    public function for(string $connection): self;

    /**
     * @param  Operation  $operation
     * @return $this
     */
    public function do(Operation $operation): self;

    /**
     * @param  string  $id
     * @return $this
     */
    public function as(string $id): self;

    /**
     * @param  string  $key
     * @param  mixed  $value
     * @return $this
     */
    public function with(string $key, $value): self;

    /**
     * @return bool
     */
    public function save(): bool;

    /**
     * @return static
     */
    public function clone(): self;

    /**
     * @return bool
     */
    public function delete(): bool;

    /**
     * @return Model
     */
    public function getModel(): Model;
}
