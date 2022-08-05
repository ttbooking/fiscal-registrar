<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\Enums;

/**
 * @method static self BankPayingAgent()
 * @method static self BankPayingSubagent()
 * @method static self PayingAgent()
 * @method static self PayingSubagent()
 * @method static self Attorney()
 * @method static self CommissionAgent()
 * @method static self Another()
 */
final class AgentType extends Enum
{
    private const BankPayingAgent = 'bank_paying_agent';
    private const BankPayingSubagent = 'bank_paying_subagent';
    private const PayingAgent = 'paying_agent';
    private const PayingSubagent = 'paying_subagent';
    private const Attorney = 'attorney';
    private const CommissionAgent = 'commission_agent';
    private const Another = 'another';
}
