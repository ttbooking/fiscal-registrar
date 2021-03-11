<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\DTO\Register\Request\Receipt;

final class Item
{
    // 1030
    public string $name;

    // 1079
    public float $price;

    // 1023
    public int $quantity;

    // 1043
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
     * @param  float  $sum
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
     */
    public function __construct(
        string $name,
        float $price,
        int $quantity,
        float $sum,
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
    ) {
        $this->name = $name;
        $this->price = $price;
        $this->quantity = $quantity;
        $this->sum = $sum;
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
        $this->declarationNumber = $declarationNumber;
    }
}
