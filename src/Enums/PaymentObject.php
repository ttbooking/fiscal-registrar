<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\Enums;

/**
 * @method static self Commodity
 * @method static self Excise
 * @method static self Job
 * @method static self Service
 * @method static self GamblingBet
 * @method static self GamblingPrize
 * @method static self Lottery
 * @method static self LotteryPrize
 * @method static self IntellectualActivity
 * @method static self Payment
 * @method static self AgentCommission
 * @method static self Award
 * @method static self Composite
 * @method static self Another
 * @method static self PropertyRight
 * @method static self NonOperatingGain
 * @method static self InsurancePremium
 * @method static self SalesTax
 * @method static self ResortFee
 * @method static self Deposit
 * @method static self Expense
 * @method static self PensionInsuranceIp
 * @method static self PensionInsurance
 * @method static self MedicalInsuranceIp
 * @method static self MedicalInsurance
 * @method static self SocialInsurance
 * @method static self CasinoPayment
 */
final class PaymentObject extends Enum
{
    private const Commodity = 'commodity';
    private const Excise = 'excise';
    private const Job = 'job';
    private const Service = 'service';
    private const GamblingBet = 'gambling_bet';
    private const GamblingPrize = 'gambling_prize';
    private const Lottery = 'lottery';
    private const LotteryPrize = 'lottery_prize';
    private const IntellectualActivity = 'intellectual_activity';
    private const Payment = 'payment';
    private const AgentCommission = 'agent_commission';
    private const Award = 'award';
    private const Composite = 'composite';
    private const Another = 'another';
    private const PropertyRight = 'property_right';
    private const NonOperatingGain = 'non-operating_gain';
    private const InsurancePremium = 'insurance_premium';
    private const SalesTax = 'sales_tax';
    private const ResortFee = 'resort_fee';
    private const Deposit = 'deposit';
    private const Expense = 'expense';
    private const PensionInsuranceIp = 'pension_insurance_ip';
    private const PensionInsurance = 'pension_insurance';
    private const MedicalInsuranceIp = 'medical_insurance_ip';
    private const MedicalInsurance = 'medical_insurance';
    private const SocialInsurance = 'social_insurance';
    private const CasinoPayment = 'casino_payment';
}
