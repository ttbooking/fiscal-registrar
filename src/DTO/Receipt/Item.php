<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\DTO\Receipt;

use TTBooking\FiscalRegistrar\DTO\DataTransferObject;

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

    // 1214 (enum)
    public string $paymentMethod;

    // 1212 (enum)
    public string $paymentObject;

    public ?Item\VAT $vat;

    public ?AgentInfo $agentInfo;

    public ?Item\SupplierInfo $supplierInfo;

    // 1191
    public ?string $userData;

    // 1229
    public ?float $excise;

    // 1230
    public ?string $countryCode;

    // 1231
    public ?string $declarationNumber;

    /**
     * Item constructor.
     *
     * @param  string  $name
     * @param  float  $price
     * @param  int  $quantity
     * @param  float|null  $sum
     * @param  string|null  $measurementUnit
     * @param  string|null  $nomenclatureCode
     * @param  string  $paymentMethod
     * @param  string  $paymentObject
     * @param  Item\VAT|null  $vat
     * @param  AgentInfo|null  $agentInfo
     * @param  Item\SupplierInfo|null  $supplierInfo
     * @param  string|null  $userData
     * @param  float|null  $excise
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
        string $paymentMethod = 'full_prepayment',
        string $paymentObject = 'commodity',
        Item\VAT $vat = null,
        AgentInfo $agentInfo = null,
        Item\SupplierInfo $supplierInfo = null,
        string $userData = null,
        float $excise = null,
        string $countryCode = null,
        string $declarationNumber = null
    ): self {
        /*$this->name = $name;
        $this->price = $price;
        $this->quantity = $quantity;
        $this->sum = $sum ?? $price * $quantity;
        $this->measurementUnit = $measurementUnit;
        $this->nomenclatureCode = $nomenclatureCode;
        $this->paymentMethod = $paymentMethod;
        $this->paymentObject = $paymentObject;
        $this->vat = $vat;
        $this->agentInfo = $agentInfo;
        $this->supplierInfo = $supplierInfo;
        $this->userData = $userData;
        $this->excise = $excise;
        $this->countryCode = $countryCode;
        $this->declarationNumber = $declarationNumber;*/

        return new self(compact(
            'name', 'price', 'quantity', 'measurementUnit', 'nomenclatureCode',
            'paymentMethod', 'paymentObject', 'vat', 'agentInfo', 'supplierInfo', 'userData',
            'excise', 'countryCode', 'declarationNumber'
        ) + ['sum' => $sum ?? $price * $quantity]);
    }
}
