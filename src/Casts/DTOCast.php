<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Spatie\DataTransferObject\DataTransferObject;

class DTOCast implements CastsAttributes
{
    /** @var class-string<DataTransferObject> */
    protected string $dtoClass;

    /**
     * @param  class-string<DataTransferObject>  $dtoClass
     * @return void
     */
    public function __construct(string $dtoClass)
    {
        $this->dtoClass = $dtoClass;
    }

    public function get($model, string $key, $value, array $attributes)
    {
        return isset($value) ? new $this->dtoClass(json_decode($value, true)) : null;
    }

    public function set($model, string $key, $value, array $attributes)
    {
        if (is_null($value)) {
            return null;
        }

        if (! $value instanceof DataTransferObject) {
            $value = new $this->dtoClass($value);
        }

        return json_encode($value);
    }
}
