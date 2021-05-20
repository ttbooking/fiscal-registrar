<script type="text/ecmascript-6">
    export default {
        data() {
            return {
                ready: false,
                receipt: {}
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
    <div>
        <!--<h4>{{ this.$root.message }}</h4>
        <p>{{ $t('message.hello') }}</p>
        <span v-if="!ready">Loading...</span>
        <span v-if="ready">{{ this.receipt.internal_id }}</span>-->

        <form v-if="ready" v-on:keyup.enter="saveReceipt">
            <div id="receiptAccordion" class="accordion mb-3">

                <div class="accordion-item">
                    <h2 id="clientDataHeading" class="accordion-header">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#clientDataCollapse" aria-expanded="true" aria-controls="clientDataCollapse">
                            Данные клиента
                        </button>
                    </h2>
                    <div id="clientDataCollapse" class="accordion-collapse collapse show" aria-labelledby="clientDataHeading">
                        <div class="accordion-body row">
                            <div class="col-sm-auto mb-3">
                                <label for="clientEmail" class="form-label">Электронный адрес</label>
                                <input type="email" class="form-control form-control-sm" id="clientEmail" placeholder="user@domain.com" v-model="receipt.data.client.email" />
                            </div>
                            <div class="col-sm-auto mb-3">
                                <label for="clientPhone" class="form-label">Телефон</label>
                                <input type="tel" class="form-control form-control-sm" id="clientPhone" placeholder="+79001234567" v-model="receipt.data.client.phone" />
                            </div>
                            <div class="col-sm-auto mb-3">
                                <label for="clientName" class="form-label">Наименование</label>
                                <input type="text" class="form-control form-control-sm" id="clientName" placeholder="Иван Иванович Иванов" v-model="receipt.data.client.name" />
                            </div>
                            <div class="col-sm-auto mb-3">
                                <label for="clientInn" class="form-label">ИНН</label>
                                <input type="text" class="form-control form-control-sm" id="clientInn" placeholder="1234567890" pattern="\d{10}|\d{12}" maxlength="12" v-model="receipt.data.client.inn" />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h2 id="companyDataHeading" class="accordion-header">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#companyDataCollapse" aria-expanded="true" aria-controls="companyDataCollapse">
                            Данные организации
                        </button>
                    </h2>
                    <div id="companyDataCollapse" class="accordion-collapse collapse show" aria-labelledby="companyDataHeading">
                        <!--<div class="accordion-body row">
                            <div class="col-sm-auto mb-3">
                                <label for="companyEmail" class="form-label">Электронный адрес</label>
                                <input type="email" class="form-control form-control-sm" id="companyEmail" placeholder="user@domain.com" v-model="receipt.data.company.email" />
                            </div>
                            <div class="col-sm-auto mb-3">
                                <label for="companyInn" class="form-label">ИНН</label>
                                <input type="text" class="form-control form-control-sm" id="companyInn" placeholder="1234567890" pattern="\d{10}|\d{12}" maxlength="12" v-model="receipt.data.company.inn" />
                            </div>
                            <div class="col-sm-auto mb-3">
                                <label for="companyPaymentAddress" class="form-label">Место расчетов</label>
                                <input type="text" class="form-control form-control-sm" id="companyPaymentAddress" v-model="receipt.data.company.payment_address" />
                            </div>
                        </div>-->
                    </div>
                </div>

                <div class="accordion-item">
                    <h2 id="itemDataHeading" class="accordion-header">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#itemDataCollapse" aria-expanded="true" aria-controls="itemDataCollapse">
                            Позиции документа
                        </button>
                    </h2>
                    <div id="itemDataCollapse" class="accordion-collapse collapse show" aria-labelledby="itemDataHeading">
                        <div class="accordion-body row">

                            <div v-for="item in receipt.data.items">
                                <div class="col-sm-auto mb-3">
                                    <label for="itemName" class="form-label">Наименование</label>
                                    <input type="text" class="form-control form-control-sm" id="itemName" v-model="item.name" />
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h2 id="paymentDataHeading" class="accordion-header">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#paymentDataCollapse" aria-expanded="true" aria-controls="paymentDataCollapse">
                            Данные по оплате
                        </button>
                    </h2>
                    <div id="paymentDataCollapse" class="accordion-collapse collapse show" aria-labelledby="paymentDataHeading">
                        <div class="accordion-body row">
                            <div class="col-sm-auto mb-3">
                                <label for="paymentCash" class="form-label">Наличными</label>
                                <input type="number" class="form-control form-control-sm" id="paymentCash" placeholder="0" v-model="receipt.data.payments.cash" />
                            </div>
                            <div class="col-sm-auto mb-3">
                                <label for="paymentElectronic" class="form-label">Безналичными</label>
                                <input type="number" class="form-control form-control-sm" id="paymentElectronic" placeholder="0" v-model="receipt.data.payments.electronic" />
                            </div>
                            <div class="col-sm-auto mb-3">
                                <label for="paymentPrepaid" class="form-label">Предоплатой</label>
                                <input type="number" class="form-control form-control-sm" id="paymentPrepaid" placeholder="0" v-model="receipt.data.payments.prepaid" />
                            </div>
                            <div class="col-sm-auto mb-3">
                                <label for="paymentPostpaid" class="form-label">Постоплатой</label>
                                <input type="number" class="form-control form-control-sm" id="paymentPostpaid" placeholder="0" v-model="receipt.data.payments.postpaid" />
                            </div>
                            <div class="col-sm-auto mb-3">
                                <label for="paymentOther" class="form-label">Встречным представлением</label>
                                <input type="number" class="form-control form-control-sm" id="paymentOther" placeholder="0" v-model="receipt.data.payments.other" />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h2 id="agentDataHeading" class="accordion-header">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#agentDataCollapse" aria-expanded="true" aria-controls="agentDataCollapse">
                            Данные агента
                        </button>
                    </h2>
                    <div id="agentDataCollapse" class="accordion-collapse collapse show" aria-labelledby="agentDataHeading">
                        <div class="accordion-body row">
                            Agent Data
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h2 id="supplierDataHeading" class="accordion-header">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#supplierDataCollapse" aria-expanded="true" aria-controls="supplierDataCollapse">
                            Данные поставщика
                        </button>
                    </h2>
                    <div id="supplierDataCollapse" class="accordion-collapse collapse show" aria-labelledby="supplierDataHeading">
                        <div class="accordion-body row">
                            Supplier Data
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h2 id="miscInfoHeading" class="accordion-header">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#miscInfoCollapse" aria-expanded="true" aria-controls="miscInfoCollapse">
                            Прочая информация
                        </button>
                    </h2>
                    <div id="miscInfoCollapse" class="accordion-collapse collapse show" aria-labelledby="miscInfoHeading">
                        <div class="accordion-body row">
                            Misc Info
                        </div>
                    </div>
                </div>

            </div>
            <button type="button" class="btn btn-sm btn-primary" v-on:click="saveReceipt">Save</button>
        </form>

    </div>
</template>
