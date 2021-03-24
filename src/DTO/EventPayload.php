<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\DTO;

class EventPayload extends DataTransferObject
{
    public string $connection;

    public string $operation;

    public string $externalId;

    public ?string $internalId;

    public ?Receipt $receipt;

    public ?Result $result;

    /**
     * Create a new event payload instance.
     *
     * @param  string  $connection
     * @param  string  $operation
     * @param  string  $externalId
     * @param  string|null  $internalId
     * @param  Receipt|null  $receipt
     * @param  Result|null  $result
     * @return self
     */
    public static function new(
        string $connection,
        string $operation,
        string $externalId,
        string $internalId = null,
        Receipt $receipt = null,
        Result $result = null
    ): self {
        return new self(compact('connection', 'operation', 'externalId', 'internalId', 'receipt', 'result'));
    }
}
