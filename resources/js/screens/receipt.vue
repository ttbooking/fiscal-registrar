<script>
    import Receipt from "../models/Receipt"
    import ReceiptItem from '../components/ReceiptItem.vue'

    export default {
        components: {
            ReceiptItem,
        },

        data() {
            return {
                ready: false,
                showPreview: false,
                receipt: new Receipt,
                vats: {
                    without_vat: null,
                    with_vat0: null,
                    vat10: null,
                    vat20: null,
                    vat110: null,
                    vat120: null,
                },
                agentInfo: {
                    type: null,
                    paying_agent: {
                        operation: null,
                        phones: null,
                    },
                    receive_payments_operator: {
                        phones: null,
                    },
                    money_transfer_operator: {
                        phones: null,
                        name: null,
                        address: null,
                        inn: null,
                    },
                },
            }
        },

        mounted() {
            this.handleRoute(this.$route)
            this.enumConnections()
            this.listenEvents()
        },

        methods: {
            async handleRoute(to, from = null) {
                switch (to.name) {
                    case 'receipt':
                        await this.loadReceipt(to.params.id)
                        break
                    case 'new-receipt':
                        this.resetReceipt()
                        break
                    case 'new-receipt-from-existing':
                        from !== null && from.name === 'receipt' && +to.params.id === +from.params.id
                            ? this.shrinkReceipt()
                            : await this.loadReceipt(to.params.id, true)
                }
            },

            listenEvents() {
                this.$echo.listen('.receipt.processed', receipt => {
                    if (receipt.id === this.receipt.id) {
                        this.receipt = receipt
                    }
                })
            },

            resetReceipt() {
                this.receipt = new Receipt({
                    id: null,
                    state: 0,
                    connection: null,
                    operation: null,
                    external_id: null,
                    internal_id: null,
                    payload: {
                        client: {
                            email: null,
                            phone: null,
                            name: null,
                            inn: null,
                        },
                        company: {
                            name: null,
                            email: null,
                            inn: null,
                            payment_address: null,
                            payment_site: null,
                            tax_system: null,
                        },
                        agent_info: null,
                        supplier_info: null,
                        items: [],
                        payments: {
                            cash: 0,
                            electronic: 0,
                            prepaid: 0,
                            postpaid: 0,
                            other: 0,
                        },
                        vats: null,
                        total: 0,
                        additional_check_props: null,
                        cashier: null,
                        additional_user_props: null,
                    },
                    result: null,
                })
                this.ready = true
            },

            shrinkReceipt() {
                this.receipt.id = null
                this.receipt.state = 0
                this.receipt.external_id = null
                this.receipt.internal_id = null
                this.receipt.result = null
            },

            async loadReceipt(id, asTemplate = false) {
                this.ready = false
                this.receipt = await Receipt.find(id)
                asTemplate && this.shrinkReceipt()
                this.ready = true
            },

            async saveReceipt() {
                try {
                    this.receipt = await this.receipt.save()
                    this.receipt.id && this.$router.replace(
                        { name: 'receipt', params: { id: this.receipt.id } },
                    )
                } catch (error) {
                    Object.values(error.response.data.errors).forEach(
                        msgBag => this.$bvToast.toast(
                            msgBag.map(msg => this.$createElement('div', msg)), {
                                toaster: 'b-toaster-bottom-right',
                                variant: 'danger',
                                solid: true,
                                noCloseButton: true,
                            }
                        )
                    )
                }
            },

            async registerReceipt() {
                await this.saveReceipt()
                await this.receipt.register()
            },

            async syncReceipt() {
                await this.receipt.sync()
            },

            async deleteReceipt() {
                if (confirm('Удалить чек?')) {
                    await this.receipt.delete()
                    this.$router.replace({ name: 'receipts' })
                }
            },

            addItem() {
                this.receipt.payload.items.push({
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
                        type: 'vat20',
                    },
                })
            },

            removeItem(id) {
                this.receipt.payload.items.splice(id, 1)
            },

            getVats(calc = false) {
                const vats = {
                    without_vat: 0,
                    with_vat0: 0,
                    vat10: 0,
                    vat20: 0,
                    vat110: 0,
                    vat120: 0,
                }

                if (calc) {
                    for (const item of this.receipt.payload.items) {
                        switch (item?.vat.type ?? 'none') {
                            case 'vat20':
                            case 'vat18':
                                vats.vat20 += this.extractVat(item.sum, item.vat.type)
                                break
                            case 'vat10':
                                vats.vat10 += this.extractVat(item.sum, item.vat.type)
                                break
                            case 'vat0':
                                vats.with_vat0 += item.sum
                                break
                            case 'none':
                                vats.without_vat += item.sum
                                break
                            case 'vat120':
                            case 'vat118':
                                vats.vat120 += this.extractVat(item.sum, item.vat.type)
                                break
                            case 'vat110':
                                vats.vat110 += this.extractVat(item.sum, item.vat.type)
                        }
                    }
                }

                return vats
            },

            fitContent(event) {
                event.target.style.height = (event.target.contentDocument.body.scrollHeight + 20) + 'px'
            },

            printReceipt() {
                let printWindow = window.open(
                    window.FiscalRegistrar.basePath + '/api/v1/receipts/' + this.receipt.id + '/preview',
                    'ReceiptPreview', 'popup,width=500,height=1000'
                )
                printWindow.onafterprint = printWindow.close
                printWindow.onload = function() {
                    printWindow.setTimeout(printWindow.print)
                }
            },
        },

        computed: {
            title() {
                const action = !this.receipt.id ? 'Создание' : this.receipt.state === 0 ? 'Редактирование' : 'Просмотр'
                return action + ' кассового чека'
            },

            model() {
                return this.$deepModel(this.receipt.payload)
            },

            isClientEmailRequired() {
                return !this.receipt.payload.client.phone
            },

            isClientPhoneRequired() {
                return !this.receipt.payload.client.email
            },

            companyEmailPlaceholder() {
                return this.connections[this.receipt.connection]?.company?.email ?? 'contact@myshop.com'
            },

            companyNamePlaceholder() {
                return this.connections[this.receipt.connection]?.company?.name ?? 'ООО "Рога и Копыта"'
            },

            companyInnPlaceholder() {
                return this.connections[this.receipt.connection]?.company?.inn ?? '1234567890'
            },

            companyPaymentSitePlaceholder() {
                return this.connections[this.receipt.connection]?.company?.payment_site ?? 'https://www.myshop.com'
            },

            companyPaymentAddressPlaceholder() {
                return this.connections[this.receipt.connection]?.company?.payment_address ?? ''
            },

            vatsPlaceholder() {
                return Object.fromEntries(
                    Object.entries(this.getVats(!this.receipt.payload.vats)).map(([key, val]) => [key, String(val)])
                )
            },

            vatsWithoutVat: {
                get: function () {
                    return this.receipt.payload.vats?.without_vat ?? null
                },
                set: function (val) {
                    this.receipt.payload.vats ??= this.vats
                    this.receipt.payload.vats.without_vat = val || null
                }
            },

            vatsWithVat0: {
                get: function () {
                    return this.receipt.payload.vats?.with_vat0 ?? null
                },
                set: function (val) {
                    this.receipt.payload.vats ??= this.vats
                    this.receipt.payload.vats.with_vat0 = val || null
                }
            },

            vatsVat10: {
                get: function () {
                    return this.receipt.payload.vats?.vat10 ?? null
                },
                set: function (val) {
                    this.receipt.payload.vats ??= this.vats
                    this.receipt.payload.vats.vat10 = val || null
                }
            },

            vatsVat20: {
                get: function () {
                    return this.receipt.payload.vats?.vat20 ?? null
                },
                set: function (val) {
                    this.receipt.payload.vats ??= this.vats
                    this.receipt.payload.vats.vat20 = val || null
                }
            },

            vatsVat110: {
                get: function () {
                    return this.receipt.payload.vats?.vat110 ?? null
                },
                set: function (val) {
                    this.receipt.payload.vats ??= this.vats
                    this.receipt.payload.vats.vat110 = val || null
                }
            },

            vatsVat120: {
                get: function () {
                    return this.receipt.payload.vats?.vat120 ?? null
                },
                set: function (val) {
                    this.receipt.payload.vats ??= this.vats
                    this.receipt.payload.vats.vat120 = val || null
                }
            },

            agentType: {
                get: function () {
                    return this.receipt.payload.agent_info?.type ?? null
                },
                set: function (val) {
                    this.receipt.payload.agent_info ??= this.agentInfo
                    this.receipt.payload.agent_info.type = val || null
                }
            },

            payingAgentPhones: {
                get: function () {
                    return this.model['agent_info.paying_agent.phones']?.join('\n')
                },
                set: function (phones) {
                    phones = phones.split('\n')
                        .map(phone => phone.trim())
                        .filter(phone => phone != null && phone !== '')
                    this.model['agent_info.paying_agent.phones'] = phones.length ? phones : null
                }
            },

            receivePaymentsOperatorPhones: {
                get: function () {
                    return this.model['agent_info.receive_payments_operator.phones']?.join('\n')
                },
                set: function (phones) {
                    phones = phones.split('\n')
                        .map(phone => phone.trim())
                        .filter(phone => phone != null && phone !== '')
                    this.model['agent_info.receive_payments_operator.phones'] = phones.length ? phones : null
                }
            },

            moneyTransferOperatorPhones: {
                get: function () {
                    return this.model['agent_info.money_transfer_operator.phones']?.join('\n')
                },
                set: function (phones) {
                    phones = phones.split('\n')
                        .map(phone => phone.trim())
                        .filter(phone => phone != null && phone !== '')
                    this.model['agent_info.money_transfer_operator.phones'] = phones.length ? phones : null
                }
            },

            supplierPhones: {
                get: function () {
                    return this.model['supplier_info.phones']?.join('\n')
                },
                set: function (phones) {
                    phones = phones.split('\n')
                        .map(phone => phone.trim())
                        .filter(phone => phone != null && phone !== '')
                    this.model['supplier_info.phones'] = phones.length ? phones : null
                }
            },

            resultPayloadFnsSite() {
                return /^https?:\/\//i.test(this.receipt.result.payload.fns_site)
                    ? this.receipt.result.payload.fns_site
                    : 'https://' + this.receipt.result.payload.fns_site
            },
        },

        watch: {
            $route(to, from) {
               this.handleRoute(to, from)
            },

            'receipt.payload.items': {
                handler: function (items) {
                    this.receipt.payload.total = items.reduce((total, item) => total + item.sum, 0)
                },
                deep: true
            },

            'receipt.payload.vats': {
                handler: function (vats) {
                    if (vats === null) return
                    Object.values(vats).every(vat => vat === null) && (this.receipt.payload.vats = null)
                },
                deep: true
            },

            'receipt.payload.agent_info': {
                handler: function (agent_info) {
                    this.receipt.payload.agent_info = agent_info?.type === null ? null : this.emptify(agent_info)
                },
                deep: true
            },
        },
    }
