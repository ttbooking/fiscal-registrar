<script type="text/ecmascript-6">
    import ReceiptItem from '../components/ReceiptItem.vue';

    export default {
        components: {
            ReceiptItem
        },

        data() {
            return {
                ready: false,
                receipt: {},
                agentTypes: [
                    { value: null, text: 'нет' },
                    { value: 'bank_paying_agent', text: 'банковский платежный агент' },
                    { value: 'bank_paying_subagent', text: 'банковский платежный субагент' },
                    { value: 'paying_agent', text: 'платежный агент' },
                    { value: 'paying_subagent', text: 'платежный субагент' },
                    { value: 'attorney', text: 'поверенный' },
                    { value: 'commission_agent', text: 'комиссионер' },
                    { value: 'another', text: 'другой' }
                ],
                connections: [],
                operations: [
                    { value: 'sell', text: 'приход' },
                    { value: 'sell_refund', text: 'возврат прихода' },
                    { value: 'buy', text: 'расход' },
                    { value: 'buy_refund', text: 'возврат расхода' },
                ]
            }
        },

        mounted() {
            this.loadReceipt(this.$route.params.id);
            this.enumConnections();

            document.title = 'Fiscal Registrar - Receipt';
        },

        methods: {
            enumConnections() {
                this.$http.get(FiscalRegistrar.basePath + '/api/v1/connection/')
                    .then(response => {
                        this.connections = response.data;
                    });
            },

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
            },

            addItem() {
                this.receipt.data.items.push({
                    agent_info: null,
                    country_code: null,
                    declaration_number: null,
                    excise: null,
                    measurement_unit: null,
                    name: '',
                    nomenclature_code: null,
                    payment_method: 'full_prepayment',
                    payment_object: 'commodity',
                    price: null,
                    quantity: 1,
                    sum: null,
                    supplier_info: null,
                    user_data: null,
                    vat: {
                        sum: null,
                        type: 'vat20'
                    }
                });
            },

            removeItem(id) {
                this.receipt.data.items.splice(id, 1);
            }
        },

        computed: {
            isClientEmailRequired() {
                return !this.receipt.data.client.phone;
            },

            isClientPhoneRequired() {
                return !this.receipt.data.client.email;
            }
        }
    }
</script>

<style scoped>
fieldset { margin: 0 }
</style>

