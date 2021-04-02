<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\DTO;

use Illuminate\Support\Collection;

final class Receipt extends DataTransferObject
{
    public Receipt\Client $client;

    public ?Receipt\Company $company;

    public ?Receipt\AgentInfo $agentInfo;

    public ?Receipt\SupplierInfo $supplierInfo;

    public Receipt\ItemCollection $items;

    public Receipt\PaymentCollection $payments;

    public ?Receipt\VATCollection $vats;

    // 1020
    /** @var float|int */
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
     * @param  array  $items
     * @param  array  $payments
     * @param  array|null  $vats
     * @param  float|int|null  $total
     * @param  string|null  $additionalCheckProps
     * @param  string|null  $cashier
     * @param  Receipt\AdditionalUserProps|null  $additionalUserProps
     * @return self
     */
    public static function new(
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
    ): self {
        return new self([
            'client' => $client,
            'company' => $company,
            'agentInfo' => $agentInfo,
            'supplierInfo' => $supplierInfo,
            'items' => new Receipt\ItemCollection($items),
            'total' => $total ??= collect($items)->sum('sum'),
            'payments' => new Receipt\PaymentCollection(collect($payments)->whenEmpty(
                fn (Collection $payments) => $payments->add(new Receipt\Payment(['sum' => $total]))
            )->all()),
            'vats' => isset($vats) ? new Receipt\VATCollection($vats) : $vats,
            'additionalCheckProps' => $additionalCheckProps,
            'cashier' => $cashier,
            'additionalUserProps' => $additionalUserProps,
        ]);
    }
}
