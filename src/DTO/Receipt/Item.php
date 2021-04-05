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
    public int $quantity;

    // 1043
    /** @var float|int */
    public float $sum;

    // 1197
    public ?string $measurementUnit;

    // 1162
    public ?string $nomenclatureCode;

    // 1214
    public PaymentMethod $paymentMethod;

    // 1212
    public PaymentObject $paymentObject;

    public ?Item\VAT $vat;

    public ?AgentInfo $agentInfo;

    public ?Item\SupplierInfo $supplierInfo;

    // 1191
    public ?string $userData;

    // 1229
    /** @var float|int|null */
    public ?float $excise;

    // 1230
    public ?string $countryCode;

    // 1231
    public ?string $declarationNumber;

    /**
     * Item constructor.
     *
     * @param  string  $name
     * @param  float|int  $price
     * @param  int  $quantity
     * @param  float|int|null  $sum
     * @param  string|null  $measurementUnit
     * @param  string|null  $nomenclatureCode
     * @param  PaymentMethod|null  $paymentMethod
     * @param  PaymentObject|null  $paymentObject
     * @param  Item\VAT|null  $vat
     * @param  AgentInfo|null  $agentInfo
     * @param  Item\SupplierInfo|null  $supplierInfo
     * @param  string|null  $userData
     * @param  float|int|null  $excise
     * @param  string|null  $countryCode
     * @param  string|null  $declarationNumber
     * @return self
     */
    public static function new(
        string $name,
        float $price,
        int $quantity = 1,
        float $sum = null,
        string $measurementUnit = null,
        string $nomenclatureCode = null,
        PaymentMethod $paymentMethod = null,
        PaymentObject $paymentObject = null,
        Item\VAT $vat = null,
        AgentInfo $agentInfo = null,
        Item\SupplierInfo $supplierInfo = null,
        string $userData = null,
        float $excise = null,
        string $countryCode = null,
        string $declarationNumber = null
    ): self {
        return new self(compact(
            'name', 'price', 'quantity', 'measurementUnit', 'nomenclatureCode', 'vat',
            'agentInfo', 'supplierInfo', 'userData', 'excise', 'countryCode', 'declarationNumber'
        ) + [
            'sum' => $sum ?? $price * $quantity,
            'paymentMethod' => $paymentMethod ?? PaymentMethod::FullPrepayment(),
            'paymentObject' => $paymentObject ?? PaymentObject::Commodity(),
        ]);
    }
}
