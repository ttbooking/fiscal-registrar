<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\DTO\Receipt;

use Spatie\DataTransferObject\Attributes\CastWith;
use Spatie\DataTransferObject\Casters\EnumCaster;
use TTBooking\FiscalRegistrar\DTO\Casters\RoundingCaster;
use TTBooking\FiscalRegistrar\DTO\DataTransferObject;
use TTBooking\FiscalRegistrar\Enums\PaymentMethod;
use TTBooking\FiscalRegistrar\Enums\PaymentObject;

final class Item extends DataTransferObject
{
    // 1030
    public string $name;

    // 1079
    #[CastWith(RoundingCaster::class)]
    public float|int $price;

    // 1023
    public int $quantity = 1;

    // 1043
    #[CastWith(RoundingCaster::class)]
    public float|int $sum;

    // 1197
    public ?string $measurement_unit = null;

    // 1162
    public ?string $nomenclature_code = null;

    // 1214
    #[CastWith(EnumCaster::class, PaymentMethod::class)]
    public PaymentMethod $payment_method = PaymentMethod::FullPrepayment;

    // 1212
    #[CastWith(EnumCaster::class, PaymentObject::class)]
    public PaymentObject $payment_object = PaymentObject::Commodity;

    public ?Item\Vat $vat = null;

    public ?AgentInfo $agent_info = null;

    public ?Item\SupplierInfo $supplier_info = null;

    // 1191
    public ?string $user_data = null;

    // 1229
    #[CastWith(RoundingCaster::class)]
    public float|int|null $excise = null;

    // 1230
    public ?string $country_code = null;

    // 1231
    public ?string $declaration_number = null;

    public function getVatSum(): float
    {
        return $this->vat?->sum ?? round(
            $this->sum - $this->sum / (1 + (float) $this->vat?->type->getRate()),
            2, PHP_ROUND_HALF_EVEN
        );
    }

    protected static function transformSum($sum, array $args)
    {
        return $sum ?? ($args['price'] ?? 0) * ($args['quantity'] ?? 1);
    }

    protected static function transformPaymentMethod($payment_method)
    {
        return $payment_method ?? PaymentMethod::FullPrepayment;
    }

    protected static function transformPaymentObject($payment_object)
    {
        return $payment_object ?? PaymentObject::Commodity;
    }
}
