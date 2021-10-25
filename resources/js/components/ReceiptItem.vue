<script type="text/ecmascript-6">
    export default {
        props: ['item'],

        data() {
            return {
                paymentMethods: [
                    { value: 'full_prepayment', text: 'предоплата 100%' },
                    { value: 'prepayment', text: 'предоплата' },
                    { value: 'advance', text: 'аванс' },
                    { value: 'full_payment', text: 'полный расчет' },
                    { value: 'partial_payment', text: 'частичный расчет и кредит' },
                    { value: 'credit', text: 'передача в кредит' },
                    { value: 'credit_payment', text: 'оплата кредита' }
                ],
                paymentObjects: [
                    { value: 'commodity', text: 'товар' },
                    { value: 'excise', text: 'подакцизный товар' },
                    { value: 'job', text: 'работа' },
                    { value: 'service', text: 'услуга' },
                    { value: 'gambling_bet', text: 'ставка азартной игры' },
                    { value: 'gambling_prize', text: 'выигрыш азартной игры' },
                    { value: 'lottery', text: 'лотерейный билет' },
                    { value: 'lottery_prize', text: 'выигрыш лотереи' },
                    { value: 'intellectual_activity', text: 'предоставление результатов интеллектуальной деятельности' },
                    { value: 'payment', text: 'платеж' },
                    { value: 'agent_commission', text: 'агентское вознаграждение' },
                    { value: 'award', text: 'взнос/штраф/вознаграждение/бонус' },
                    { value: 'composite', text: 'составной предмет расчета' },
                    { value: 'another', text: 'иной предмет расчета' },
                    { value: 'property_right', text: 'имущественное право' },
                    { value: 'non-operating_gain', text: 'внереализационный доход' },
                    { value: 'insurance_premium', text: 'страховые взносы' },
                    { value: 'sales_tax', text: 'торговый сбор' },
                    { value: 'resort_fee', text: 'курортный сбор' },
                    { value: 'deposit', text: 'залог' },
                    { value: 'expense', text: 'расход' },
                    { value: 'pension_insurance_ip', text: 'взносы на ОПС ИП' },
                    { value: 'pension_insurance', text: 'взносы на ОПС' },
                    { value: 'medical_insurance_ip', text: 'взносы на ОМС ИП' },
                    { value: 'medical_insurance', text: 'взносы на ОМС' },
                    { value: 'social_insurance', text: 'взносы на ОСС' },
                    { value: 'casino_payment', text: 'платеж казино' }
                ],
                vatTypes: [
                    { value: 'none', text: 'нет' },
                    { value: 'vat0', text: '0%' },
                    { value: 'vat10', text: '10%' },
                    { value: 'vat18', text: '18%' },
                    { value: 'vat20', text: '20%' },
                    { value: 'vat110', text: '10/110' },
                    { value: 'vat118', text: '18/118' },
                    { value: 'vat120', text: '20/120' }
                ],
            }
        },

        methods: {
            updateSum() {
                this.item.sum = this.item.price * this.item.quantity;
            }
        },

        computed: {
            id() {
                return this.$vnode.key + 1;
            }
        }
    }
</script>

<template>
    <b-form-row class="my-1">
        <b-col align-self="end" lg="3" md="2">
            <b-form-group label="Наименование" :label-for="'item' + id + 'Name'" class="required">
                <b-form-input :id="'item' + id + 'Name'" type="text" size="sm" required v-model="item.name"></b-form-input>
            </b-form-group>
        </b-col>
        <b-col align-self="end" lg="1" md="2">
            <b-form-group label="Цена" :label-for="'item' + id + 'Price'" class="required">
                <b-form-input :id="'item' + id + 'Price'" type="number" min="0" max="42949672.95" step=".01" size="sm" placeholder="0" required v-model="item.price" @input="updateSum"></b-form-input>
            </b-form-group>
        </b-col>
        <b-col align-self="end" lg="1" md="2">
            <b-form-group label="Кол-во" :label-for="'item' + id + 'Quantity'" class="required">
                <b-form-input :id="'item' + id + 'Quantity'" type="number" min=".001" max="99999.999" step="any" size="sm" placeholder="1" required v-model="item.quantity" @input="updateSum"></b-form-input>
            </b-form-group>
        </b-col>
        <b-col align-self="end" lg="1" md="2">
            <b-form-group label="Сумма" :label-for="'item' + id + 'Sum'" class="required">
                <b-form-input :id="'item' + id + 'Sum'" type="number" min="0" max="42949672.95" step=".01" size="sm" placeholder="0" required v-model="item.sum"></b-form-input>
            </b-form-group>
        </b-col>
        <b-col align-self="end" lg="1" md="2">
            <b-form-group label="Ед. изм." :label-for="'item' + id + 'MeasurementUnit'">
                <b-form-input :id="'item' + id + 'MeasurementUnit'" type="text" size="sm" placeholder="шт." maxlength="16" v-model="item.measurement_unit"></b-form-input>
            </b-form-group>
        </b-col>
        <b-col align-self="end" lg="2" md="2">
            <b-form-group label="Способ расчета" :label-for="'item' + id + 'PaymentMethod'" class="required">
                <b-form-select :id="'item' + id + 'PaymentMethod'" size="sm" v-model="item.payment_method" :options="paymentMethods"></b-form-select>
            </b-form-group>
        </b-col>
        <b-col align-self="end" lg="2" md="2">
            <b-form-group label="Предмет расчета" :label-for="'item' + id + 'PaymentObject'" class="required">
                <b-form-select :id="'item' + id + 'PaymentObject'" size="sm" v-model="item.payment_object" :options="paymentObjects"></b-form-select>
            </b-form-group>
        </b-col>
        <b-col align-self="end" lg="1" md="2">
            <b-form-group label="НДС" :label-for="'item' + id + 'VatType'" class="required">
                <b-form-select :id="'item' + id + 'VatType'" size="sm" v-model="item.vat.type" :options="vatTypes"></b-form-select>
            </b-form-group>
        </b-col>
        <b-col align-self="end" lg="1" md="2">
            <b-form-group label="Сумма НДС" :label-for="'item' + id + 'VatSum'">
                <b-form-input :id="'item' + id + 'VatSum'" type="number" min="0" max="42949672.95" step=".01" size="sm" placeholder="0" v-model="item.vat.sum"></b-form-input>
            </b-form-group>
        </b-col>
    </b-form-row>
</template>
