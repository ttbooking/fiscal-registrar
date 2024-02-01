<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\Enums;

enum PaymentObject: string
{
    use Translatable;

    case Commodity = 'commodity';
    case Excise = 'excise';
    case Job = 'job';
    case Service = 'service';
    case GamblingBet = 'gambling_bet';
    case GamblingPrize = 'gambling_prize';
    case Lottery = 'lottery';
    case LotteryPrize = 'lottery_prize';
    case IntellectualActivity = 'intellectual_activity';
    case Payment = 'payment';
    case AgentCommission = 'agent_commission';
    case Award = 'award';
    case Composite = 'composite';
    case Another = 'another';
    case PropertyRight = 'property_right';
    case NonOperatingGain = 'non-operating_gain';
    case InsurancePremium = 'insurance_premium';
    case SalesTax = 'sales_tax';
    case ResortFee = 'resort_fee';
    case Deposit = 'deposit';
    case Expense = 'expense';
    case PensionInsuranceIp = 'pension_insurance_ip';
    case PensionInsurance = 'pension_insurance';
    case MedicalInsuranceIp = 'medical_insurance_ip';
    case MedicalInsurance = 'medical_insurance';
    case SocialInsurance = 'social_insurance';
    case CasinoPayment = 'casino_payment';
}
