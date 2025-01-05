<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\DTO;

use Illuminate\Support\Collection;
use Spatie\DataTransferObject\Attributes\CastWith;
use Spatie\DataTransferObject\Casters\ArrayCaster;
use TTBooking\FiscalRegistrar\DTO\Casters\RoundingCaster;
use TTBooking\FiscalRegistrar\DTO\Receipt\Vats;
use TTBooking\FiscalRegistrar\Enums\VatType;

final class Receipt extends DataTransferObject
{
    public Receipt\Client $client;

    public Receipt\Company $company;

    public ?Receipt\AgentInfo $agent_info = null;

    public ?Receipt\SupplierInfo $supplier_info = null;

    /** @var Collection<int, Receipt\Item> */
    #[CastWith(ArrayCaster::class, itemType: Receipt\Item::class)]
    public Collection $items;

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
            switch ($item->vat?->type ?? VatType::None) {
                case VatType::VAT20:
                case VatType::VAT18:
                    $vats->vat20 += $item->getVatSum();
                    break;
                case VatType::VAT10:
                    $vats->vat10 += $item->getVatSum();
                    break;
                case VatType::VAT0:
                    $vats->with_vat0 += $item->sum;
                    break;
                case VatType::None:
                    $vats->without_vat += $item->sum;
                    break;
                case VatType::VAT120:
                case VatType::VAT118:
                    $vats->vat120 += $item->getVatSum();
                    break;
                case VatType::VAT110:
                    $vats->vat110 += $item->getVatSum();
                    break;
                case VatType::VAT5:
                    $vats->vat5 += $item->getVatSum();
                    break;
                case VatType::VAT7:
                    $vats->vat7 += $item->getVatSum();
                    break;
                case VatType::VAT105:
                    $vats->vat105 += $item->getVatSum();
                    break;
                case VatType::VAT107:
                    $vats->vat107 += $item->getVatSum();
            }
        }

        return $vats;
    }

    /**
     * @param  array<mixed>|null  $company
     * @return array<mixed>
     */
    protected static function transformCompany(?array $company): array
    {
        return $company ?? [];
    }

    /**
     * @param  array{items?: ?list<array{sum: float}>}  $args
     */
    protected static function transformTotal(?float $total, array $args): float
    {
        return (float) ($total ?? collect($args['items'] ?? [])->sum('sum'));
    }

    /**
     * @template T of array{electronic: float}
     *
     * @param  T|null  $payments
     * @param  array{total?: ?float}  $args
     * @return T
     */
    protected static function transformPayments(?array $payments, array $args): array
    {
        return $payments ?? ['electronic' => self::transformTotal($args['total'] ?? null, $args)];
    }
}
