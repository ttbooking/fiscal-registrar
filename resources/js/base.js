import moment from "moment";

export default {
    computed: {
        FiscalRegistrar() {
            return FiscalRegistrar;
        },

        $echo() {
            return Echo.channel("fiscal-registrar");
        },

        agentTypeOptions() {
            return [{ value: null, text: "нет" }, ...this.buildOptions(this.dictionary.agentTypes)];
        },

        taxSystemOptions() {
            return [{ value: null, text: "не выбрано" }, ...this.buildOptions(this.dictionary.taxSystems)];
        },
    },

    methods: {
        formatTime(timestamp, format = "DD.MM.YYYY HH:mm:ss") {
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
            if (!obj || typeof obj !== "object") {
                return obj;
            }
            for (const key in obj) {
                obj[key] = this.emptify(obj[key]);
                obj[key] || (obj[key] = null);
            }
            Object.values(obj).every((prop) => prop === null) && (obj = null);
            return obj;
        },

        enumConnections() {
            this.$http.get(FiscalRegistrar.basePath + "/api/v1/connection").then((response) => {
                this.connections = response.data;
            });
        },

        resetFilter() {
            this.query.filter = {
                id: null,
                external_id: null,
                internal_id: null,
                created_from: null,
                created_to: null,
                connection: null,
                operation: null,
                min_total: null,
                max_total: null,
                state: null,
                email: null,
                phone: null,
                fn: null,
                i: null,
                fd: null,
            };
        },
    },

    beforeMount() {
        this.resetFilter();
        this.query = merge.all([
            this.query,
            JSON.parse(sessionStorage["fiscal-registrar.query"] || "{}"),
            qs.parse(window.location.search.replace(/^\?/, "")),
        ]);
    },

    watch: {
        query: {
            handler: function (query) {
                sessionStorage["fiscal-registrar.query"] = JSON.stringify(query);
            },
            deep: true,
        },
    },

    data() {
        return {
            query: {
                hideFilter: false,
                hideCreateButton: false,
                filter: {},
                sort: {
                    by: "id",
                    desc: false,
                },
            },
            connections: {},
            dictionary: {
                operations: {
                    sell: "приход",
                    sell_refund: "возврат прихода",
                    buy: "расход",
                    buy_refund: "возврат расхода",
                },

                states: ["не зарегистрирован", "зарегистрирован", "проведен"],

                agentTypes: {
                    bank_paying_agent: "банковский платежный агент",
                    bank_paying_subagent: "банковский платежный субагент",
                    paying_agent: "платежный агент",
                    paying_subagent: "платежный субагент",
                    attorney: "поверенный",
                    commission_agent: "комиссионер",
                    another: "агент",
                },

                taxSystems: {
                    osn: "общая система налогообложения",
                    usn_income: "упрощенная система налогообложения (доходы)",
                    usn_income_outcome: "упрощенная система налогообложения (доходы минус расходы)",
                    envd: "единый налог на вмененный доход",
                    esn: "единый сельскохозяйственный налог",
                    patent: "патентная система налогообложения",
                },

                paymentMethods: {
                    full_prepayment: "предоплата 100%",
                    prepayment: "предоплата",
                    advance: "аванс",
                    full_payment: "полный расчет",
                    partial_payment: "частичный расчет и кредит",
                    credit: "передача в кредит",
                    credit_payment: "оплата кредита",
                },

                paymentObjects: {
                    commodity: "товар",
                    excise: "подакцизный товар",
                    job: "работа",
                    service: "услуга",
                    gambling_bet: "ставка азартной игры",
                    gambling_prize: "выигрыш азартной игры",
                    lottery: "лотерейный билет",
                    lottery_prize: "выигрыш лотереи",
                    intellectual_activity: "предоставление результатов интеллектуальной деятельности",
                    payment: "платеж",
                    agent_commission: "агентское вознаграждение",
                    award: "взнос/штраф/вознаграждение/бонус",
                    composite: "составной предмет расчета",
                    another: "иной предмет расчета",
                    property_right: "имущественное право",
                    "non-operating_gain": "внереализационный доход",
                    insurance_premium: "страховые взносы",
                    sales_tax: "торговый сбор",
                    resort_fee: "курортный сбор",
                    deposit: "залог",
                    expense: "расход",
                    pension_insurance_ip: "взносы на ОПС ИП",
                    pension_insurance: "взносы на ОПС",
                    medical_insurance_ip: "взносы на ОМС ИП",
                    medical_insurance: "взносы на ОМС",
                    social_insurance: "взносы на ОСС",
                    casino_payment: "платеж казино",
                },

                vatTypes: {
                    none: "нет",
                    vat0: "0%",
                    vat5: "5%",
                    vat7: "7%",
                    vat10: "10%",
                    vat18: "18%",
                    vat20: "20%",
                    vat105: "5/105",
                    vat107: "7/107",
                    vat110: "10/110",
                    vat118: "18/118",
                    vat120: "20/120",
                },

                vatRates: {
                    none: 0,
                    vat0: 0,
                    vat5: 0.05,
                    vat7: 0.07,
                    vat10: 0.1,
                    vat18: 0.18,
                    vat20: 0.2,
                    vat105: 5 / 105,
                    vat107: 7 / 107,
                    vat110: 10 / 110,
                    vat118: 18 / 118,
                    vat120: 20 / 120,
                },
            },
        };
    },
};
