<script type="text/ecmascript-6">
    export default {
        data() {
            return {
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
        <table class="table table-sm table-striped table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Время</th>
                    <th>Соединение</th>
                    <th>Тип</th>
                    <th>Сумма</th>
                    <th>Статус</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="receipt in receipts.data" :key="receipt.id">
                    <td><a :href="'receipts/' + receipt.id">{{ receipt.id }}</a></td>
                    <td>{{ receipt.created_at }}</td>
                    <td>{{ receipt.connection }}</td>
                    <td>{{ receipt.operation }}</td>
                    <td>{{ receipt.data.total }}</td>
                    <td>{{ receipt.state }}</td>
                </tr>
            </tbody>
        </table>

        <pagination :data="receipts" :limit="2" :show-disabled="true" align="center" @pagination-change-page="getReceipts"></pagination>
    </div>
</template>
