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
     * @param  mixed  $data
     * @return ReceiptFactory|ReceiptContract
     */
    function receipt($data = null)
    {
        // Pass-thru receipt contracts
        if ($data instanceof ReceiptContract || $data instanceof ReceiptFactory) {
            return $data;
        }

        // Return receipt factory if no parameters passed
        if (is_null($data)) {
            return Container::getInstance()->make(ReceiptFactory::class);
        }

        // Make new fluent receipt interface for given model
        if ($data instanceof Model) {
            return Container::getInstance()->make(ReceiptContract::class)->setModel($data);
        }

        // Try to convert array to receipt DTO
        if (is_array($data)) {
            $data = new DTO\Receipt($data);
        }

        // Make new receipt from receipt DTO
        if ($data instanceof DTO\Receipt) {
            return Receipt::make($data);
        }

        // Resolve receipt by its identifier
        return Receipt::resolve($data);
    }
}
