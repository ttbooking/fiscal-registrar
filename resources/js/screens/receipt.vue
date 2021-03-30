<script type="text/ecmascript-6">
    export default {
        data() {
            return {
                ready: false,
                receipt: {}
            }
        },

        mounted() {
            this.loadReceipt('atol', this.$route.params.id);

            document.title = "Fiscal Registrar - Receipt";
        },

        methods: {
            loadReceipt(connection, id) {
                this.ready = false;

                this.$http.get(FiscalRegistrar.basePath + '/api/connection/' + connection + '/report/' + id)
                    .then(response => {
                        this.receipt = response.data;

                        this.ready = true;
                    });
            }
        }
    }
</script>

<template>
    <div>
        <h4>{{ this.$root.message }}</h4>
        <p>{{ $t('message.hello') }}</p>
        <span v-if="!ready">Loading...</span>
        <span v-if="ready">{{ this.receipt.internalId }}</span>
    </div>
</template>
