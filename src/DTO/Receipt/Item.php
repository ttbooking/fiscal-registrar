<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\DTO\Receipt;

use TTBooking\FiscalRegistrar\DTO\DataTransferObject;
use TTBooking\FiscalRegistrar\Enums\PaymentMethod;
use TTBooking\FiscalRegistrar\Enums\PaymentObject;

final class Item extends DataTransferObject
{
    // 1030
    public string $name;

    // 1079
    /** @var float|int */
    public float $price;

    // 1023
    public int $quantity = 1;

    // 1043
    /** @var float|int */
    public float $sum;

    // 1197
    public ?string $measurement_unit;

    // 1162
    public ?string $nomenclature_code;

    // 1214
    public PaymentMethod $payment_method;

    // 1212
    public PaymentObject $payment_object;

    public ?Item\VAT $vat;

    public ?AgentInfo $agent_info;

    public ?Item\SupplierInfo $supplier_info;

    // 1191
    public ?string $user_data;

    // 1229
    /** @var float|int|null */
    public ?float $excise;

    // 1230
    public ?string $country_code;

    // 1231
    public ?string $declaration_number;

    /**
     * Item constructor.
     *
     * @param  string  $name
     * @param  float|int  $price
     * @param  int  $quantity
     * @param  float|int|null  $sum
     * @param  string|null  $measurement_unit
     * @param  string|null  $nomenclature_code
     * @param  PaymentMethod|null  $payment_method
     * @param  PaymentObject|null  $payment_object
     * @param  Item\VAT|null  $vat
     * @param  AgentInfo|null  $agent_info
     * @param  Item\SupplierInfo|null  $supplier_info
     * @param  string|null  $user_data
     * @param  float|int|null  $excise
     * @param  string|null  $country_code
     * @param  string|null  $declaration_number
     * @return self
     */
    public static function new(
        string $name,
        float $price,
        int $quantity = 1,
        float $sum = null,
        string $measurement_unit = null,
        string $nomenclature_code = null,
        PaymentMethod $payment_method = null,
        PaymentObject $payment_object = null,
        Item\VAT $vat = null,
        AgentInfo $agent_info = null,
        Item\SupplierInfo $supplier_info = null,
        string $user_data = null,
        float $excise = null,
        string $country_code = null,
        string $declaration_number = null
    ): self {
        return new self(compact(
            'name', 'price', 'quantity', 'sum', 'measurement_unit', 'nomenclature_code', 'payment_method', 'payment_object',
            'vat', 'agent_info', 'supplier_info', 'user_data', 'excise', 'country_code', 'declaration_number'
        ));
    }

    protected static function transformSum($sum, array $parameters)
    {
        return $sum ?? ($parameters['price'] ?? 0) * ($parameters['quantity'] ?? 1);
    }

    protected static function transformPaymentMethod($payment_method): PaymentMethod
    {
        return isset($payment_method) ? new PaymentMethod($payment_method) : PaymentMethod::FullPrepayment();
    }

    protected static function transformPaymentObject($payment_object): PaymentObject
    {
        return isset($payment_object) ? new PaymentObject($payment_object) : PaymentObject::Commodity();
    }
}
