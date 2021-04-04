<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\Concerns;

use TTBooking\FiscalRegistrar\DTO;
use TTBooking\FiscalRegistrar\Enums\Operation;
use TTBooking\FiscalRegistrar\Facades\FiscalRegistrar;

trait FluentOperation
{
    public function make(DTO\Receipt $data)
    {
        $this->data = $data;

        return $this;
    }

    public function for(string $connection)
    {
        $this->connection = $connection;

        return $this;
    }

    public function do(Operation $operation)
    {
        $this->operation = $operation;

        return $this;
    }

    public function as(string $id)
    {
        $this->external_id = $id;

        return $this;
    }

    public function clone()
    {
        return $this->replicate(['external_id', 'internal_id', 'result']);
    }

    public function sell(string $externalId = null, DTO\Receipt $data = null): DTO\Result
    {
        return FiscalRegistrar::connection($this->connection)
            ->{__FUNCTION__}($externalId ?? $this->external_id, $data ?? $this->data);
    }

    public function sellRefund(string $externalId = null, DTO\Receipt $data = null): DTO\Result
    {
        return FiscalRegistrar::connection($this->connection)
            ->{__FUNCTION__}($externalId ?? $this->external_id, $data ?? $this->data);
    }

    public function buy(string $externalId = null, DTO\Receipt $data = null): DTO\Result
    {
        return FiscalRegistrar::connection($this->connection)
            ->{__FUNCTION__}($externalId ?? $this->external_id, $data ?? $this->data);
    }

    public function buyRefund(string $externalId = null, DTO\Receipt $data = null): DTO\Result
    {
        return FiscalRegistrar::connection($this->connection)
            ->{__FUNCTION__}($externalId ?? $this->external_id, $data ?? $this->data);
    }

    public function report(string $id = null): DTO\Result
    {
        return FiscalRegistrar::connection($this->connection)
            ->{__FUNCTION__}($id ?? $this->internal_id);
    }
}
