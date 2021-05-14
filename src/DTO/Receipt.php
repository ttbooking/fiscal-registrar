<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\DTO;

use Spatie\DataTransferObject\Attributes\CastWith;
use TTBooking\FiscalRegistrar\DTO\Casters\RoundingCaster;

final class Receipt extends DataTransferObject
{
    public Receipt\Client $client;

    public ?Receipt\Company $company = null;

    public ?Receipt\AgentInfo $agent_info = null;

    public ?Receipt\SupplierInfo $supplier_info = null;

    /** @var Receipt\ItemCollection|Receipt\Item[] */
    public Receipt\ItemCollection $items;

    public Receipt\Payments $payments;

    public ?Receipt\Vats $vats = null;

    // 1020
    #[CastWith(RoundingCaster::class)]
    public float|int $total;

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

    protected static function transformPayments($payments, array $args)
    {
        return $payments ?? ['electronic' => self::transformTotal($args['total'] ?? null, $args)];
    }
}
