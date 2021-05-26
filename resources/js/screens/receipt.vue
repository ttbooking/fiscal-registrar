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
                                <label for="companyPaymentSite" class="form-label">Место расчетов</label>
                                <input type="text" class="form-control form-control-sm" id="companyPaymentSite" v-model="receipt.data.company.payment_site" />
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
                        <div class="accordion-body">

                            <div v-for="(item, id) in receipt.data.items" class="row">
                                <div class="col-sm-4 mb-3">
                                    <label :for="'item' + (id+1) + 'Name'" class="form-label">Наименование</label>
                                    <input type="text" class="form-control form-control-sm" :id="'item' + (id+1) + 'Name'" v-model="item.name" />
                                </div>
                                <div class="col-sm-2 mb-3">
                                    <label :for="'item' + (id+1) + 'Price'" class="form-label">Цена</label>
                                    <input type="number" class="form-control form-control-sm" :id="'item' + (id+1) + 'Price'" v-model="item.price" />
                                </div>
                                <div class="col-sm-2 mb-3">
                                    <label :for="'item' + (id+1) + 'Quantity'" class="form-label">Кол-во</label>
                                    <input type="number" class="form-control form-control-sm" :id="'item' + (id+1) + 'Quantity'" v-model="item.quantity" />
                                </div>
                                <div class="col-sm-2 mb-3">
                                    <label :for="'item' + (id+1) + 'Sum'" class="form-label">Сумма</label>
                                    <input type="number" class="form-control form-control-sm" :id="'item' + (id+1) + 'Sum'" v-model="item.price" />
                                </div>
                                <div class="col-sm-2 mb-3">
                                    <label :for="'item' + (id+1) + 'MeasurementUnit'" class="form-label">Ед. изм.</label>
                                    <input type="text" class="form-control form-control-sm" :id="'item' + (id+1) + 'MeasurementUnit'" v-model="item.measurement_unit" />
                                </div>
                                <div class="col-sm-auto mb-3">
                                    <label :for="'item' + (id+1) + 'PaymentMethod'" class="form-label">Способ расчета</label>
                                    <select class="form-select form-select-sm" :id="'item' + (id+1) + 'PaymentMethod'" v-model="item.payment_method">
                                        <option value="full_prepayment">предоплата 100%</option>
                                        <option value="prepayment">предоплата</option>
                                        <option value="advance">аванс</option>
                                        <option value="full_payment">полный расчет</option>
                                        <option value="partial_payment">частичный расчет и кредит</option>
                                        <option value="credit">передача в кредит</option>
                                        <option value="credit_payment">оплата кредита</option>
                                    </select>
                                </div>
                                <div class="col-sm-auto mb-3">
                                    <label :for="'item' + (id+1) + 'PaymentObject'" class="form-label">Предмет расчета</label>
                                    <select class="form-select form-select-sm" :id="'item' + (id+1) + 'PaymentObject'" v-model="item.payment_object">
                                        <option value="commodity">товар</option>
                                        <option value="excise">подакцизный товар</option>
                                        <option value="job">работа</option>
                                        <option value="service">услуга</option>
                                        <option value="gambling_bet">ставка азартной игры</option>
                                        <option value="gambling_prize">выигрыш азартной игры</option>
                                        <option value="lottery">лотерейный билет</option>
                                        <option value="lottery_prize">выигрыш лотереи</option>
                                        <option value="intellectual_activity">предоставление результатов интеллектуальной деятельности</option>
                                        <option value="payment">платеж</option>
                                        <option value="agent_commission">агентское вознаграждение</option>
                                        <option value="award">взнос/штраф/вознаграждение/бонус</option>
                                        <option value="composite">составной предмет расчета</option>
                                        <option value="another">иной предмет расчета</option>
                                        <option value="property_right">имущественное право</option>
                                        <option value="non-operating_gain">внереализационный доход</option>
                                        <option value="insurance_premium">страховые взносы</option>
                                        <option value="sales_tax">торговый сбор</option>
                                        <option value="resort_fee">курортный сбор</option>
                                        <option value="deposit">залог</option>
                                        <option value="expense">расход</option>
                                        <option value="pension_insurance_ip">взносы на ОПС ИП</option>
                                        <option value="pension_insurance">взносы на ОПС</option>
                                        <option value="medical_insurance_ip">взносы на ОМС ИП</option>
                                        <option value="medical_insurance">взносы на ОМС</option>
                                        <option value="social_insurance">взносы на ОСС</option>
                                        <option value="casino_payment">платеж казино</option>
                                    </select>
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
                        <div class="accordion-body">
                            <div class="row">
                                <div class="col-sm-auto mb-3">
                                    <!--<label for="agentType" class="form-label">Тип агента</label>
                                    <select class="form-select form-select-sm" id="agentType" v-model="receipt.data.agent_info.type">
                                        <option value="bank_paying_agent">банковский платежный агент</option>
                                        <option value="bank_paying_subagent">банковский платежный субагент</option>
                                        <option value="paying_agent">платежный агент</option>
                                        <option value="paying_subagent">платежный субагент</option>
                                        <option value="attorney">поверенный</option>
                                        <option value="commission_agent">комиссионер</option>
                                        <option value="another">другой</option>
                                    </select>-->
                                </div>
                            </div>
                            <div class="row">

                            </div>
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
