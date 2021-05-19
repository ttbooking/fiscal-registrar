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

        <form v-if="ready">
            <div class="mb-3">
                <label for="clientEmail" class="form-label">Email address</label>
                <input type="email" class="form-control" id="clientEmail" placeholder="user@domain.com" v-model="receipt.data.client.email" />
            </div>
            <div class="mb-3">
                <label for="clientPhone" class="form-label">Phone</label>
                <input type="text" class="form-control" id="clientPhone" placeholder="+79001234567" v-model="receipt.data.client.phone" />
            </div>
            <div class="mb-3">
                <label for="clientName" class="form-label">Name</label>
                <input type="text" class="form-control" id="clientName" placeholder="Иван Иванович Иванов" v-model="receipt.data.client.name" />
            </div>
            <div class="mb-3">
                <label for="clientInn" class="form-label">INN</label>
                <input type="text" class="form-control" id="clientInn" placeholder="1234567890" v-model="receipt.data.client.inn" />
            </div>
            <button type="button" class="btn btn-primary" v-on:click="saveReceipt">Save</button>
        </form>

    </div>
</template>
