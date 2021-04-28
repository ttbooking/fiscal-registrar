<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\DTO;

use Illuminate\Support\Collection;
use TTBooking\FiscalRegistrar\Enums\PaymentType;

final class Receipt extends DataTransferObject
{
    public Receipt\Client $client;

    public ?Receipt\Company $company = null;

    public ?Receipt\AgentInfo $agent_info = null;

    public ?Receipt\SupplierInfo $supplier_info = null;

    /** @var Receipt\ItemCollection|Receipt\Item[] */
    public Receipt\ItemCollection $items;

    // 1031, 1081, 1215, 1216, 1217
    /** @var array<int, float|int> */
    public array $payments = [];

    /** @var Receipt\VATCollection|Receipt\VAT[]|null */
    public ?Receipt\VATCollection $vats = null;

    // 1020
    public float|int|null $total = null;

    // 1192
    public ?string $additional_check_props = null;

    // 1021
    public ?string $cashier = null;

    // 1084
    public ?Receipt\AdditionalUserProps $additional_user_props = null;

    protected static function transformTotal($total, array $args): float
    {
        return $total ?? collect($args['items'] ?? [])->sum('sum');
    }

    protected static function transformPayments($payments, array $args): array
    {
        return empty($payments)
            ? [PaymentType::Electronic => self::transformTotal($args['total'] ?? null, $args)]
            : $payments;
    }
}
