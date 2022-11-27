<script>
export default {
    props: ["item", "disabled"],

    computed: {
        id() {
            return this.$vnode.key + 1;
        },

        model() {
            return this.$deepModel(this.item);
        },

        vatSum() {
            return String(this.extractVat(this.item.sum, this.item.vat.type));
        },

        agentType: {
            get: function () {
                return this.model["agent_info.type"] ?? null;
            },
            set: function (val) {
                this.model["agent_info.type"] = val;
            },
        },

        payingAgentPhones: {
            get: function () {
                return this.model["agent_info.paying_agent.phones"]?.join("\n");
            },
            set: function (phones) {
                phones = phones
                    .split("\n")
                    .map((phone) => phone.trim())
                    .filter((phone) => phone != null && phone !== "");
                this.model["agent_info.paying_agent.phones"] = phones.length ? phones : null;
            },
        },

        receivePaymentsOperatorPhones: {
            get: function () {
                return this.model["agent_info.receive_payments_operator.phones"]?.join("\n");
            },
            set: function (phones) {
                phones = phones
                    .split("\n")
                    .map((phone) => phone.trim())
                    .filter((phone) => phone != null && phone !== "");
                this.model["agent_info.receive_payments_operator.phones"] = phones.length ? phones : null;
            },
        },

        moneyTransferOperatorPhones: {
            get: function () {
                return this.model["agent_info.money_transfer_operator.phones"]?.join("\n");
            },
            set: function (phones) {
                phones = phones
                    .split("\n")
                    .map((phone) => phone.trim())
                    .filter((phone) => phone != null && phone !== "");
                this.model["agent_info.money_transfer_operator.phones"] = phones.length ? phones : null;
            },
        },

        supplierPhones: {
            get: function () {
                return this.model["supplier_info.phones"]?.join("\n");
            },
            set: function (phones) {
                phones = phones
                    .split("\n")
                    .map((phone) => phone.trim())
                    .filter((phone) => phone != null && phone !== "");
                this.model["supplier_info.phones"] = phones.length ? phones : null;
            },
        },
    },

    watch: {
        "item.price": function (price) {
            this.item.sum = price * this.item.quantity;
        },

        "item.quantity": function (quantity) {
            this.item.sum = this.item.price * quantity;
        },

        "item.sum": function (sum) {
            this.item.vat.sum = this.extractVat(sum, this.item.vat.type);
        },

        "item.vat.type": function (vatType) {
            this.item.vat.sum = this.extractVat(this.item.sum, vatType);
        },

        "item.agent_info": {
            handler: function (agent_info) {
                this.item.agent_info = agent_info?.type === null ? null : this.emptify(agent_info);
            },
            deep: true,
        },

        "item.supplier_info": {
            handler: function (supplier_info) {
                this.item.supplier_info = supplier_info?.type === null ? null : this.emptify(supplier_info);
            },
            deep: true,
        },
    },
};
</script>

