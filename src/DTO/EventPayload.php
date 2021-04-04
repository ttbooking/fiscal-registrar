<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\DTO;

use TTBooking\FiscalRegistrar\Enums\Operation;

class EventPayload extends DataTransferObject
{
    public string $connection;

    public Operation $operation;

    public string $externalId;

    public ?string $internalId;

    public ?Receipt $data;

    public ?Result $result;

    /**
     * Create a new event payload instance.
     *
     * @param  string  $connection
     * @param  Operation  $operation
     * @param  string  $externalId
     * @param  string|null  $internalId
     * @param  Receipt|null  $data
     * @param  Result|null  $result
     * @return self
     */
    public static function new(
        string $connection,
        Operation $operation,
        string $externalId,
        string $internalId = null,
        Receipt $data = null,
        Result $result = null
    ): self {
        return new self(compact('connection', 'operation', 'externalId', 'internalId', 'data', 'result'));
    }
}