<template>
    <div>
        <h2 class="text-center">Редактирование кассового чека</h2>
        <div class="accordion" role="tablist">
            <b-form validated v-if="ready" @submit.prevent="saveReceipt">
                <b-card no-body class="mb-1">
                    <b-card-header header-tag="header" class="p-1" role="tab">
                        <b-button block v-b-toggle.accordion-1 variant="info">Атрибуты документа</b-button>
                    </b-card-header>
                    <b-collapse id="accordion-1" visible accordion="my-accordion" role="tabpanel">
                        <b-card-body>
                            <b-card-text>
                                TODO
                            </b-card-text>
                            <b-form-group :disabled="receipt.state !== 0">
                                <b-container fluid>
                                    <b-form-row class="my-1">
                                        <b-col align-self="end" lg="3" md="4" sm="6">
                                            <b-form-group label="Подключение" label-for="receiptConnection" class="required">
                                                <b-form-select id="receiptConnection" size="sm" v-model="receipt.connection" :options="connections"></b-form-select>
                                            </b-form-group>
                                        </b-col>
                                        <b-col align-self="end" lg="3" md="4" sm="6">
                                            <b-form-group label="Операция" label-for="receiptOperation" class="required">
                                                <b-form-select id="receiptOperation" size="sm"  v-model="receipt.operation" :options="operations"></b-form-select>
                                            </b-form-group>
                                        </b-col>
                                    </b-form-row>
                                </b-container>
                            </b-form-group>
                        </b-card-body>
                    </b-collapse>
                </b-card>

                <b-card no-body class="mb-1">
                    <b-card-header header-tag="header" class="p-1" role="tab">
                        <b-button block v-b-toggle.accordion-2 variant="info">Данные клиента</b-button>
                    </b-card-header>
                    <b-collapse id="accordion-2" accordion="my-accordion" role="tabpanel">
                        <b-card-body>
                            <b-card-text>
                                В запросе обязательно должно быть заполнено хотя бы одно из полей: <code>email</code> или <code>phone</code>.<br />
                                Если заполнены оба поля, ОФД отправит электронный чек только на email.
                            </b-card-text>
                            <b-form-group :disabled="receipt.state !== 0">
                                <b-container fluid>
                                    <b-form-row class="my-1">
                                        <b-col align-self="end" lg="2" md="3" sm="4">
                                            <b-form-group label="Электронный адрес" label-for="clientEmail" :class="isClientEmailRequired && 'required'">
                                                <b-form-input id="clientEmail" type="email" size="sm" placeholder="user@domain.com" :required="isClientEmailRequired" v-model="receipt.data.client.email"></b-form-input>
                                            </b-form-group>
                                        </b-col>
                                        <b-col align-self="end" lg="2" md="3" sm="4">
                                            <b-form-group label="Телефон" label-for="clientPhone" :class="isClientPhoneRequired && 'required'">
                                                <b-form-input id="clientPhone" type="tel" size="sm" placeholder="+79001234567" :required="isClientPhoneRequired" v-model="receipt.data.client.phone"></b-form-input>
                                            </b-form-group>
                                        </b-col>
                                        <b-col align-self="end" lg="2" md="3" sm="4">
                                            <b-form-group label="Наименование" label-for="clientName">
                                                <b-form-input id="clientName" type="text" size="sm" placeholder="Иван Иванович Иванов" v-model="receipt.data.client.name"></b-form-input>
                                            </b-form-group>
                                        </b-col>
                                        <b-col align-self="end" lg="2" md="3" sm="4">
                                            <b-form-group label="ИНН" label-for="clientInn">
                                                <b-form-input id="clientInn" type="text" size="sm" placeholder="1234567890" pattern="\d{10}|\d{12}" maxlength="12" v-model="receipt.data.client.inn"></b-form-input>
                                            </b-form-group>
                                        </b-col>
                                    </b-form-row>
                                </b-container>
                            </b-form-group>
                        </b-card-body>
                    </b-collapse>
                </b-card>

                <b-card no-body class="mb-1">
                    <b-card-header header-tag="header" class="p-1" role="tab">
                        <b-button block v-b-toggle.accordion-3 variant="info">Данные организации</b-button>
                    </b-card-header>
                    <b-collapse id="accordion-3" accordion="my-accordion" role="tabpanel">
                        <b-card-body>
                            <b-card-text>
                                Оставьте поля пустыми для автоматической подстановки параметров из конфигурации подключения при проводке чека.
                            </b-card-text>
                            <b-form-group :disabled="receipt.state !== 0">
                                <b-container fluid>
                                    <b-form-row class="my-1">
                                        <b-col align-self="end" lg="2" md="3" sm="4">
                                            <b-form-group label="Электронный адрес" label-for="companyEmail">
                                                <b-form-input id="companyEmail" type="email" size="sm" placeholder="user@domain.com" v-model="receipt.data.company.email"></b-form-input>
                                            </b-form-group>
                                        </b-col>
                                        <b-col align-self="end" lg="2" md="3" sm="4">
                                            <b-form-group label="ИНН" label-for="companyInn">
                                                <b-form-input id="companyInn" type="text" size="sm" placeholder="1234567890" pattern="\d{10}|\d{12}" maxlength="12" v-model="receipt.data.company.inn"></b-form-input>
                                            </b-form-group>
                                        </b-col>
                                        <b-col align-self="end" lg="2" md="3" sm="4">
                                            <b-form-group label="Место расчетов" label-for="companyPaymentSite">
                                                <b-form-input id="companyPaymentSite" type="text" size="sm" v-model="receipt.data.company.payment_site"></b-form-input>
                                            </b-form-group>
                                        </b-col>
                                    </b-form-row>
                                </b-container>
                            </b-form-group>
                        </b-card-body>
                    </b-collapse>
                </b-card>

                <b-card no-body class="mb-1">
                    <b-card-header header-tag="header" class="p-1" role="tab">
                        <b-button block v-b-toggle.accordion-4 variant="info">Позиции документа</b-button>
                    </b-card-header>
                    <b-collapse id="accordion-4" accordion="my-accordion" role="tabpanel">
                        <b-card-body>
                            <b-card-text>
                                В документе должна быть как минимум одна позиция.
                            </b-card-text>
                            <b-form-group :disabled="receipt.state !== 0">
                                <b-container fluid>
                                    <template v-for="(item, id) in receipt.data.items">
                                        <receipt-item :key="id" :item="item" @remove="removeItem(id)"></receipt-item>
                                        <hr />
                                    </template>
                                    <b-button variant="primary" size="sm" @click="addItem">Добавить</b-button>
                                </b-container>
                            </b-form-group>
                        </b-card-body>
                    </b-collapse>
                </b-card>

                <b-card no-body class="mb-1">
                    <b-card-header header-tag="header" class="p-1" role="tab">
                        <b-button block v-b-toggle.accordion-5 variant="info">Данные по оплате</b-button>
                    </b-card-header>
                    <b-collapse id="accordion-5" accordion="my-accordion" role="tabpanel">
                        <b-card-body>
                            <b-card-text>
                                TODO
                            </b-card-text>
                            <b-form-group :disabled="receipt.state !== 0">
                                <b-container fluid>
                                    <b-form-row class="my-1">
                                        <b-col align-self="end" lg="2" md="3" sm="4">
                                            <b-form-group label="Наличными" label-for="paymentCash">
                                                <b-form-input id="paymentCash" type="number" min="0" max="42949672.95" step=".01" size="sm" placeholder="0" v-model="receipt.data.payments.cash"></b-form-input>
                                            </b-form-group>
                                        </b-col>
                                        <b-col align-self="end" lg="2" md="3" sm="4">
                                            <b-form-group label="Безналичными" label-for="paymentElectronic">
                                                <b-form-input id="paymentElectronic" type="number" min="0" max="42949672.95" step=".01" size="sm" placeholder="0" v-model="receipt.data.payments.electronic"></b-form-input>
                                            </b-form-group>
                                        </b-col>
                                        <b-col align-self="end" lg="2" md="3" sm="4">
                                            <b-form-group label="Предоплатой" label-for="paymentPrepaid">
                                                <b-form-input id="paymentPrepaid" type="number" min="0" max="42949672.95" step=".01" size="sm" placeholder="0" v-model="receipt.data.payments.prepaid"></b-form-input>
                                            </b-form-group>
                                        </b-col>
                                        <b-col align-self="end" lg="2" md="3" sm="4">
                                            <b-form-group label="Постоплатой" label-for="paymentPostpaid">
                                                <b-form-input id="paymentPostpaid" type="number" min="0" max="42949672.95" step=".01" size="sm" placeholder="0" v-model="receipt.data.payments.postpaid"></b-form-input>
                                            </b-form-group>
                                        </b-col>
                                        <b-col align-self="end" lg="2" md="3" sm="4">
                                            <b-form-group label="Встр. предст." label-for="paymentOther">
                                                <b-form-input id="paymentOther" type="number" min="0" max="42949672.95" step=".01" size="sm" placeholder="0" v-model="receipt.data.payments.other"></b-form-input>
                                            </b-form-group>
                                        </b-col>
                                    </b-form-row>
                                </b-container>
                            </b-form-group>
                        </b-card-body>
                    </b-collapse>
                </b-card>

                <b-card no-body class="mb-1">
                    <b-card-header header-tag="header" class="p-1" role="tab">
                        <b-button block v-b-toggle.accordion-6 variant="info">Данные агента</b-button>
                    </b-card-header>
                    <b-collapse id="accordion-6" accordion="my-accordion" role="tabpanel">
                        <b-card-body>
                            <b-card-text>
                                TODO
                            </b-card-text>
                            <b-form-group :disabled="receipt.state !== 0">
                                <b-container fluid>
                                    <b-form-row class="my-1">
                                        <b-col align-self="end" sm="2">
                                            <!--<b-form-group label="Тип агента" label-for="agentType">
                                                <b-form-select id="agentType" size="sm" v-model="receipt.data.agent_info.type" :options="agentTypes"></b-form-select>
                                            </b-form-group>-->
                                        </b-col>
                                    </b-form-row>
                                </b-container>
                            </b-form-group>
                        </b-card-body>
                    </b-collapse>
                </b-card>

                <b-button type="submit" variant="primary" size="sm" :disabled="receipt.state !== 0">Сохранить</b-button>
            </b-form>
        </div>
    </div>
</template>
