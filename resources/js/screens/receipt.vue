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
                ],
                vats: {
                    without_vat: null,
                    with_vat0: null,
                    vat10: null,
                    vat20: null,
                    vat110: null,
                    vat120: null
                },
                vatRates: {
                    none: 0,
                    vat0: 0,
                    vat10: .1,
                    vat18: .18,
                    vat20: .2,
                    vat110: 10 / 110,
                    vat118: 18 / 118,
                    vat120: 20 / 120
                }
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
            },

            extractVat(sum, vatType) {
                return parseFloat((sum - sum / (1 + this.vatRates[vatType])).toFixed(2));
            },

            getVats(calc = false) {
                const vats = {
                    without_vat: 0,
                    with_vat0: 0,
                    vat10: 0,
                    vat20: 0,
                    vat110: 0,
                    vat120: 0
                };

                if (calc) {
                    for (const item of this.receipt.data.items) {
                        switch (item?.vat.type ?? 'none') {
                            case 'vat20':
                            case 'vat18':
                                vats.vat20 += this.extractVat(item.sum, item.vat.type);
                                break;
                            case 'vat10':
                                vats.vat10 += this.extractVat(item.sum, item.vat.type);
                                break;
                            case 'vat0':
                                vats.with_vat0 += item.sum;
                                break;
                            case 'none':
                                vats.without_vat += item.sum;
                                break;
                            case 'vat120':
                            case 'vat118':
                                vats.vat120 += this.extractVat(item.sum, item.vat.type);
                                break;
                            case 'vat110':
                                vats.vat110 += this.extractVat(item.sum, item.vat.type);
                        }
                    }
                }

                return vats;
            }
        },

        computed: {
            isClientEmailRequired() {
                return !this.receipt.data.client.phone;
            },

            isClientPhoneRequired() {
                return !this.receipt.data.client.email;
            },

            selectConnections() {
                return Object.entries(this.connections).map(([name, data]) => ({ value: name, text: data.display_name }));
            },

            companyEmailPlaceholder() {
                return this.connections[this.receipt.connection].email ?? 'user@domain.com';
            },

            companyInnPlaceholder() {
                return this.connections[this.receipt.connection].inn ?? '1234567890';
            },

            companyPaymentSitePlaceholder() {
                return this.connections[this.receipt.connection].payment_site ?? '';
            },

            vatsPlaceholder() {
                return Object.fromEntries(
                    Object.entries(this.getVats(!this.receipt.data.vats)).map(([key, val]) => [key, String(val)])
                );
            },

            vatsWithoutVat: {
                get: function () {
                    return this.receipt.data.vats?.without_vat ?? null;
                },
                set: function (val) {
                    this.receipt.data.vats ??= this.vats;
                    this.receipt.data.vats.without_vat = val || null;
                }
            },

            vatsWithVat0: {
                get: function () {
                    return this.receipt.data.vats?.with_vat0 ?? null;
                },
                set: function (val) {
                    this.receipt.data.vats ??= this.vats;
                    this.receipt.data.vats.with_vat0 = val || null;
                }
            },

            vatsVat10: {
                get: function () {
                    return this.receipt.data.vats?.vat10 ?? null;
                },
                set: function (val) {
                    this.receipt.data.vats ??= this.vats;
                    this.receipt.data.vats.vat10 = val || null;
                }
            },

            vatsVat20: {
                get: function () {
                    return this.receipt.data.vats?.vat20 ?? null;
                },
                set: function (val) {
                    this.receipt.data.vats ??= this.vats;
                    this.receipt.data.vats.vat20 = val || null;
                }
            },

            vatsVat110: {
                get: function () {
                    return this.receipt.data.vats?.vat110 ?? null;
                },
                set: function (val) {
                    this.receipt.data.vats ??= this.vats;
                    this.receipt.data.vats.vat110 = val || null;
                }
            },

            vatsVat120: {
                get: function () {
                    return this.receipt.data.vats?.vat120 ?? null;
                },
                set: function (val) {
                    this.receipt.data.vats ??= this.vats;
                    this.receipt.data.vats.vat120 = val || null;
                }
            }
        },

        watch: {
            'receipt.data.vats': {
                handler: function (vats) {
                    if (vats === null) return;
                    Object.values(vats).every(vat => vat === null) && (this.receipt.data.vats = null);
                },
                deep: true
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
                                                <b-form-select id="receiptConnection" size="sm" v-model="receipt.connection" :options="selectConnections"></b-form-select>
                                            </b-form-group>
                                        </b-col>
                                        <b-col align-self="end" lg="3" md="4" sm="6">
                                            <b-form-group label="Операция" label-for="receiptOperation" class="required">
                                                <b-form-select id="receiptOperation" size="sm"  v-model="receipt.operation" :options="operations"></b-form-select>
                                            </b-form-group>
                                        </b-col>
                                        <b-col align-self="end" lg="3" md="4" sm="6">
                                            <b-form-group label="Уникальный идентификатор" label-for="receiptExternalId">
                                                <b-form-input id="receiptExternalId" type="text" size="sm" v-model="receipt.external_id"></b-form-input>
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
                                                <b-form-input id="companyEmail" type="email" size="sm" :placeholder="companyEmailPlaceholder" v-model="receipt.data.company.email"></b-form-input>
                                            </b-form-group>
                                        </b-col>
                                        <b-col align-self="end" lg="2" md="3" sm="4">
                                            <b-form-group label="ИНН" label-for="companyInn">
                                                <b-form-input id="companyInn" type="text" size="sm" :placeholder="companyInnPlaceholder" pattern="\d{10}|\d{12}" maxlength="12" v-model="receipt.data.company.inn"></b-form-input>
                                            </b-form-group>
                                        </b-col>
                                        <b-col align-self="end" lg="2" md="3" sm="4">
                                            <b-form-group label="Место расчетов" label-for="companyPaymentSite">
                                                <b-form-input id="companyPaymentSite" type="text" size="sm" :placeholder="companyPaymentSitePlaceholder" v-model="receipt.data.company.payment_site"></b-form-input>
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
                        <b-button block v-b-toggle.accordion-6 variant="info">НДС на чек</b-button>
                    </b-card-header>
                    <b-collapse id="accordion-6" accordion="my-accordion" role="tabpanel">
                        <b-card-body>
                            <b-card-text>
                                Необходимо передать либо сумму налога на позицию, либо сумму налога на чек. Если будет переданы и сумма налога на позицию и сумма налога на чек, сервис учтет только сумму налога на чек.
                                <span v-if="receipt.data.vats">В данный момент используются суммы налога на чек. Для возврата к использованию сумм налога на позицию, очистите все поля в этом разделе.</span>
                                <span v-else>В данный момент используются суммы налога на позицию. В полях ниже можно увидеть их расчетные величины.</span>
                            </b-card-text>
                            <b-form-group :disabled="receipt.state !== 0">
                                <b-container fluid>
                                    <b-form-row class="my-1">
                                        <b-col align-self="end" lg="2" md="3" sm="4">
                                            <b-form-group label="Сумма расчета по чеку без НДС" label-for="withoutVat">
                                                <b-form-input id="withoutVat" type="number" min="0" max="42949672.95" step=".01" size="sm" :placeholder="vatsPlaceholder.without_vat" v-model.number="vatsWithoutVat"></b-form-input>
                                            </b-form-group>
                                        </b-col>
                                        <b-col align-self="end" lg="2" md="3" sm="4">
                                            <b-form-group label="Сумма расчета по чеку с НДС 0%" label-for="withVat0">
                                                <b-form-input id="withVat0" type="number" min="0" max="42949672.95" step=".01" size="sm" :placeholder="vatsPlaceholder.with_vat0" v-model.number="vatsWithVat0"></b-form-input>
                                            </b-form-group>
                                        </b-col>
                                        <b-col align-self="end" lg="2" md="3" sm="4">
                                            <b-form-group label="Сумма НДС чека по ставке 10%" label-for="vat10">
                                                <b-form-input id="vat10" type="number" min="0" max="42949672.95" step=".01" size="sm" :placeholder="vatsPlaceholder.vat10" v-model.number="vatsVat10"></b-form-input>
                                            </b-form-group>
                                        </b-col>
                                        <b-col align-self="end" lg="2" md="3" sm="4">
                                            <b-form-group label="Сумма НДС чека по ставке 20%" label-for="vat20">
                                                <b-form-input id="vat20" type="number" min="0" max="42949672.95" step=".01" size="sm" :placeholder="vatsPlaceholder.vat20" v-model.number="vatsVat20"></b-form-input>
                                            </b-form-group>
                                        </b-col>
                                        <b-col align-self="end" lg="2" md="3" sm="4">
                                            <b-form-group label="Сумма НДС чека по расч. ставке 10/110" label-for="vat110">
                                                <b-form-input id="vat110" type="number" min="0" max="42949672.95" step=".01" size="sm" :placeholder="vatsPlaceholder.vat110" v-model.number="vatsVat110"></b-form-input>
                                            </b-form-group>
                                        </b-col>
                                        <b-col align-self="end" lg="2" md="3" sm="4">
                                            <b-form-group label="Сумма НДС чека по расч. ставке 20/120" label-for="vat120">
                                                <b-form-input id="vat120" type="number" min="0" max="42949672.95" step=".01" size="sm" :placeholder="vatsPlaceholder.vat120" v-model.number="vatsVat120"></b-form-input>
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
                        <b-button block v-b-toggle.accordion-7 variant="info">Данные агента</b-button>
                    </b-card-header>
                    <b-collapse id="accordion-7" accordion="my-accordion" role="tabpanel">
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
