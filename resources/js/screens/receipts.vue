<script>
    import Receipt from '../models/Receipt'

    export default {
        data() {
            return {
                fields: [
                    { key: 'id', label: '#', sortable: true },
                    { key: 'created_at', label: 'Время', sortable: true },
                    { key: 'connection', label: 'Соединение', sortable: true },
                    { key: 'operation', label: 'Тип', sortable: true },
                    { key: 'payload.total', label: 'Сумма', sortable: true },
                    { key: 'state', label: 'Статус', sortable: true },
                ],
                aliases: {
                    'payload.total': 'total',
                },
                receipts: {},
                page: 1,
            }
        },

        mounted() {
            this.populateQuery()
            this.enumConnections()
            this.getReceipts()
        },

        methods: {
            populateQuery() {
                sessionStorage['fiscal-registrar.page'] && (this.page = +sessionStorage['fiscal-registrar.page'])
            },

            async getReceipts(page = null) {
                page && (this.page = page)
                const receipt = new Receipt
                Object.entries(this.query.filter)
                    .filter(([key, val]) => !!val)
                    .forEach(([key, val]) => receipt.where(key, val))
                let column = this.aliases[this.query.sort.by] ?? this.query.sort.by
                this.query.sort.desc && (column = '-' + column)
                receipt.orderBy(column)
                this.page && receipt.page(this.page)
                this.receipts = await receipt.get()
            },
        },

        computed: {
            selectConnections() {
                return [
                    { value: null, text: 'любое' },
                    ...this.buildOptions(
                        this.connections,
                        ([name, data]) => ({ value: name, text: data.display_name })
                    )]
            },

            selectOperations() {
                return [{ value: null, text: 'все' }, ...this.buildOptions(this.dictionary.operations)]
            },

            selectStates() {
                return [{ value: null, text: 'любой' }, ...this.buildOptions(this.dictionary.states)]
            },
        },

        watch: {
            query: {
                handler: function (query) {
                    this.getReceipts(1)
                },
                deep: true
            },
            page: function (page) {
                sessionStorage['fiscal-registrar.page'] = page
            },
        },
    }
</script>