<template>
    <b-container fluid>
        <b-form-row class="my-1">
            <b-col align-self="end" lg="4" md="6" sm="12">
                <b-form-group
                    label="Наименование"
                    :label-for="'item' + id + 'Name'"
                    description="тег 1030"
                    class="required"
                >
                    <b-form-input
                        :id="'item' + id + 'Name'"
                        type="text"
                        size="sm"
                        required
                        v-model="item.name"
                        :disabled="disabled"
                    ></b-form-input>
                </b-form-group>
            </b-col>
            <b-col align-self="end" lg="1" md="2" sm="4">
                <b-form-group label="Цена" :label-for="'item' + id + 'Price'" description="тег 1079" class="required">
                    <b-form-input
                        :id="'item' + id + 'Price'"
                        type="number"
                        min="0"
                        max="42949672.95"
                        step=".01"
                        size="sm"
                        placeholder="0"
                        required
                        v-model.number="item.price"
                        :disabled="disabled"
                    ></b-form-input>
                </b-form-group>
            </b-col>
            <b-col align-self="end" lg="1" md="2" sm="4">
                <b-form-group
                    label="Кол-во"
                    :label-for="'item' + id + 'Quantity'"
                    description="тег 1023"
                    class="required"
                >
                    <b-form-input
                        :id="'item' + id + 'Quantity'"
                        type="number"
                        min=".001"
                        max="99999.999"
                        step="any"
                        size="sm"
                        placeholder="1"
                        required
                        v-model.number="item.quantity"
                        :disabled="disabled"
                    ></b-form-input>
                </b-form-group>
            </b-col>
            <b-col align-self="end" lg="1" md="2" sm="4">
                <b-form-group label="Сумма" :label-for="'item' + id + 'Sum'" description="тег 1043" class="required">
                    <b-form-input
                        :id="'item' + id + 'Sum'"
                        type="number"
                        min="0"
                        max="42949672.95"
                        step=".01"
                        size="sm"
                        placeholder="0"
                        required
                        v-model.number="item.sum"
                        :disabled="disabled"
                    ></b-form-input>
                </b-form-group>
            </b-col>
            <b-col align-self="end" lg="1" md="2" sm="4">
                <b-form-group label="Ед. изм." :label-for="'item' + id + 'MeasurementUnit'" description="тег 1197">
                    <b-form-input
                        :id="'item' + id + 'MeasurementUnit'"
                        type="text"
                        size="sm"
                        placeholder="шт."
                        maxlength="16"
                        v-model="item.measurement_unit"
                        :disabled="disabled"
                    ></b-form-input>
                </b-form-group>
            </b-col>
            <b-col align-self="end" lg="2" md="2" sm="4">
                <b-form-group
                    label="Способ расчета"
                    :label-for="'item' + id + 'PaymentMethod'"
                    description="тег 1214"
                    class="required"
                >
                    <b-form-select
                        :id="'item' + id + 'PaymentMethod'"
                        size="sm"
                        v-model="item.payment_method"
                        :options="buildOptions(dictionary.paymentMethods)"
                        :disabled="disabled"
                    ></b-form-select>
                </b-form-group>
            </b-col>
            <b-col align-self="end" lg="2" md="2" sm="4">
                <b-form-group
                    label="Предмет расчета"
                    :label-for="'item' + id + 'PaymentObject'"
                    description="тег 1212"
                    class="required"
                >
                    <b-form-select
                        :id="'item' + id + 'PaymentObject'"
                        size="sm"
                        v-model="item.payment_object"
                        :options="buildOptions(dictionary.paymentObjects)"
                        :disabled="disabled"
                    ></b-form-select>
                </b-form-group>
            </b-col>
            <b-col align-self="end" lg="1" md="2" sm="4">
                <b-form-group label="НДС" :label-for="'item' + id + 'VatType'" description="тег 1199" class="required">
                    <b-form-select
                        :id="'item' + id + 'VatType'"
                        size="sm"
                        v-model="item.vat.type"
                        :options="buildOptions(dictionary.vatTypes)"
                        :disabled="disabled"
                    ></b-form-select>
                </b-form-group>
            </b-col>
            <b-col align-self="end" lg="1" md="2" sm="4">
                <b-form-group label="Сумма НДС" :label-for="'item' + id + 'VatSum'" description="тег 1200">
                    <b-form-input
                        :id="'item' + id + 'VatSum'"
                        type="number"
                        min="0"
                        max="42949672.95"
                        step=".01"
                        size="sm"
                        :placeholder="vatSum"
                        v-model.number="item.vat.sum"
                        :disabled="disabled"
                    ></b-form-input>
                </b-form-group>
            </b-col>
            <b-col align-self="end" lg="3" md="4" sm="6">
                <b-form-group label="Признак агента" :label-for="'item' + id + 'agentType'" description="тег 1222">
                    <b-form-select
                        :id="'item' + id + 'agentType'"
                        size="sm"
                        v-model="agentType"
                        :options="agentTypeOptions"
                        :disabled="disabled"
                    ></b-form-select>
                </b-form-group>
            </b-col>
            <b-col align-self="end" lg="1" md="1" sm="1" class="ml-auto mr-0">
                <b-form-group class="text-right">
                    <b-button variant="danger" size="sm" title="Удалить" @click="$emit('remove')" :disabled="disabled"
                        ><b>&times;</b></b-button
                    >
                </b-form-group>
            </b-col>
        </b-form-row>
        <div class="accordion" role="tablist" v-if="agentType !== null">
            <b-card no-body class="mb-1">
                <b-card-header header-tag="header" class="p-1" role="tab">
                    <b-button block v-b-toggle="'receipt-item-' + id + '-paying-agent'"
                        >Атрибуты платежного агента</b-button
                    >
                </b-card-header>
                <b-collapse
                    :id="'receipt-item-' + id + '-paying-agent'"
                    :accordion="'receipt-item-' + id + '-agent-supplier-accordion'"
                    role="tabpanel"
                >
                    <b-card-body>
                        <b-container fluid>
                            <b-form-row class="my-1">
                                <b-col align-self="end" lg="3" md="4" sm="6">
                                    <b-form-group
                                        label="Наименование операции"
                                        :label-for="'item' + id + 'payingAgentOperation'"
                                        description="тег 1044"
                                    >
                                        <b-form-input
                                            :id="'item' + id + 'payingAgentOperation'"
                                            type="text"
                                            size="sm"
                                            v-model="model['agent_info.paying_agent.operation']"
                                            :disabled="disabled"
                                        ></b-form-input>
                                    </b-form-group>
                                </b-col>
                                <b-col align-self="end" lg="3" md="4" sm="6">
                                    <b-form-group
                                        label="Телефон(ы)"
                                        :label-for="'item' + id + 'payingAgentPhones'"
                                        description="тег 1073"
                                    >
                                        <b-form-textarea
                                            :id="'item' + id + 'payingAgentPhones'"
                                            size="sm"
                                            max-rows="4"
                                            v-model="payingAgentPhones"
                                            :disabled="disabled"
                                        ></b-form-textarea>
                                    </b-form-group>
                                </b-col>
                            </b-form-row>
                        </b-container>
                    </b-card-body>
                </b-collapse>
            </b-card>

            <b-card no-body class="mb-1">
                <b-card-header header-tag="header" class="p-1" role="tab">
                    <b-button block v-b-toggle="'receipt-item-' + id + '-receive-payments-operator'"
                        >Атрибуты оператора по приему платежей</b-button
                    >
                </b-card-header>
                <b-collapse
                    :id="'receipt-item-' + id + '-receive-payments-operator'"
                    :accordion="'receipt-item-' + id + '-agent-supplier-accordion'"
                    role="tabpanel"
                >
                    <b-card-body>
                        <b-container fluid>
                            <b-form-row class="my-1">
                                <b-col align-self="end" lg="3" md="4" sm="6">
                                    <b-form-group
                                        label="Телефон(ы)"
                                        :label-for="'item' + id + 'receivePaymentsOperatorPhones'"
                                        description="тег 1074"
                                    >
                                        <b-form-textarea
                                            :id="'item' + id + 'receivePaymentsOperatorPhones'"
                                            size="sm"
                                            max-rows="4"
                                            v-model="receivePaymentsOperatorPhones"
                                            :disabled="disabled"
                                        ></b-form-textarea>
                                    </b-form-group>
                                </b-col>
                            </b-form-row>
                        </b-container>
                    </b-card-body>
                </b-collapse>
            </b-card>

            <b-card no-body class="mb-1">
                <b-card-header header-tag="header" class="p-1" role="tab">
                    <b-button block v-b-toggle="'receipt-item-' + id + '-money-transfer-operator'"
                        >Атрибуты оператора перевода</b-button
                    >
                </b-card-header>
                <b-collapse
                    :id="'receipt-item-' + id + '-money-transfer-operator'"
                    :accordion="'receipt-item-' + id + '-agent-supplier-accordion'"
                    role="tabpanel"
                >
                    <b-card-body>
                        <b-container fluid>
                            <b-form-row class="my-1">
                                <b-col align-self="end" lg="3" md="4" sm="6">
                                    <b-form-group
                                        label="Телефон(ы)"
                                        :label-for="'item' + id + 'moneyTransferOperatorPhones'"
                                        description="тег 1075"
                                    >
                                        <b-form-textarea
                                            :id="'item' + id + 'moneyTransferOperatorPhones'"
                                            size="sm"
                                            max-rows="4"
                                            v-model="moneyTransferOperatorPhones"
                                            :disabled="disabled"
                                        ></b-form-textarea>
                                    </b-form-group>
                                </b-col>
                                <b-col align-self="end" lg="3" md="4" sm="6">
                                    <b-form-group
                                        label="Наименование"
                                        :label-for="'item' + id + 'moneyTransferOperatorName'"
                                        description="тег 1026"
                                    >
                                        <b-form-input
                                            :id="'item' + id + 'moneyTransferOperatorName'"
                                            type="text"
                                            size="sm"
                                            v-model="model['agent_info.money_transfer_operator.name']"
                                            :disabled="disabled"
                                        ></b-form-input>
                                    </b-form-group>
                                </b-col>
                                <b-col align-self="end" lg="3" md="4" sm="6">
                                    <b-form-group
                                        label="Адрес"
                                        :label-for="'item' + id + 'moneyTransferOperatorAddress'"
                                        description="тег 1005"
                                    >
                                        <b-form-input
                                            :id="'item' + id + 'moneyTransferOperatorAddress'"
                                            type="text"
                                            size="sm"
                                            v-model="model['agent_info.money_transfer_operator.address']"
                                            :disabled="disabled"
                                        ></b-form-input>
                                    </b-form-group>
                                </b-col>
                                <b-col align-self="end" lg="3" md="4" sm="6">
                                    <b-form-group
                                        label="ИНН"
                                        :label-for="'item' + id + 'moneyTransferOperatorInn'"
                                        description="тег 1016"
                                    >
                                        <b-form-input
                                            :id="'item' + id + 'moneyTransferOperatorInn'"
                                            type="text"
                                            size="sm"
                                            pattern="\d{10}|\d{12}"
                                            maxlength="12"
                                            v-model="model['agent_info.money_transfer_operator.inn']"
                                            :disabled="disabled"
                                        ></b-form-input>
                                    </b-form-group>
                                </b-col>
                            </b-form-row>
                        </b-container>
                    </b-card-body>
                </b-collapse>
            </b-card>

            <b-card no-body class="mb-1">
                <b-card-header header-tag="header" class="p-1" role="tab">
                    <b-button block v-b-toggle="'receipt-item-' + id + '-supplier'">Атрибуты поставщика</b-button>
                </b-card-header>
                <b-collapse
                    :id="'receipt-item-' + id + '-supplier'"
                    :accordion="'receipt-item-' + id + '-agent-supplier-accordion'"
                    role="tabpanel"
                >
                    <b-card-body>
                        <b-container fluid>
                            <b-form-row class="my-1">
                                <b-col align-self="end" lg="3" md="4" sm="6">
                                    <b-form-group
                                        label="Телефон(ы)"
                                        :label-for="'item' + id + 'supplierPhones'"
                                        description="тег 1171"
                                        class="required"
                                    >
                                        <b-form-textarea
                                            :id="'item' + id + 'supplierPhones'"
                                            size="sm"
                                            max-rows="4"
                                            required
                                            v-model="supplierPhones"
                                            :disabled="disabled"
                                        ></b-form-textarea>
                                    </b-form-group>
                                </b-col>
                                <b-col align-self="end" lg="3" md="4" sm="6">
                                    <b-form-group
                                        label="Наименование"
                                        :label-for="'item' + id + 'supplierName'"
                                        description="тег 1225"
                                    >
                                        <b-form-input
                                            :id="'item' + id + 'supplierName'"
                                            type="text"
                                            size="sm"
                                            v-model="model['supplier_info.name']"
                                            :disabled="disabled"
                                        ></b-form-input>
                                    </b-form-group>
                                </b-col>
                                <b-col align-self="end" lg="3" md="4" sm="6">
                                    <b-form-group
                                        label="ИНН"
                                        :label-for="'item' + id + 'supplierInn'"
                                        description="тег 1226"
                                    >
                                        <b-form-input
                                            :id="'item' + id + 'supplierInn'"
                                            type="text"
                                            size="sm"
                                            pattern="\d{10}|\d{12}"
                                            maxlength="12"
                                            v-model="model['supplier_info.inn']"
                                            :disabled="disabled"
                                        ></b-form-input>
                                    </b-form-group>
                                </b-col>
                            </b-form-row>
                        </b-container>
                    </b-card-body>
                </b-collapse>
            </b-card>
        </div>
    </b-container>
</template>
