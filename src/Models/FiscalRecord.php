<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\Models;

use Illuminate\Database\Eloquent\Model;
use TTBooking\FiscalRegistrar\Facades\FiscalRegistrar;

/**
 * @property string $connection
 * @property string $external_id
 * @property string $internal_id
 */
class FiscalRecord extends Model
{
    protected $table = 'fiscal_registry';

    public function report(): object
    {
        return FiscalRegistrar::connection($this->connection)->report($this->internal_id);
    }
}
