<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\Support;

use Carbon\Carbon;
use Closure, RuntimeException;
use TTBooking\FiscalRegistrar\Models\Receipt;

final class Utils
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

    public static function updateStatus(int $olderThanMinutes = 5, int $batchSize = 1): void
    {
        Receipt::query()

            // Take $batchSize unregistered receipts (TODO: replace with scope)
            ->where('state', Receipt::STATE_REGISTERED)

            // older than $olderThanMinutes minutes
            ->where(Receipt::UPDATED_AT, '>', Carbon::now()->subMinutes($olderThanMinutes)->toDateTimeString())

            // ordered by oldest first
            ->oldest()->take($batchSize)

            // and sync their status with remote service
            ->each(fn (Receipt $receipt) => $receipt->report());
    }
}