<template>
    <div>
        <h2 class="text-center">Кассовые чеки</h2>

        <b-form-group v-if="query.showFilter">
            <b-container fluid>
                <b-form-row class="my-1">
                    <b-col align-self="end" lg="2" md="3" sm="4">
                        <b-form-group label="Порядковый номер" label-for="receiptId">
                            <b-form-input id="receiptId" type="number" size="sm" v-model.number="query.filter.id" debounce="500"></b-form-input>
                        </b-form-group>
                    </b-col>
                    <b-col align-self="end" lg="2" md="3" sm="4">
                        <b-form-group label="Уникальный идентификатор" label-for="receiptExternalId">
                            <b-form-input id="receiptExternalId" type="text" size="sm" v-model="query.filter.external_id" debounce="500"></b-form-input>
                        </b-form-group>
                    </b-col>
                    <b-col align-self="end" lg="2" md="3" sm="4">
                        <b-form-group label="Идентификатор поставщика" label-for="receiptInternalId">
                            <b-form-input id="receiptInternalId" type="text" size="sm" v-model="query.filter.internal_id" debounce="500"></b-form-input>
                        </b-form-group>
                    </b-col>
                    <b-col align-self="end" lg="2" md="3" sm="4">
                        <b-form-group label="Время создания от" label-for="receiptCreatedFrom">
                            <b-form-input id="receiptCreatedFrom" type="datetime-local" size="sm" v-model="query.filter.created_from"></b-form-input>
                        </b-form-group>
                    </b-col>
                    <b-col align-self="end" lg="2" md="3" sm="4">
                        <b-form-group label="Время создания до" label-for="receiptCreatedTo">
                            <b-form-input id="receiptCreatedTo" type="datetime-local" size="sm" v-model="query.filter.created_to"></b-form-input>
                        </b-form-group>
                    </b-col>
                    <b-col align-self="end" lg="2" md="3" sm="4">
                        <b-form-group label="Соединение" label-for="receiptConnection">
                            <b-form-select id="receiptConnection" size="sm" v-model="query.filter.connection" :options="selectConnections"></b-form-select>
                        </b-form-group>
                    </b-col>
                    <b-col align-self="end" lg="2" md="3" sm="4">
                        <b-form-group label="Операция" label-for="receiptOperation">
                            <b-form-select id="receiptOperation" size="sm" v-model="query.filter.operation" :options="selectOperations"></b-form-select>
                        </b-form-group>
                    </b-col>
                    <b-col align-self="end" lg="2" md="3" sm="4">
                        <b-form-group label="Сумма от" label-for="receiptMinTotal">
                            <b-form-input id="receiptMinTotal" type="number" min="0" :max="query.filter.max_total || 42949672.95" step=".01" size="sm" v-model="query.filter.min_total" debounce="500"></b-form-input>
                        </b-form-group>
                    </b-col>
                    <b-col align-self="end" lg="2" md="3" sm="4">
                        <b-form-group label="Сумма до" label-for="receiptMaxTotal">
                            <b-form-input id="receiptMaxTotal" type="number" :min="query.filter.min_total || 0" max="42949672.95" step=".01" size="sm" v-model="query.filter.max_total" debounce="500"></b-form-input>
                        </b-form-group>
                    </b-col>
                    <b-col align-self="end" lg="2" md="3" sm="4">
                        <b-form-group label="Статус" label-for="receiptState">
                            <b-form-select id="receiptState" size="sm" v-model="query.filter.state" :options="selectStates"></b-form-select>
                        </b-form-group>
                    </b-col>
                    <b-col align-self="end" lg="2" md="3" sm="4">
                        <b-form-group label="E-mail клиента" label-for="receiptClientEmail">
                            <b-form-input id="receiptClientEmail" type="email" size="sm" placeholder="user@domain.com" v-model="query.filter.email" debounce="500"></b-form-input>
                        </b-form-group>
                    </b-col>
                    <b-col align-self="end" lg="2" md="3" sm="4">
                        <b-form-group label="Телефон клиента" label-for="receiptClientPhone">
                            <b-form-input id="receiptClientPhone" type="tel" size="sm" placeholder="+79001234567" v-model="query.filter.phone" debounce="500"></b-form-input>
                        </b-form-group>
                    </b-col>
                    <b-col align-self="end" lg="2" md="3" sm="4">
                        <b-form-group label="ФН" label-for="receiptFnNumber">
                            <b-form-input id="receiptFnNumber" type="number" size="sm" v-model="query.filter.fn" debounce="500"></b-form-input>
                        </b-form-group>
                    </b-col>
                    <b-col align-self="end" lg="2" md="3" sm="4">
                        <b-form-group label="ФД №" label-for="receiptFiscalDocumentNumber">
                            <b-form-input id="receiptFiscalDocumentNumber" type="number" size="sm" v-model="query.filter.i" debounce="500"></b-form-input>
                        </b-form-group>
                    </b-col>
                    <b-col align-self="end" lg="2" md="3" sm="4">
                        <b-form-group label="ФПД" label-for="receiptFiscalDocumentAttribute">
                            <b-form-input id="receiptFiscalDocumentAttribute" type="number" size="sm" v-model="query.filter.fd" debounce="500"></b-form-input>
                        </b-form-group>
                    </b-col>
                    <b-col align-self="end" lg="2" md="3" sm="4">
                        <b-form-group class="text-center">
                            <b-button variant="danger" size="sm" @click="resetFilter">Очистить фильтр</b-button>
                        </b-form-group>
                    </b-col>
                </b-form-row>
            </b-container>
        </b-form-group>

        <b-table small striped hover :items="receipts.data" :fields="fields" no-local-sorting :sort-by.sync="query.sort.by" :sort-desc.sync="query.sort.desc">
            <template #cell(id)="data">
                <b-link :to="{ name: 'receipt', params: { id: data.value } }">{{ data.value }}</b-link>
            </template>
            <template #cell(created_at)="data">
                {{ formatTime(data.value) }}
            </template>
            <template #cell(connection)="data">
                {{ data.value ? connections[data.value].display_name : '-' }}
            </template>
            <template #cell(operation)="data">
                {{ data.value ? dictionary.operations[data.value] : '-' }}
            </template>
            <template #cell(payload.total)="data">
                <div class="text-right">{{ data.value }}</div>
            </template>
            <template #cell(state)="data">
                {{ dictionary.states[data.value] }}
            </template>
        </b-table>

        <pagination :data="receipts" :limit="2" :show-disabled="true" align="center" @pagination-change-page="getReceipts"></pagination>

        <b-button v-if="query.showCreateButton" :to="{ name: 'new-receipt' }" variant="primary" size="sm">Создать</b-button>
    </div>
</template>
