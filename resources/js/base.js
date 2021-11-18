import moment from 'moment';

export default {
    computed: {
        FiscalRegistrar() {
            return FiscalRegistrar;
        },

        agentTypeOptions() {
            return [{ value: null, text: 'нет' }, ...this.buildOptions(this.dictionary.agentTypes)];
        },

        taxSystemOptions() {
            return [{ value: null, text: 'не выбрано' }, ...this.buildOptions(this.dictionary.taxSystems)];
        },
    },

    methods: {
        formatTime(timestamp, format = 'DD.MM.YYYY HH:mm:ss') {
            return moment(timestamp).format(format);
        },

        buildOptions(object, callback = null) {
            callback ??= ([key, value]) => ({ value: key, text: value });

            return Object.entries(object).map(callback);
        },

        extractVat(sum, vatType) {
            return parseFloat((sum - sum / (1 + this.dictionary.vatRates[vatType])).toFixed(2));
        },

        emptify(obj) {
            if (!obj || typeof obj !== 'object') {
                return obj;
            }
            for (const key in obj) {
                obj[key] = this.emptify(obj[key]);
                obj[key] || (obj[key] = null);
            }
            Object.values(obj).every(prop => prop === null) && (obj = null);
            return obj;
        },
    },

    data() {
        return {
            dictionary: {
                operations: {
                    sell: 'приход',
                    sell_refund: 'возврат прихода',
                    buy: 'расход',
                    buy_refund: 'возврат расхода',
                },

                states: [
                    'не зарегистрирован',
                    'зарегистрирован',
                    'проведен',
                ],

                agentTypes: {
                    bank_paying_agent: 'банковский платежный агент',
                    bank_paying_subagent: 'банковский платежный субагент',
                    paying_agent: 'платежный агент',
                    paying_subagent: 'платежный субагент',
                    attorney: 'поверенный',
                    commission_agent: 'комиссионер',
                    another: 'агент',
                },

                taxSystems: {
                    osn: 'общая система налогообложения',
                    usn_income: 'упрощенная система налогообложения (доходы)',
                    usn_income_outcome: 'упрощенная система налогообложения (доходы минус расходы)',
                    envd: 'единый налог на вмененный доход',
                    esn: 'единый сельскохозяйственный налог',
                    patent: 'патентная система налогообложения',
                },

                paymentMethods: {
                    full_prepayment: 'предоплата 100%',
                    prepayment: 'предоплата',
                    advance: 'аванс',
                    full_payment: 'полный расчет',
                    partial_payment: 'частичный расчет и кредит',
                    credit: 'передача в кредит',
                    credit_payment: 'оплата кредита',
                },

                paymentObjects: {
                    commodity: 'товар',
                    excise: 'подакцизный товар',
                    job: 'работа',
                    service: 'услуга',
                    gambling_bet: 'ставка азартной игры',
                    gambling_prize: 'выигрыш азартной игры',
                    lottery: 'лотерейный билет',
                    lottery_prize: 'выигрыш лотереи',
                    intellectual_activity: 'предоставление результатов интеллектуальной деятельности',
                    payment: 'платеж',
                    agent_commission: 'агентское вознаграждение',
                    award: 'взнос/штраф/вознаграждение/бонус',
                    composite: 'составной предмет расчета',
                    another: 'иной предмет расчета',
                    property_right: 'имущественное право',
                    'non-operating_gain': 'внереализационный доход',
                    insurance_premium: 'страховые взносы',
                    sales_tax: 'торговый сбор',
                    resort_fee: 'курортный сбор',
                    deposit: 'залог',
                    expense: 'расход',
                    pension_insurance_ip: 'взносы на ОПС ИП',
                    pension_insurance: 'взносы на ОПС',
                    medical_insurance_ip: 'взносы на ОМС ИП',
                    medical_insurance: 'взносы на ОМС',
                    social_insurance: 'взносы на ОСС',
                    casino_payment: 'платеж казино',
                },

                vatTypes: {
                    none: 'нет',
                    vat0: '0%',
                    vat10: '10%',
                    vat18: '18%',
                    vat20: '20%',
                    vat110: '10/110',
                    vat118: '18/118',
                    vat120: '20/120',
                },

                vatRates: {
                    none: 0,
                    vat0: 0,
                    vat10: .1,
                    vat18: .18,
                    vat20: .2,
                    vat110: 10 / 110,
                    vat118: 18 / 118,
                    vat120: 20 / 120,
                },
            },
        };
    },
};
