<script type="text/ecmascript-6">
    export default {
        data() {
            return {
                ready: false,
                receipt: {},
                paymentMethods: [
                    { value: 'full_prepayment', text: 'предоплата 100%' },
                    { value: 'prepayment', text: 'предоплата' },
                    { value: 'advance', text: 'аванс' },
                    { value: 'full_payment', text: 'полный расчет' },
                    { value: 'partial_payment', text: 'частичный расчет и кредит' },
                    { value: 'credit', text: 'передача в кредит' },
                    { value: 'credit_payment', text: 'оплата кредита' }
                ],
                paymentObjects: [
                    { value: 'commodity', text: 'товар' },
                    { value: 'excise', text: 'подакцизный товар' },
                    { value: 'job', text: 'работа' },
                    { value: 'service', text: 'услуга' },
                    { value: 'gambling_bet', text: 'ставка азартной игры' },
                    { value: 'gambling_prize', text: 'выигрыш азартной игры' },
                    { value: 'lottery', text: 'лотерейный билет' },
                    { value: 'lottery_prize', text: 'выигрыш лотереи' },
                    { value: 'intellectual_activity', text: 'предоставление результатов интеллектуальной деятельности' },
                    { value: 'payment', text: 'платеж' },
                    { value: 'agent_commission', text: 'агентское вознаграждение' },
                    { value: 'award', text: 'взнос/штраф/вознаграждение/бонус' },
                    { value: 'composite', text: 'составной предмет расчета' },
                    { value: 'another', text: 'иной предмет расчета' },
                    { value: 'property_right', text: 'имущественное право' },
                    { value: 'non-operating_gain', text: 'внереализационный доход' },
                    { value: 'insurance_premium', text: 'страховые взносы' },
                    { value: 'sales_tax', text: 'торговый сбор' },
                    { value: 'resort_fee', text: 'курортный сбор' },
                    { value: 'deposit', text: 'залог' },
                    { value: 'expense', text: 'расход' },
                    { value: 'pension_insurance_ip', text: 'взносы на ОПС ИП' },
                    { value: 'pension_insurance', text: 'взносы на ОПС' },
                    { value: 'medical_insurance_ip', text: 'взносы на ОМС ИП' },
                    { value: 'medical_insurance', text: 'взносы на ОМС' },
                    { value: 'social_insurance', text: 'взносы на ОСС' },
                    { value: 'casino_payment', text: 'платеж казино' }
                ],
                agentTypes: [
                    { value: null, text: 'нет' },
                    { value: 'bank_paying_agent', text: 'банковский платежный агент' },
                    { value: 'bank_paying_subagent', text: 'банковский платежный субагент' },
                    { value: 'paying_agent', text: 'платежный агент' },
                    { value: 'paying_subagent', text: 'платежный субагент' },
                    { value: 'attorney', text: 'поверенный' },
                    { value: 'commission_agent', text: 'комиссионер' },
                    { value: 'another', text: 'другой' }
                ]
            }
        },

        mounted() {
            this.loadReceipt(this.$route.params.id);

            document.title = "Fiscal Registrar - Receipt";
        },

        methods: {
            loadReceipt(id) {
                this.ready = false;

                this.$http.get(FiscalRegistrar.basePath + '/api/v1/receipts/' + id)
                    .then(response => {
                        this.receipt = response.data;
                        this.ready = true;
                    });
            },

            saveReceipt() {
                this.$http.put(FiscalRegistrar.basePath + '/api/v1/receipts/' + this.receipt.id, this.receipt)
            }
        }
    }
</script>

