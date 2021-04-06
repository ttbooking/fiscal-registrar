<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\DTO;

use TTBooking\FiscalRegistrar\Enums\Operation;

class EventPayload extends DataTransferObject
{
    public string $connection;

    public Operation $operation;

    public string $external_id;

    public ?string $internal_id;

    public ?Receipt $data;

    public ?Result $result;

    /**
     * Create a new event payload instance.
     *
     * @param  string  $connection
     * @param  Operation  $operation
     * @param  string  $external_id
     * @param  string|null  $internal_id
     * @param  Receipt|null  $data
     * @param  Result|null  $result
     * @return self
     */
    public static function new(
        string $connection,
        Operation $operation,
        string $external_id,
        string $internal_id = null,
        Receipt $data = null,
        Result $result = null
    ): self {
        return new self(compact('connection', 'operation', 'external_id', 'internal_id', 'data', 'result'));
    }
}
