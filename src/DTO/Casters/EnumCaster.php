<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\DTO\Casters;

use LogicException;
use MyCLabs\Enum\Enum;
use Spatie\DataTransferObject\Caster;

class EnumCaster implements Caster
{
    /**
     * @param  string[]  $classNames
     */
    public function __construct(
        private array $classNames,
    ) {
    }

    /**
     * @param  mixed  $value
     * @return Enum<array-key>
     */
    public function cast(mixed $value): Enum
    {
        foreach ($this->classNames as $className) {
            if (is_subclass_of($className, Enum::class)) {
                return new $className($value);
            }
        }

        throw new LogicException('Caster [EnumCaster] may only be used to cast objects that implement '.Enum::class.'.');
    }
}