<template>
    <div class="accordion" role="tablist">
        <b-form validated v-if="ready" @submit="saveReceipt">
            <b-card no-body class="mb-1">
                <b-card-header header-tag="header" class="p-1" role="tab">
                    <b-button block v-b-toggle.accordion-1 variant="info">Данные клиента</b-button>
                </b-card-header>
                <b-collapse id="accordion-1" visible accordion="my-accordion" role="tabpanel">
                    <b-card-body>
                        <b-card-text>
                            В запросе обязательно должно быть заполнено хотя бы одно из полей: <code>email</code> или <code>phone</code>.<br />
                            Если заполнены оба поля, ОФД отправит электронный чек только на email.
                        </b-card-text>
                        <b-container fluid>
                            <b-form-row class="my-1">
                                <b-col sm="2">
                                    <b-form-group label="Электронный адрес" label-for="clientEmail">
                                        <b-form-input id="clientEmail" type="email" size="sm" placeholder="user@domain.com" v-model="receipt.data.client.email"></b-form-input>
                                    </b-form-group>
                                </b-col>
                                <b-col sm="2">
                                    <b-form-group label="Телефон" label-for="clientPhone">
                                        <b-form-input id="clientPhone" type="tel" size="sm" placeholder="+79001234567" v-model="receipt.data.client.phone"></b-form-input>
                                    </b-form-group>
                                </b-col>
                                <b-col sm="2">
                                    <b-form-group label="Наименование" label-for="clientName">
                                        <b-form-input id="clientName" type="text" size="sm" placeholder="Иван Иванович Иванов" v-model="receipt.data.client.name"></b-form-input>
                                    </b-form-group>
                                </b-col>
                                <b-col sm="2">
                                    <b-form-group label="ИНН" label-for="clientInn">
                                        <b-form-input id="clientInn" type="text" size="sm" placeholder="1234567890" pattern="\d{10}|\d{12}" maxlength="12" v-model="receipt.data.client.inn"></b-form-input>
                                    </b-form-group>
                                </b-col>
                            </b-form-row>
                        </b-container>
                    </b-card-body>
                </b-collapse>
            </b-card>

            <b-card no-body class="mb-1">
                <b-card-header header-tag="header" class="p-1" role="tab">
                    <b-button block v-b-toggle.accordion-2 variant="info">Данные организации</b-button>
                </b-card-header>
                <b-collapse id="accordion-2" accordion="my-accordion" role="tabpanel">
                    <b-card-body>
                        <b-card-text>
                            Оставьте поля пустыми для автоматической подстановки параметров из конфигурации подключения при проводке чека.
                        </b-card-text>
                        <b-container fluid>
                            <b-form-row class="my-1">
                                <b-col sm="2">
                                    <b-form-group label="Электронный адрес" label-for="companyEmail">
                                        <b-form-input id="companyEmail" type="email" size="sm" placeholder="user@domain.com" v-model="receipt.data.company.email"></b-form-input>
                                    </b-form-group>
                                </b-col>
                                <b-col sm="2">
                                    <b-form-group label="ИНН" label-for="companyInn">
                                        <b-form-input id="companyInn" type="text" size="sm" placeholder="1234567890" pattern="\d{10}|\d{12}" maxlength="12" v-model="receipt.data.company.inn"></b-form-input>
                                    </b-form-group>
                                </b-col>
                                <b-col sm="2">
                                    <b-form-group label="Место расчетов" label-for="companyPaymentSite">
                                        <b-form-input id="companyPaymentSite" type="text" size="sm" v-model="receipt.data.company.payment_site"></b-form-input>
                                    </b-form-group>
                                </b-col>
                            </b-form-row>
                        </b-container>
                    </b-card-body>
                </b-collapse>
            </b-card>

            <b-card no-body class="mb-1">
                <b-card-header header-tag="header" class="p-1" role="tab">
                    <b-button block v-b-toggle.accordion-3 variant="info">Позиции документа</b-button>
                </b-card-header>
                <b-collapse id="accordion-3" accordion="my-accordion" role="tabpanel">
                    <b-card-body>
                        <b-card-text>
                            TODO
                        </b-card-text>
                        <b-container fluid>
                            <b-form-row v-for="(item, id) in receipt.data.items" :key="id" class="my-1">
                                <b-col lg="3" md="2">
                                    <b-form-group label="Наименование" :label-for="'item' + (id+1) + 'Name'">
                                        <b-form-input :id="'item' + (id+1) + 'Name'" type="text" size="sm" required v-model="item.name"></b-form-input>
                                    </b-form-group>
                                </b-col>
                                <b-col lg="1" md="2">
                                    <b-form-group label="Цена" :label-for="'item' + (id+1) + 'Price'">
                                        <b-form-input :id="'item' + (id+1) + 'Price'" type="number" min="0" max="42949672.95" step=".01" size="sm" placeholder="0" required v-model="item.price"></b-form-input>
                                    </b-form-group>
                                </b-col>
                                <b-col lg="1" md="2">
                                    <b-form-group label="Кол-во" :label-for="'item' + (id+1) + 'Quantity'">
                                        <b-form-input :id="'item' + (id+1) + 'Quantity'" type="number" min=".001" max="99999.999" step="any" size="sm" placeholder="1" required v-model="item.quantity"></b-form-input>
                                    </b-form-group>
                                </b-col>
                                <b-col lg="1" md="2">
                                    <b-form-group label="Сумма" :label-for="'item' + (id+1) + 'Sum'">
                                        <b-form-input :id="'item' + (id+1) + 'Sum'" type="number" min="0" max="42949672.95" step=".01" size="sm" placeholder="0" required v-model="item.sum"></b-form-input>
                                    </b-form-group>
                                </b-col>
                                <b-col lg="1" md="2">
                                    <b-form-group label="Ед. изм." :label-for="'item' + (id+1) + 'MeasurementUnit'">
                                        <b-form-input :id="'item' + (id+1) + 'MeasurementUnit'" type="text" size="sm" placeholder="шт." maxlength="16" v-model="item.measurement_unit"></b-form-input>
                                    </b-form-group>
                                </b-col>
                                <b-col lg="2" md="2">
                                    <b-form-group label="Способ расчета" :label-for="'item' + (id+1) + 'PaymentMethod'">
                                        <b-form-select :id="'item' + (id+1) + 'PaymentMethod'" size="sm" v-model="item.payment_method" :options="paymentMethods"></b-form-select>
                                    </b-form-group>
                                </b-col>
                                <b-col lg="2" md="2">
                                    <b-form-group label="Предмет расчета" :label-for="'item' + (id+1) + 'PaymentObject'">
                                        <b-form-select :id="'item' + (id+1) + 'PaymentObject'" size="sm" v-model="item.payment_object" :options="paymentObjects"></b-form-select>
                                    </b-form-group>
                                </b-col>
                            </b-form-row>
                        </b-container>
                    </b-card-body>
                </b-collapse>
            </b-card>

            <b-card no-body class="mb-1">
                <b-card-header header-tag="header" class="p-1" role="tab">
                    <b-button block v-b-toggle.accordion-4 variant="info">Данные по оплате</b-button>
                </b-card-header>
                <b-collapse id="accordion-4" accordion="my-accordion" role="tabpanel">
                    <b-card-body>
                        <b-card-text>
                            TODO
                        </b-card-text>
                        <b-container fluid>
                            <b-form-row class="my-1">
                                <b-col sm="1">
                                    <b-form-group label="Наличными" label-for="paymentCash">
                                        <b-form-input id="paymentCash" type="number" min="0" max="42949672.95" step=".01" size="sm" placeholder="0" v-model="receipt.data.payments.cash"></b-form-input>
                                    </b-form-group>
                                </b-col>
                                <b-col sm="1">
                                    <b-form-group label="Безналичными" label-for="paymentElectronic">
                                        <b-form-input id="paymentElectronic" type="number" min="0" max="42949672.95" step=".01" size="sm" placeholder="0" v-model="receipt.data.payments.electronic"></b-form-input>
                                    </b-form-group>
                                </b-col>
                                <b-col sm="1">
                                    <b-form-group label="Предоплатой" label-for="paymentPrepaid">
                                        <b-form-input id="paymentPrepaid" type="number" min="0" max="42949672.95" step=".01" size="sm" placeholder="0" v-model="receipt.data.payments.prepaid"></b-form-input>
                                    </b-form-group>
                                </b-col>
                                <b-col sm="1">
                                    <b-form-group label="Постоплатой" label-for="paymentPostpaid">
                                        <b-form-input id="paymentPostpaid" type="number" min="0" max="42949672.95" step=".01" size="sm" placeholder="0" v-model="receipt.data.payments.postpaid"></b-form-input>
                                    </b-form-group>
                                </b-col>
                                <b-col sm="1">
                                    <b-form-group label="Встр. предст." label-for="paymentOther">
                                        <b-form-input id="paymentOther" type="number" min="0" max="42949672.95" step=".01" size="sm" placeholder="0" v-model="receipt.data.payments.other"></b-form-input>
                                    </b-form-group>
                                </b-col>
                            </b-form-row>
                        </b-container>
                    </b-card-body>
                </b-collapse>
            </b-card>

            <b-card no-body class="mb-1">
                <b-card-header header-tag="header" class="p-1" role="tab">
                    <b-button block v-b-toggle.accordion-5 variant="info">Данные агента</b-button>
                </b-card-header>
                <b-collapse id="accordion-5" accordion="my-accordion" role="tabpanel">
                    <b-card-body>
                        <b-card-text>
                            TODO
                        </b-card-text>
                        <b-container fluid>
                            <b-form-row class="my-1">
                                <b-col sm="2">
                                    <!--<b-form-group label="Тип агента" label-for="agentType">
                                        <b-form-select id="agentType" size="sm" v-model="receipt.data.agent_info.type" :options="agentTypes"></b-form-select>
                                    </b-form-group>-->
                                </b-col>
                            </b-form-row>
                        </b-container>
                    </b-card-body>
                </b-collapse>
            </b-card>

            <b-button type="submit" variant="primary" size="sm">Сохранить</b-button>
        </b-form>
    </div>
</template>
