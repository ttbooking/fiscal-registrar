<?php

declare(strict_types=1);

use Illuminate\Container\Container;
use TTBooking\FiscalRegistrar\Contracts\Receipt as ReceiptContract;
use TTBooking\FiscalRegistrar\Contracts\ReceiptFactory;
use TTBooking\FiscalRegistrar\DTO;
use TTBooking\FiscalRegistrar\Facades\Receipt;
use TTBooking\FiscalRegistrar\Models\Receipt as Model;

if (! function_exists('receipt')) {
    /**
     * @param  ReceiptFactory|ReceiptContract|array<mixed>|Model|DTO\Receipt|scalar|null  $data
     * @return ReceiptFactory|ReceiptContract
     */
    function receipt(mixed $data = null): ReceiptFactory|ReceiptContract
    {
        // Pass-thru receipt contracts
        if ($data instanceof ReceiptFactory || $data instanceof ReceiptContract) {
            return $data;
        }

        // Return receipt factory if no parameters passed
        if (is_null($data)) {
            /** @var ReceiptFactory */
            return Container::getInstance()->make(ReceiptFactory::class);
        }

        // Try to convert array to receipt DTO
        if (is_array($data)) {
            $data = new DTO\Receipt($data);
        }

        // Extract receipt DTO from model
        elseif ($data instanceof Model) {
            $data = $data->payload;
        }

        // Make new receipt from receipt DTO
        if ($data instanceof DTO\Receipt) {
            return Receipt::make($data);
        }

        // Resolve receipt by its identifier
        return Receipt::resolve($data);
    }
}
