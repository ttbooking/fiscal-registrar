<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\Jobs;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use TTBooking\FiscalRegistrar\Models\Receipt;

class SyncReceipts implements ShouldBeUnique, ShouldQueue
{
    use InteractsWithQueue, Queueable;

    protected int $olderThanMinutes;

    protected int $batchSize;

    /**
     * Create a new job instance.
     */
    public function __construct(int $olderThanMinutes = 5, int $batchSize = 1)
    {
        $this->olderThanMinutes = $olderThanMinutes;
        $this->batchSize = $batchSize;
    }

    /**
     * Execute the job.
     */
    public function handle(Receipt $receipt): void
    {
        $receipt->newQuery()

            // Take $batchSize unregistered receipts (TODO: replace with scope)
            ->where('state', Receipt::STATE_REGISTERED)

            // older than $olderThanMinutes minutes
            ->where(Receipt::UPDATED_AT, '<', Carbon::now()->subMinutes($this->olderThanMinutes)->toDateTimeString())

            // ordered by oldest first
            ->oldest()->take($this->batchSize)

            // and sync their status with remote service
            ->each(fn (Receipt $receipt) => $receipt->report());
    }

    /**
     * Get the tags that should be assigned to the job.
     *
     * @return string[]
     */
    public function tags(): array
    {
        return ['fiscal-registrar'];
    }
}
