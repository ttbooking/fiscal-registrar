<?php

declare(strict_types=1);

use Illuminate\Container\Container;
use TTBooking\FiscalRegistrar\Contracts\Receipt as ReceiptContract;
use TTBooking\FiscalRegistrar\DTO;
use TTBooking\FiscalRegistrar\Models\Receipt;

if (! function_exists('receipt')) {
    /**
     * @param  mixed  $data
     * @return ReceiptContract
     */
    function receipt($data = null): ReceiptContract
    {
        if ($data instanceof ReceiptContract) {
            return $data;
        }

        /** @var Receipt $receipt */
        $receipt = Container::getInstance()->make(Receipt::class);

        if (is_array($data)) {
            $data = new DTO\Receipt($data);
        }

        if ($data instanceof DTO\Receipt) {
            $receipt->data = $data;
        } elseif (! is_null($data)) {
            $receipt = $receipt->resolve($data);
        }

        return $receipt;
    }
}
