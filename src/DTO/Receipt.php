<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\DTO;

use Illuminate\Support\Collection;

final class Receipt extends DataTransferObject
{
    public Receipt\Client $client;

    public ?Receipt\Company $company;

    public ?Receipt\AgentInfo $agent_info;

    public ?Receipt\SupplierInfo $supplier_info;

    public Receipt\ItemCollection $items;

    public Receipt\PaymentCollection $payments;

    public ?Receipt\VATCollection $vats;

    // 1020
    /** @var float|int */
    public float $total;

    // 1192
    public ?string $additional_check_props;

    // 1021
    public ?string $cashier;

    // 1084
    public ?Receipt\AdditionalUserProps $additional_user_props;

    /**
     * Receipt constructor.
     *
     * @param  Receipt\Client  $client
     * @param  Receipt\Company|null  $company
     * @param  Receipt\AgentInfo|null  $agent_info
     * @param  Receipt\SupplierInfo|null  $supplier_info
     * @param  array  $items
     * @param  array  $payments
     * @param  array|null  $vats
     * @param  float|int|null  $total
     * @param  string|null  $additional_check_props
     * @param  string|null  $cashier
     * @param  Receipt\AdditionalUserProps|null  $additional_user_props
     * @return self
     */
    public static function new(
        Receipt\Client $client,
        Receipt\Company $company = null,
        Receipt\AgentInfo $agent_info = null,
        Receipt\SupplierInfo $supplier_info = null,
        $items = [],
        $payments = [],
        $vats = null,
        float $total = null,
        string $additional_check_props = null,
        string $cashier = null,
        Receipt\AdditionalUserProps $additional_user_props = null
    ): self {
        return new self(compact(
            'client', 'company', 'agent_info', 'supplier_info', 'items', 'total', 'payments', 'vats',
            'additional_check_props', 'cashier', 'additional_user_props'
        ));
    }

    protected static function transformItems($items)
    {
        return new Receipt\ItemCollection($items);
    }

    protected static function transformTotal($total, array $parameters)
    {
        return $total ?? collect($parameters['items'] ?? [])->sum('sum');
    }

    protected static function transformPayments($payments, array $parameters)
    {
        return new Receipt\PaymentCollection(collect($payments)->whenEmpty(
            fn (Collection $payments) => $payments->add(Receipt\Payment::new($parameters['total'] ?? 0))
        )->all());
    }

    protected static function transformVats($vats)
    {
        return isset($vats) ? new Receipt\VATCollection($vats) : $vats;
    }
}
