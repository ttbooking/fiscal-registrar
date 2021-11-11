<script type="text/ecmascript-6">
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
                receipts: {}
            }
        },

        mounted() {
            this.getReceipts();
        },

        methods: {
            getReceipts(page = 1) {
                this.$http.get(FiscalRegistrar.basePath + '/api/v1/receipts/?page=' + page)
                    .then(response => {
                        this.receipts = response.data;
                    });
            }
        }
    }
</script>

<template>
    <div>
        <b-table small striped hover :items="receipts.data" :fields="fields">
            <template #cell(id)="data">
                <a :href="'receipts/' + data.value">{{ data.value }}</a>
            </template>
            <template #cell(created_at)="data">
                {{ $moment(data.value).format('DD.MM.YYYY HH:mm:ss') }}
            </template>
        </b-table>

        <pagination :data="receipts" :limit="2" :show-disabled="true" align="center" @pagination-change-page="getReceipts"></pagination>
    </div>
</template>
