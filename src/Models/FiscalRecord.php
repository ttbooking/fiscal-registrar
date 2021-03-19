<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\Models;

use Illuminate\Database\Eloquent\Model;
use TTBooking\FiscalRegistrar\DTO\Receipt;
use TTBooking\FiscalRegistrar\DTO\Result;
use TTBooking\FiscalRegistrar\Facades\FiscalRegistrar;

/**
 * @property string $connection
 * @property string $external_id
 * @property string $internal_id
 * @property Receipt $receipt
 * @property Result|null $result
 */
class FiscalRecord extends Model
{
    protected $table = 'fiscal_registry';

    protected $guarded = ['id'];

    public function report(): Result
    {
        return FiscalRegistrar::connection($this->connection)->report($this->internal_id);
    }
}
