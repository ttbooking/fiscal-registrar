<script>
    import Receipt from '../models/Receipt'

    export default {
        data() {
            return {
                fields: [
                    { key: 'id', label: '#' },
                    { key: 'created_at', label: 'Время' },
                    { key: 'connection', label: 'Соединение' },
                    { key: 'operation', label: 'Тип' },
                    { key: 'payload.total', label: 'Сумма' },
                    { key: 'state', label: 'Статус' },
                ],
                receipts: {},
                query: {
                    filter: {
                        connection: null,
                        operation: null,
                        min_total: null,
                        max_total: null,
                        state: null,
                        email: null,
                        phone: null,
                    },
                },
                page: 1,
            }
        },

        mounted() {
            this.enumConnections()
            this.populateQuery()
            this.getReceipts()
        },

        methods: {
            populateQuery() {
                localStorage['fiscal-registrar.query'] && (this.query = JSON.parse(localStorage['fiscal-registrar.query']))
                localStorage['fiscal-registrar.page'] && (this.page = +localStorage['fiscal-registrar.page'])
            },

            async getReceipts(page = null) {
                page && (this.page = page)
                const receipt = new Receipt
                Object.entries(this.query.filter)
                    .filter(([key, val]) => val !== null)
                    .forEach(([key, val]) => receipt.where(key, val))
                this.page && receipt.page(this.page)
                this.receipts = await receipt.get()
            },
        },

        watch: {
            query: {
                handler: function (query) {
                    localStorage['fiscal-registrar.query'] = JSON.stringify(query)
                    this.getReceipts(1)
                },
                deep: true
            },
            page: function (page) {
                localStorage['fiscal-registrar.page'] = page
            },
        },
    }
</script>

<template>
    <div>
        <h2 class="text-center">Кассовые чеки</h2>

        <b-form-group>
            <b-container fluid>
                <b-form-row class="my-1">
                    <b-col align-self="end" lg="2" md="3" sm="4">
                        <b-form-group label="Соединение" label-for="receiptConnection">
                            <b-form-select id="receiptConnection" size="sm" v-model="query.filter.connection" :options="selectConnections"></b-form-select>
                        </b-form-group>
                    </b-col>
                    <b-col align-self="end" lg="2" md="3" sm="4">
                        <b-form-group label="Операция" label-for="receiptOperation">
                            <b-form-select id="receiptOperation" size="sm" v-model="query.filter.operation" :options="buildOptions(dictionary.operations)"></b-form-select>
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
                            <b-form-select id="receiptState" size="sm" v-model="query.filter.state" :options="buildOptions(dictionary.states)"></b-form-select>
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
                </b-form-row>
            </b-container>
        </b-form-group>

        <b-table small striped hover :items="receipts.data" :fields="fields">
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

        <b-button :to="{ name: 'new-receipt' }" variant="primary" size="sm">Создать</b-button>
    </div>
</template>
