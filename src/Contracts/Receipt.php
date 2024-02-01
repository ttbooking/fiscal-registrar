<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\Contracts;

use TTBooking\FiscalRegistrar\Enums\Operation;
use TTBooking\FiscalRegistrar\Models\Receipt as Model;

interface Receipt extends StatefulFiscalRegistrar
{
    /**
     * @return $this
     */
    public function for(?string $connection = null): static;

    /**
     * @return $this
     */
    public function do(?Operation $operation = null): static;

    /**
     * @return $this
     */
    public function as(?string $id = null): static;

    /**
     * @return $this
     */
    public function with(string $key, mixed $value): static;

    public function save(): bool;

    public function clone(): static;

    public function delete(bool $force = false): bool;

    public function getModel(): Model;
}
