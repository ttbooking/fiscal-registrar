<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\DTO;

use Illuminate\Support\Collection;

final class Receipt
{
    public Receipt\Client $client;

    public Receipt\Company $company;

    public ?Receipt\AgentInfo $agentInfo;

    public ?Receipt\SupplierInfo $supplierInfo;

    /** @var Collection<Receipt\Item> */
    public Collection $items;

    /** @var Collection<Receipt\Payment> */
    public Collection $payments;

    /** @var Collection<Receipt\VAT>|null */
    public ?Collection $vats;

    // 1020
    public float $total;

    // 1192
    public ?string $additionalCheckProps;

    // 1021
    public ?string $cashier;

    // 1084
    public ?Receipt\AdditionalUserProps $additionalUserProps;

    /**
     * Receipt constructor.
     *
     * @param  Receipt\Client  $client
     * @param  Receipt\Company  $company
     * @param  Receipt\AgentInfo|null  $agentInfo
     * @param  Receipt\SupplierInfo|null  $supplierInfo
     * @param  Collection|array  $items
     * @param  Collection|array  $payments
     * @param  Collection|array|null  $vats
     * @param  float  $total
     * @param  string|null  $additionalCheckProps
     * @param  string|null  $cashier
     * @param  Receipt\AdditionalUserProps|null  $additionalUserProps
     */
    public function __construct(
        Receipt\Client $client,
        Receipt\Company $company,
        Receipt\AgentInfo $agentInfo = null,
        Receipt\SupplierInfo $supplierInfo = null,
        $items = [],
        $payments = [],
        $vats = null,
        float $total = 0,
        string $additionalCheckProps = null,
        string $cashier = null,
        Receipt\AdditionalUserProps $additionalUserProps = null
    ) {
        $this->client = $client;
        $this->company = $company;
        $this->agentInfo = $agentInfo;
        $this->supplierInfo = $supplierInfo;
        $this->items = collect($items);
        $this->payments = collect($payments);
        $this->vats = isset($vats) ? collect($vats) : $vats;
        $this->total = $total;
        $this->additionalCheckProps = $additionalCheckProps;
        $this->cashier = $cashier;
        $this->additionalUserProps = $additionalUserProps;
    }
}
