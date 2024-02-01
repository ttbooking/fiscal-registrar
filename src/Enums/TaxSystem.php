<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\Enums;

enum TaxSystem: string
{
    use Translatable;

    case OSN = 'osn';
    case USNIncome = 'usn_income';
    case USNIncomeOutcome = 'usn_income_outcome';
    case ENVD = 'envd';
    case ESN = 'esn';
    case Patent = 'patent';
}
