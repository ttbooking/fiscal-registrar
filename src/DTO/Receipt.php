<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\DTO;

use Illuminate\Support\Collection;

final class Receipt
{
    public Receipt\Client $client;

    public ?Receipt\Company $company;

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
     * @param  Receipt\Company|null  $company
     * @param  Receipt\AgentInfo|null  $agentInfo
     * @param  Receipt\SupplierInfo|null  $supplierInfo
     * @param  Collection|array  $items
     * @param  Collection|array  $payments
     * @param  Collection|array|null  $vats
     * @param  float|null  $total
     * @param  string|null  $additionalCheckProps
     * @param  string|null  $cashier
     * @param  Receipt\AdditionalUserProps|null  $additionalUserProps
     */
    public function __construct(
        Receipt\Client $client,
        Receipt\Company $company = null,
        Receipt\AgentInfo $agentInfo = null,
        Receipt\SupplierInfo $supplierInfo = null,
        $items = [],
        $payments = [],
        $vats = null,
        float $total = null,
        string $additionalCheckProps = null,
        string $cashier = null,
        Receipt\AdditionalUserProps $additionalUserProps = null
    ) {
        $this->client = $client;
        $this->company = $company;
        $this->agentInfo = $agentInfo;
        $this->supplierInfo = $supplierInfo;
        $this->items = collect($items);
        $this->total = $total ?? $this->items->sum('sum');
        $this->payments = collect($payments)->whenEmpty(
            fn (Collection $payments) => $payments->add(new Receipt\Payment(1, $this->total))
        );
        $this->vats = isset($vats) ? collect($vats) : $vats;
        $this->additionalCheckProps = $additionalCheckProps;
        $this->cashier = $cashier;
        $this->additionalUserProps = $additionalUserProps;
    }
}