</script>

<style scoped>
fieldset { margin: 0 }
</style>

<template>
    <div>
        <h2 class="text-center" v-if="ready">{{ title }}</h2>
        <b-form validated v-if="ready" @submit.prevent="saveReceipt">
            <div class="accordion" role="tablist">
                <b-card no-body class="mb-1">
                    <b-card-header header-tag="header" class="p-1" role="tab">
                        <b-button block v-b-toggle.receipt-attributes variant="info">Атрибуты документа</b-button>
                    </b-card-header>
                    <b-collapse id="receipt-attributes" visible accordion="receipt-accordion" role="tabpanel">
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
                                                <b-form-select id="receiptOperation" size="sm" required v-model="receipt.operation" :options="buildOptions(dictionary.operations)"></b-form-select>
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
                        <b-button block v-b-toggle.receipt-client variant="info">Данные клиента</b-button>
                    </b-card-header>
                    <b-collapse id="receipt-client" accordion="receipt-accordion" role="tabpanel">
                        <b-card-body>
                            <b-card-text>
                                В запросе обязательно должно быть заполнено хотя бы одно из полей: <code>email</code> или <code>phone</code>.<br />
                                Если заполнены оба поля, ОФД отправит электронный чек только на email.
                            </b-card-text>
                            <b-form-group :disabled="receipt.state !== 0">
                                <b-container fluid>
                                    <b-form-row class="my-1">
                                        <b-col align-self="end" lg="2" md="3" sm="4">
                                            <b-form-group label="Электронный адрес" label-for="clientEmail" description="тег 1008" :class="isClientEmailRequired && 'required'">
                                                <b-form-input id="clientEmail" type="email" size="sm" placeholder="user@domain.com" :required="isClientEmailRequired" v-model="receipt.payload.client.email"></b-form-input>
                                            </b-form-group>
                                        </b-col>
                                        <b-col align-self="end" lg="2" md="3" sm="4">
                                            <b-form-group label="Телефон" label-for="clientPhone" description="тег 1008" :class="isClientPhoneRequired && 'required'">
                                                <b-form-input id="clientPhone" type="tel" size="sm" placeholder="+79001234567" :required="isClientPhoneRequired" v-model="receipt.payload.client.phone"></b-form-input>
                                            </b-form-group>
                                        </b-col>
                                        <b-col align-self="end" lg="2" md="3" sm="4">
                                            <b-form-group label="Наименование" label-for="clientName" description="тег 1227">
                                                <b-form-input id="clientName" type="text" size="sm" placeholder="Иван Иванович Иванов" v-model="receipt.payload.client.name"></b-form-input>
                                            </b-form-group>
                                        </b-col>
                                        <b-col align-self="end" lg="2" md="3" sm="4">
                                            <b-form-group label="ИНН" label-for="clientInn" description="тег 1228">
                                                <b-form-input id="clientInn" type="text" size="sm" placeholder="1234567890" pattern="\d{10}|\d{12}" maxlength="12" v-model="receipt.payload.client.inn"></b-form-input>
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
                        <b-button block v-b-toggle.receipt-company variant="info">Данные организации</b-button>
                    </b-card-header>
                    <b-collapse id="receipt-company" accordion="receipt-accordion" role="tabpanel">
                        <b-card-body>
                            <b-card-text>
                                Оставьте поля пустыми для автоматической подстановки параметров из конфигурации подключения при проводке чека.
                            </b-card-text>
                            <b-form-group :disabled="receipt.state !== 0">
                                <b-container fluid>
                                    <b-form-row class="my-1">
                                        <b-col align-self="end" lg="2" md="3" sm="4">
                                            <b-form-group label="Электронный адрес" label-for="companyEmail" description="тег 1117">
                                                <b-form-input id="companyEmail" type="email" size="sm" :placeholder="companyEmailPlaceholder" v-model="receipt.payload.company.email"></b-form-input>
                                            </b-form-group>
                                        </b-col>
                                        <b-col align-self="end" lg="2" md="3" sm="4">
                                            <b-form-group label="Наименование" label-for="companyName" description="тег 1048">
                                                <b-form-input id="companyName" type="text" size="sm" :placeholder="companyNamePlaceholder" v-model="receipt.payload.company.name"></b-form-input>
                                            </b-form-group>
                                        </b-col>
                                        <b-col align-self="end" lg="2" md="3" sm="4">
                                            <b-form-group label="ИНН" label-for="companyInn" description="тег 1018">
                                                <b-form-input id="companyInn" type="text" size="sm" :placeholder="companyInnPlaceholder" pattern="\d{10}|\d{12}" maxlength="12" v-model="receipt.payload.company.inn"></b-form-input>
                                            </b-form-group>
                                        </b-col>
                                        <b-col align-self="end" lg="2" md="3" sm="4">
                                            <b-form-group label="Система налогообложения" label-for="taxSystem" description="тег 1055">
                                                <b-form-select id="taxSystem" size="sm" v-model="receipt.payload.company.tax_system" :options="taxSystemOptions"></b-form-select>
                                            </b-form-group>
                                        </b-col>
                                        <b-col align-self="end" lg="2" md="3" sm="4">
                                            <b-form-group label="Место расчетов" label-for="companyPaymentSite" description="тег 1187">
                                                <b-form-input id="companyPaymentSite" type="text" size="sm" :placeholder="companyPaymentSitePlaceholder" v-model="receipt.payload.company.payment_site"></b-form-input>
                                            </b-form-group>
                                        </b-col>
                                        <b-col align-self="end" lg="2" md="3" sm="4">
                                            <b-form-group label="Адрес расчетов" label-for="companyPaymentAddress" description="тег 1009">
                                                <b-form-input id="companyPaymentAddress" type="text" size="sm" :placeholder="companyPaymentAddressPlaceholder" v-model="receipt.payload.company.payment_address"></b-form-input>
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
                        <b-button block v-b-toggle.receipt-agent-supplier variant="info">Данные агента и поставщика</b-button>
                    </b-card-header>
                    <b-collapse id="receipt-agent-supplier" accordion="receipt-accordion" role="tabpanel">
                        <b-card-body>
                            <b-card-text>
                                TODO
                            </b-card-text>
                            <b-form-group :disabled="receipt.state !== 0">
                                <b-container fluid>
                                    <b-form-row class="my-1">
                                        <b-col align-self="end" lg="3" md="4" sm="6">
                                            <b-form-group label="Признак агента" label-for="agentType" description="тег 1057">
                                                <b-form-select id="agentType" size="sm" v-model="agentType" :options="agentTypeOptions"></b-form-select>
                                            </b-form-group>
                                        </b-col>
                                    </b-form-row>
                                </b-container>

                                <div class="accordion" role="tablist" v-if="agentType !== null">
                                    <b-card no-body class="mb-1">
                                        <b-card-header header-tag="header" class="p-1" role="tab">
                                            <b-button block v-b-toggle.receipt-paying-agent>Атрибуты платежного агента</b-button>
                                        </b-card-header>
                                        <b-collapse id="receipt-paying-agent" accordion="receipt-agent-supplier-accordion" role="tabpanel">
                                            <b-card-body>
                                                <b-container fluid>
                                                    <b-form-row class="my-1">
                                                        <b-col align-self="end" lg="3" md="4" sm="6">
                                                            <b-form-group label="Наименование операции" label-for="payingAgentOperation" description="тег 1044">
                                                                <b-form-input id="payingAgentOperation" type="text" size="sm" v-model="model['agent_info.paying_agent.operation']"></b-form-input>
                                                            </b-form-group>
                                                        </b-col>
                                                        <b-col align-self="end" lg="3" md="4" sm="6">
                                                            <b-form-group label="Телефон(ы)" label-for="payingAgentPhones" description="тег 1073">
                                                                <b-form-textarea id="payingAgentPhones" size="sm" max-rows="4" v-model="payingAgentPhones"></b-form-textarea>
                                                            </b-form-group>
                                                        </b-col>
                                                    </b-form-row>
                                                </b-container>
                                            </b-card-body>
                                        </b-collapse>
                                    </b-card>

                                    <b-card no-body class="mb-1">
                                        <b-card-header header-tag="header" class="p-1" role="tab">
                                            <b-button block v-b-toggle.receipt-receive-payments-operator>Атрибуты оператора по приему платежей</b-button>
                                        </b-card-header>
                                        <b-collapse id="receipt-receive-payments-operator" accordion="receipt-agent-supplier-accordion" role="tabpanel">
                                            <b-card-body>
                                                <b-container fluid>
                                                    <b-form-row class="my-1">
                                                        <b-col align-self="end" lg="3" md="4" sm="6">
                                                            <b-form-group label="Телефон(ы)" label-for="receivePaymentsOperatorPhones" description="тег 1074">
                                                                <b-form-textarea id="receivePaymentsOperatorPhones" size="sm" max-rows="4" v-model="receivePaymentsOperatorPhones"></b-form-textarea>
                                                            </b-form-group>
                                                        </b-col>
                                                    </b-form-row>
                                                </b-container>
                                            </b-card-body>
                                        </b-collapse>
                                    </b-card>

                                    <b-card no-body class="mb-1">
                                        <b-card-header header-tag="header" class="p-1" role="tab">
                                            <b-button block v-b-toggle.receipt-money-transfer-operator>Атрибуты оператора перевода</b-button>
                                        </b-card-header>
                                        <b-collapse id="receipt-money-transfer-operator" accordion="receipt-agent-supplier-accordion" role="tabpanel">
                                            <b-card-body>
                                                <b-container fluid>
                                                    <b-form-row class="my-1">
                                                        <b-col align-self="end" lg="3" md="4" sm="6">
                                                            <b-form-group label="Телефон(ы)" label-for="moneyTransferOperatorPhones" description="тег 1075">
                                                                <b-form-textarea id="moneyTransferOperatorPhones" size="sm" max-rows="4" v-model="moneyTransferOperatorPhones"></b-form-textarea>
                                                            </b-form-group>
                                                        </b-col>
                                                        <b-col align-self="end" lg="3" md="4" sm="6">
                                                            <b-form-group label="Наименование" label-for="moneyTransferOperatorName" description="тег 1026">
                                                                <b-form-input id="moneyTransferOperatorName" type="text" size="sm" v-model="model['agent_info.money_transfer_operator.name']"></b-form-input>
                                                            </b-form-group>
                                                        </b-col>
                                                        <b-col align-self="end" lg="3" md="4" sm="6">
                                                            <b-form-group label="Адрес" label-for="moneyTransferOperatorAddress" description="тег 1005">
                                                                <b-form-input id="moneyTransferOperatorAddress" type="text" size="sm" v-model="model['agent_info.money_transfer_operator.address']"></b-form-input>
                                                            </b-form-group>
                                                        </b-col>
                                                        <b-col align-self="end" lg="3" md="4" sm="6">
                                                            <b-form-group label="ИНН" label-for="moneyTransferOperatorInn" description="тег 1016">
                                                                <b-form-input id="moneyTransferOperatorInn" type="text" size="sm" pattern="\d{10}|\d{12}" maxlength="12" v-model="model['agent_info.money_transfer_operator.inn']"></b-form-input>
                                                            </b-form-group>
                                                        </b-col>
                                                    </b-form-row>
                                                </b-container>
                                            </b-card-body>
                                        </b-collapse>
                                    </b-card>

                                    <b-card no-body class="mb-1">
                                        <b-card-header header-tag="header" class="p-1" role="tab">
                                            <b-button block v-b-toggle.receipt-supplier>Атрибуты поставщика</b-button>
                                        </b-card-header>
                                        <b-collapse id="receipt-supplier" accordion="receipt-agent-supplier-accordion" role="tabpanel">
                                            <b-card-body>
                                                <b-container fluid>
                                                    <b-form-row class="my-1">
                                                        <b-col align-self="end" lg="3" md="4" sm="6">
                                                            <b-form-group label="Телефон(ы)" label-for="supplierPhones" description="тег 1171" class="required">
                                                                <b-form-textarea id="supplierPhones" size="sm" max-rows="4" required v-model="supplierPhones"></b-form-textarea>
                                                            </b-form-group>
                                                        </b-col>
                                                    </b-form-row>
                                                </b-container>
                                            </b-card-body>
                                        </b-collapse>
                                    </b-card>
                                </div>
                            </b-form-group>
                        </b-card-body>
                    </b-collapse>
                </b-card>

                <b-card no-body class="mb-1">
                    <b-card-header header-tag="header" class="p-1" role="tab">
                        <b-button block v-b-toggle.receipt-items variant="info">Позиции документа</b-button>
                    </b-card-header>
                    <b-collapse id="receipt-items" accordion="receipt-accordion" role="tabpanel">
                        <b-card-body>
                            <b-card-text>
                                В документе должна быть как минимум одна позиция.
                            </b-card-text>
                            <template v-for="(item, id) in receipt.payload.items">
                                <receipt-item :key="id" :item="item" :disabled="receipt.state !== 0" @remove="removeItem(id)"></receipt-item>
                                <hr />
                            </template>
                            <b-container fluid>
                                <b-form-row class="my-1">
                                    <b-col align-self="end" lg="1" md="2" sm="3">
                                        <b-form-group label="Всего на сумму" label-for="total" description="тег 1020" class="required">
                                            <b-form-input id="total" type="number" min="0" max="42949672.95" step=".01" size="sm" placeholder="0" required v-model="receipt.payload.total" :disabled="receipt.state !== 0"></b-form-input>
                                        </b-form-group>
                                    </b-col>
                                    <b-col class="mb-sm-3" align-self="end" lg="1" md="2" sm="3">
                                        <b-button class="mb-sm-4" variant="primary" size="sm" @click="addItem" :disabled="receipt.state !== 0">Добавить</b-button>
                                    </b-col>
                                </b-form-row>
                            </b-container>
                        </b-card-body>
                    </b-collapse>
                </b-card>

                <b-card no-body class="mb-1">
                    <b-card-header header-tag="header" class="p-1" role="tab">
                        <b-button block v-b-toggle.receipt-payments variant="info">Данные по оплате</b-button>
                    </b-card-header>
                    <b-collapse id="receipt-payments" accordion="receipt-accordion" role="tabpanel">
                        <b-card-body>
                            <b-card-text>
                                TODO
                            </b-card-text>
                            <b-form-group :disabled="receipt.state !== 0">
                                <b-container fluid>
                                    <b-form-row class="my-1">
                                        <b-col align-self="end" lg="2" md="3" sm="4">
                                            <b-form-group label="Наличными" label-for="paymentCash" description="тег 1031">
                                                <b-form-input id="paymentCash" type="number" min="0" max="42949672.95" step=".01" size="sm" placeholder="0" v-model="receipt.payload.payments.cash"></b-form-input>
                                            </b-form-group>
                                        </b-col>
                                        <b-col align-self="end" lg="2" md="3" sm="4">
                                            <b-form-group label="Безналичными" label-for="paymentElectronic" description="тег 1081">
                                                <b-form-input id="paymentElectronic" type="number" min="0" max="42949672.95" step=".01" size="sm" placeholder="0" v-model="receipt.payload.payments.electronic"></b-form-input>
                                            </b-form-group>
                                        </b-col>
                                        <b-col align-self="end" lg="2" md="3" sm="4">
                                            <b-form-group label="Предоплатой" label-for="paymentPrepaid" description="тег 1215">
                                                <b-form-input id="paymentPrepaid" type="number" min="0" max="42949672.95" step=".01" size="sm" placeholder="0" v-model="receipt.payload.payments.prepaid"></b-form-input>
                                            </b-form-group>
                                        </b-col>
                                        <b-col align-self="end" lg="2" md="3" sm="4">
                                            <b-form-group label="Постоплатой" label-for="paymentPostpaid" description="тег 1216">
                                                <b-form-input id="paymentPostpaid" type="number" min="0" max="42949672.95" step=".01" size="sm" placeholder="0" v-model="receipt.payload.payments.postpaid"></b-form-input>
                                            </b-form-group>
                                        </b-col>
                                        <b-col align-self="end" lg="2" md="3" sm="4">
                                            <b-form-group label="Встр. предст." label-for="paymentOther" description="тег 1217">
                                                <b-form-input id="paymentOther" type="number" min="0" max="42949672.95" step=".01" size="sm" placeholder="0" v-model="receipt.payload.payments.other"></b-form-input>
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
                        <b-button block v-b-toggle.receipt-vats variant="info">НДС на чек</b-button>
                    </b-card-header>
                    <b-collapse id="receipt-vats" accordion="receipt-accordion" role="tabpanel">
                        <b-card-body>
                            <b-card-text>
                                Необходимо передать либо сумму налога на позицию, либо сумму налога на чек. Если будет переданы и сумма налога на позицию и сумма налога на чек, сервис учтет только сумму налога на чек.
                                <span v-if="receipt.payload.vats">В данный момент используются суммы налога на чек. Для возврата к использованию сумм налога на позицию, очистите все поля в этом разделе.</span>
                                <span v-else>В данный момент используются суммы налога на позицию. В полях ниже можно увидеть их расчетные величины.</span>
                            </b-card-text>
                            <b-form-group :disabled="receipt.state !== 0">
                                <b-container fluid>
                                    <b-form-row class="my-1">
                                        <b-col align-self="end" lg="2" md="3" sm="4">
                                            <b-form-group label="Сумма расчета по чеку без НДС" label-for="withoutVat" description="тег 1105">
                                                <b-form-input id="withoutVat" type="number" min="0" max="42949672.95" step=".01" size="sm" :placeholder="vatsPlaceholder.without_vat" v-model.number="vatsWithoutVat"></b-form-input>
                                            </b-form-group>
                                        </b-col>
                                        <b-col align-self="end" lg="2" md="3" sm="4">
                                            <b-form-group label="Сумма расчета по чеку с НДС 0%" label-for="withVat0" description="тег 1104">
                                                <b-form-input id="withVat0" type="number" min="0" max="42949672.95" step=".01" size="sm" :placeholder="vatsPlaceholder.with_vat0" v-model.number="vatsWithVat0"></b-form-input>
                                            </b-form-group>
                                        </b-col>
                                        <b-col align-self="end" lg="2" md="3" sm="4">
                                            <b-form-group label="Сумма НДС чека по ставке 10%" label-for="vat10" description="тег 1103">
                                                <b-form-input id="vat10" type="number" min="0" max="42949672.95" step=".01" size="sm" :placeholder="vatsPlaceholder.vat10" v-model.number="vatsVat10"></b-form-input>
                                            </b-form-group>
                                        </b-col>
                                        <b-col align-self="end" lg="2" md="3" sm="4">
                                            <b-form-group label="Сумма НДС чека по ставке 20%" label-for="vat20" description="тег 1102">
                                                <b-form-input id="vat20" type="number" min="0" max="42949672.95" step=".01" size="sm" :placeholder="vatsPlaceholder.vat20" v-model.number="vatsVat20"></b-form-input>
                                            </b-form-group>
                                        </b-col>
                                        <b-col align-self="end" lg="2" md="3" sm="4">
                                            <b-form-group label="Сумма НДС чека по расч. ставке 10/110" label-for="vat110" description="тег 1107">
                                                <b-form-input id="vat110" type="number" min="0" max="42949672.95" step=".01" size="sm" :placeholder="vatsPlaceholder.vat110" v-model.number="vatsVat110"></b-form-input>
                                            </b-form-group>
                                        </b-col>
                                        <b-col align-self="end" lg="2" md="3" sm="4">
                                            <b-form-group label="Сумма НДС чека по расч. ставке 20/120" label-for="vat120" description="тег 1106">
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
                        <b-button block v-b-toggle.receipt-misc variant="info">Дополнительные реквизиты</b-button>
                    </b-card-header>
                    <b-collapse id="receipt-misc" accordion="receipt-accordion" role="tabpanel">
                        <b-card-body>
                            <b-form-group :disabled="receipt.state !== 0">
                                <b-container fluid>
                                    <b-form-row class="my-1">
                                        <b-col align-self="end" lg="2" md="3" sm="4">
                                            <b-form-group label="Доп. реквизит чека" label-for="additionalCheckProps" description="тег 1192">
                                                <b-form-input id="additionalCheckProps" type="text" size="sm" maxlength="16" v-model="receipt.payload.additional_check_props"></b-form-input>
                                            </b-form-group>
                                        </b-col>
                                        <b-col align-self="end" lg="3" md="5" sm="8">
                                            <b-form-group label="ФИО кассира" label-for="cashier" description="тег 1021">
                                                <b-form-input id="cashier" type="text" size="sm" maxlength="64" v-model="receipt.payload.cashier"></b-form-input>
                                            </b-form-group>
                                        </b-col>
                                        <b-col align-self="end" lg="3" md="4" sm="6">
                                            <b-form-group label="Наим. доп. рекв. пользователя" label-for="additionalUserPropsName" description="тег 1085">
                                                <b-form-input id="additionalUserPropsName" type="text" size="sm" maxlength="64" v-model="model['additional_user_props.name']"></b-form-input>
                                            </b-form-group>
                                        </b-col>
                                        <b-col align-self="end" lg="4" md="8" sm="12">
                                            <b-form-group label="Знач. доп. рекв. пользователя" label-for="additionalUserPropsValue" description="тег 1086">
                                                <b-form-input id="additionalUserPropsValue" type="text" size="sm" maxlength="256" v-model="model['additional_user_props.value']"></b-form-input>
                                            </b-form-group>
                                        </b-col>
                                    </b-form-row>
                                </b-container>
                            </b-form-group>
                        </b-card-body>
                    </b-collapse>
                </b-card>

                <b-card no-body class="mb-1" v-if="receipt.result">
                    <b-card-header header-tag="header" class="p-1" role="tab">
                        <b-button block v-b-toggle.receipt-result variant="info">Результат обработки</b-button>
                    </b-card-header>
                    <b-collapse id="receipt-result" accordion="receipt-accordion" role="tabpanel">
                        <b-card-body>
                            <b-card-sub-title class="mb-3">Информация поставщика</b-card-sub-title>
                            <b-container fluid>
                                <b-form-row>
                                    <b-col class="mb-3" lg="1" md="2" sm="3">
                                        <b-form-group description="Дата и время">
                                            {{ formatTime(receipt.result.timestamp) }}
                                        </b-form-group>
                                    </b-col>
                                    <b-col class="mb-3" lg="2" md="3" sm="4">
                                        <b-form-group description="Внутренний идентификатор">
                                            {{ receipt.result.internal_id }}
                                        </b-form-group>
                                    </b-col>
                                </b-form-row>
                            </b-container>
                            <b-card-sub-title class="mb-3" v-if="receipt.result.payload">Информация ОФД</b-card-sub-title>
                            <b-container fluid v-if="receipt.result.payload">
                                <b-form-row>
                                    <b-col class="mb-3" lg="1" md="2" sm="3">
                                        <b-form-group description="Дата и время (тег 1012)">
                                            {{ formatTime(receipt.result.payload.receipt_datetime, 'DD.MM.YYYY HH:mm') }}
                                        </b-form-group>
                                    </b-col>
                                    <b-col class="mb-3" lg="1" md="2" sm="3">
                                        <b-form-group description="Номер смены (тег 1038)">
                                            {{ receipt.result.payload.shift_number }}
                                        </b-form-group>
                                    </b-col>
                                    <b-col class="mb-3" lg="1" md="2" sm="3">
                                        <b-form-group description="Номер чека в смене (тег 1042)">
                                            {{ receipt.result.payload.fiscal_receipt_number }}
                                        </b-form-group>
                                    </b-col>
                                    <b-col class="mb-3" lg="1" md="2" sm="3">
                                        <b-form-group description="Сумма расчета (тег 1020)">
                                            {{ receipt.result.payload.total }}
                                        </b-form-group>
                                    </b-col>
                                    <b-col class="mb-3" lg="1" md="2" sm="3">
                                        <b-form-group description="Рег. номер ККТ (тег 1037)">
                                            {{ receipt.result.payload.ecr_registration_number }}
                                        </b-form-group>
                                    </b-col>
                                    <b-col class="mb-3" lg="1" md="2" sm="3">
                                        <b-form-group description="Номер ФН (тег 1041)">
                                            {{ receipt.result.payload.fn_number }}
                                        </b-form-group>
                                    </b-col>
                                    <b-col class="mb-3" lg="1" md="2" sm="3">
                                        <b-form-group description="Фискальный номер документа (тег 1040)">
                                            {{ receipt.result.payload.fiscal_document_number }}
                                        </b-form-group>
                                    </b-col>
                                    <b-col class="mb-3" lg="1" md="2" sm="3">
                                        <b-form-group description="Фискальный признак документа (тег 1077)">
                                            {{ receipt.result.payload.fiscal_document_attribute }}
                                        </b-form-group>
                                    </b-col>
                                    <b-col class="mb-3" lg="2" md="3" sm="4">
                                        <b-form-group description="Адрес сайта ФНС (тег 1060)">
                                            <b-link :href="resultPayloadFnsSite" target="_blank">{{ resultPayloadFnsSite }}</b-link>
                                        </b-form-group>
                                    </b-col>
                                </b-form-row>
                            </b-container>
                        </b-card-body>
                    </b-collapse>
                </b-card>

                <b-card no-body class="mb-1" v-if="receipt.id">
                    <b-card-header header-tag="header" class="p-1" role="tab">
                        <b-button block v-b-toggle.receipt-preview variant="info">Просмотр чека</b-button>
                    </b-card-header>
                    <b-collapse id="receipt-preview" @show="showPreview = true" accordion="receipt-accordion" role="tabpanel">
                        <b-card-body class="px-0">
                            <iframe v-if="showPreview" :src="FiscalRegistrar.basePath + '/api/v1/receipts/' + receipt.id + '/preview'" width="100%" @load="fitContent"></iframe>
                        </b-card-body>
                    </b-collapse>
                </b-card>
            </div>
            <b-container fluid>
                <b-form-row class="my-3">
                    <b-col sm="8">
                        <b-button v-if="receipt.state === 0" class="mb-1" type="submit" variant="primary" size="sm">Сохранить</b-button>
                        <b-button v-if="receipt.id" class="mb-1" variant="primary" size="sm" :to="{ name: 'new-receipt-from-existing', params: { id: receipt.id } }">Дублировать</b-button>
                        <b-button v-if="receipt.state === 0" class="mb-1" variant="success" size="sm" @click="registerReceipt">Зарегистрировать</b-button>
                        <b-button v-if="receipt.state === 1" class="mb-1" variant="warning" size="sm" @click="syncReceipt">Синхронизировать</b-button>
                        <b-button v-if="receipt.id" class="mb-1" size="sm" @click="printReceipt">Распечатать</b-button>
                        <b-button v-if="receipt.result" class="mb-1" size="sm" :href="receipt.result.payload.ofd_receipt_url" target="_blank">Просмотреть в ОФД</b-button>
                        <b-button v-if="receipt.id && receipt.state === 0" class="mb-1" variant="danger" size="sm" @click="deleteReceipt">Удалить</b-button>
                    </b-col>
                    <b-col class="text-right" sm="4">
                        <b-button class="mb-1" size="sm" :to="{ name: 'receipts' }">К списку</b-button>
                    </b-col>
                </b-form-row>
            </b-container>
        </b-form>
    </div>
</template>
