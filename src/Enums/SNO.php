<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\Enums;

use MyCLabs\Enum\Enum;

/**
 * @method static self OSN
 * @method static self USNIncome
 * @method static self USNIncomeOutcome
 * @method static self ENVD
 * @method static self ESN
 * @method static self Patent
 */
final class SNO extends Enum
{
    private const OSN = 'osn';
    private const USNIncome = 'usn_income';
    private const USNIncomeOutcome = 'usn_income_outcome';
    private const ENVD = 'envd';
    private const ESN = 'esn';
    private const Patent = 'patent';
}
