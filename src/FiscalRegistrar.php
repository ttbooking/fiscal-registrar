<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar;

use Closure, RuntimeException;
use TTBooking\FiscalRegistrar\Models\Receipt;

final class FiscalRegistrar
{
    /** @var null|Closure(Receipt):string */
    private static ?Closure $idGenerator = null;

    /**
     * @param  null|Closure(Receipt):string  $idGenerator
     * @return void
     */
    public static function registerIdentifierGenerator(?Closure $idGenerator): void
    {
        self::$idGenerator = $idGenerator;
    }

    /**
     * @param  Receipt  $model
     * @return string
     *
     * @throws RuntimeException
     */
    public static function generateIdentifier(Receipt $model): string
    {
        if (! isset(self::$idGenerator)) {
            throw new RuntimeException('Receipt identifier generator is not set.');
        }

        return call_user_func(self::$idGenerator, $model);
    }
}
