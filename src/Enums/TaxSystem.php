<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\Enums;

/**
 * @method static self OSN
 * @method static self USNIncome
 * @method static self USNIncomeOutcome
 * @method static self ENVD
 * @method static self ESN
 * @method static self Patent
 */
final class TaxSystem extends Enum
{
    public const OSN = 'osn';
    public const USNIncome = 'usn_income';
    public const USNIncomeOutcome = 'usn_income_outcome';
    public const ENVD = 'envd';
    public const ESN = 'esn';
    public const Patent = 'patent';
}
