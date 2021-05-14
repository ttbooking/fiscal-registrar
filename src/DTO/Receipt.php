<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\DTO;

use Spatie\DataTransferObject\Attributes\CastWith;
use TTBooking\FiscalRegistrar\DTO\Casters\RoundingCaster;
use TTBooking\FiscalRegistrar\DTO\Receipt\Vats;
use TTBooking\FiscalRegistrar\Enums\VatType;

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

    public function getVats(): Vats
    {
        if ($this->vats) {
            return $this->vats;
        }

        $vats = new Vats;
        foreach ($this->items as $item) {
            switch ($item->vat?->type ?? VatType::None()) {
                case VatType::VAT20():
                case VatType::VAT18():
                    $vats->vat20 += $item->getVatSum();
                    break;
                case VatType::VAT10():
                    $vats->vat10 += $item->getVatSum();
                    break;
                case VatType::VAT0():
                    $vats->with_vat0 += $item->sum;
                    break;
                case VatType::None():
                    $vats->without_vat += $item->sum;
                    break;
                case VatType::VAT120():
                case VatType::VAT118():
                    $vats->vat120 += $item->getVatSum();
                    break;
                case VatType::VAT110():
                    $vats->vat110 += $item->getVatSum();
            }
        }

        return $vats;
    }

    protected static function transformTotal($total, array $args): float
    {
        return $total ?? collect($args['items'] ?? [])->sum('sum');
    }

    protected static function transformPayments($payments, array $args)
    {
        return $payments ?? ['electronic' => self::transformTotal($args['total'] ?? null, $args)];
    }
}
