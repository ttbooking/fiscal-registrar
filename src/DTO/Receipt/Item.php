<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\DTO\Receipt;

use Spatie\LaravelData\Attributes\WithCast;
use TTBooking\FiscalRegistrar\DTO\Casters\RoundingCaster;
use TTBooking\FiscalRegistrar\DTO\DataTransferObject;
use TTBooking\FiscalRegistrar\Enums\PaymentMethod;
use TTBooking\FiscalRegistrar\Enums\PaymentObject;

final class Item extends DataTransferObject
{
    // 1043
    #[WithCast(RoundingCaster::class)]
    public float|int $sum;

    public function __construct(
        // 1030
        public string $name,

        // 1079
        #[WithCast(RoundingCaster::class)]
        public float|int $price,

        // 1023
        public int $quantity = 1,

        float|int|null $sum = null,

        // 1197
        public ?string $measurement_unit = null,

        // 1162
        public ?string $nomenclature_code = null,

        // 1214
        public PaymentMethod $payment_method = PaymentMethod::FullPrepayment,

        // 1212
        public PaymentObject $payment_object = PaymentObject::Commodity,

        public ?Item\Vat $vat = null,

        public ?AgentInfo $agent_info = null,

        public ?Item\SupplierInfo $supplier_info = null,

        // 1191
        public ?string $user_data = null,

        // 1229
        #[WithCast(RoundingCaster::class)]
        public float|int|null $excise = null,

        // 1230
        public ?string $country_code = null,

        // 1231
        public ?string $declaration_number = null,
    ) {
        $this->sum = round((float) ($sum ?? $this->price * $this->quantity), 2, PHP_ROUND_HALF_EVEN);
    }

    public function getVatSum(): float
    {
        return $this->vat->sum ?? round(
            $this->sum - $this->sum / (1 + (float) $this->vat?->type->getRate()),
            2, PHP_ROUND_HALF_EVEN
        );
    }

    /**
     * @param  array<string, mixed>  $properties
     * @return array<string, mixed>
     */
    public static function prepareForPipeline(array $properties): array
    {
        $properties['sum'] ??= ($properties['price'] ?? 0) * ($properties['quantity'] ?? 1);
        $properties['payment_method'] ??= PaymentMethod::FullPrepayment;
        $properties['payment_object'] ??= PaymentObject::Commodity;

        return $properties;
    }
}
