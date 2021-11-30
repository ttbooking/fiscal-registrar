<script>
    export default {
        data() {
            return {
                fields: [
                    { key: 'id', label: '#' },
                    { key: 'created_at', label: 'Время' },
                    { key: 'connection', label: 'Соединение' },
                    { key: 'operation', label: 'Тип' },
                    { key: 'data.total', label: 'Сумма' },
                    { key: 'state', label: 'Статус' },
                ],
                receipts: {},
                query: {
                    page: 1,
                    filter: {
                        connection: null,
                        operation: null,
                        state: null,
                    },
                },
            }
        },

        mounted() {
            this.enumConnections()
            this.populateQuery()
            this.getReceipts()
        },

        methods: {
            populateQuery() {
                localStorage.query && (this.query = JSON.parse(localStorage.query))
            },

            getReceipts(page = null) {
                page && (this.query.page = page)
                this.$http.get(FiscalRegistrar.basePath + '/api/v1/receipts/?' + qs.stringify(this.query))
                    .then(response => {
                        this.receipts = response.data
                    })
            },
        },

        watch: {
            query: {
                handler: function (query) {
                    localStorage.query = JSON.stringify(query)
                },
                deep: true
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
                            <b-form-select id="receiptConnection" size="sm" v-model="query.filter.connection" :options="selectConnections" @change="getReceipts(1)"></b-form-select>
                        </b-form-group>
                    </b-col>
                    <b-col align-self="end" lg="2" md="3" sm="4">
                        <b-form-group label="Операция" label-for="receiptOperation">
                            <b-form-select id="receiptOperation" size="sm" v-model="query.filter.operation" :options="buildOptions(dictionary.operations)" @change="getReceipts(1)"></b-form-select>
                        </b-form-group>
                    </b-col>
                    <b-col align-self="end" lg="2" md="3" sm="4">
                        <b-form-group label="Статус" label-for="receiptState">
                            <b-form-select id="receiptState" size="sm" v-model="query.filter.state" :options="buildOptions(dictionary.states)" @change="getReceipts(1)"></b-form-select>
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
            <template #cell(data.total)="data">
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
