<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\DTO;

use Illuminate\Support\Collection;
use Spatie\LaravelData\Attributes\WithCast;
use TTBooking\FiscalRegistrar\DTO\Casters\RoundingCaster;
use TTBooking\FiscalRegistrar\Enums\VatType;

final class Receipt extends DataTransferObject
{
    /** @var Collection<int, Receipt\Item> */
    public Collection $items;

    public Receipt\Payments $payments;

    // 1020
    #[WithCast(RoundingCaster::class)]
    public float|int $total;

    /**
     * @param  Collection<int, Receipt\Item>|Receipt\Item[]  $items
     */
    public function __construct(
        public Receipt\Client $client,

        public Receipt\Company $company = new Receipt\Company,

        public ?Receipt\AgentInfo $agent_info = null,

        public ?Receipt\SupplierInfo $supplier_info = null,

        Collection|array $items = new Collection,

        ?Receipt\Payments $payments = null,

        public ?Receipt\Vats $vats = null,

        float|int|null $total = null,

        // 1192
        public ?string $additional_check_props = null,

        // 1021
        public ?string $cashier = null,

        // 1084
        public ?Receipt\AdditionalUserProps $additional_user_props = null,
    ) {
        $this->items = Collection::make($items);
        $this->total = round((float) ($total ?? $this->items->sum('sum')), 2, PHP_ROUND_HALF_EVEN);
        $this->payments = $payments ?? new Receipt\Payments(electronic: $this->total);
    }

    public function getVats(): Receipt\Vats
    {
        if ($this->vats) {
            return $this->vats;
        }

        $vats = new Receipt\Vats;
        foreach ($this->items as $item) {
            switch ($item->vat->type ?? VatType::None) {
                case VatType::VAT22:
                case VatType::VAT20:
                case VatType::VAT18:
                    $vats->vat22 += $item->getVatSum();
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
                case VatType::VAT122:
                case VatType::VAT120:
                case VatType::VAT118:
                    $vats->vat122 += $item->getVatSum();
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
     * @param  array<string, mixed>  $properties
     * @return array<string, mixed>
     */
    public static function prepareForPipeline(array $properties): array
    {
        $properties['company'] ??= [];
        $properties['total'] = (float) ($properties['total'] ?? collect($properties['items'] ?? [])->sum(
            static fn ($item) => data_get($item, 'sum')
                ?? (data_get($item, 'price') ?? 0) * (data_get($item, 'quantity') ?? 1)
        ));
        $properties['payments'] ??= ['electronic' => $properties['total']];

        return $properties;
    }
}
