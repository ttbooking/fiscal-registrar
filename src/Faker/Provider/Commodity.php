<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\Faker\Provider;

use Faker\Provider\Base;

class Commodity extends Base
{
    /** @var string[] */
    protected static array $formats = [
        '{{commodityName}}',
        '{{commodityCharacteristic1}} {{commodityName}}',
        '{{commodityCharacteristic1}} {{commodityCharacteristic2}} {{commodityName}}',
    ];

    /** @var string[] */
    protected static array $commodityNames = [];

    /** @var string[] */
    protected static array $commodityCharacteristics = [];

    public function commodity(): string
    {
        $format = static::randomElement(static::$formats);

        return $this->generator->parse($format);
    }

    public static function commodityName(): string
    {
        return static::randomElement(static::$commodityNames);
    }

    public static function commodityCharacteristic1(): string
    {
        return static::randomElement(static::$commodityCharacteristics);
    }

    public static function commodityCharacteristic2(): string
    {
        return static::randomElement(static::$commodityCharacteristics);
    }
}
