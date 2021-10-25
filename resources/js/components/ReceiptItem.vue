<script type="text/ecmascript-6">
    export default {
        name: 'ReceiptItem',

        props: ['id', 'item'],

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
            }
        },

        methods: {
            updateSum() {
                this.item.sum = this.item.price * this.item.quantity;
            }
        }
    }
</script>

<template>
    <b-form-row class="my-1">
        <b-col lg="3" md="2">
            <b-form-group label="Наименование" :label-for="'item' + (id+1) + 'Name'" class="required">
                <b-form-input :id="'item' + (id+1) + 'Name'" type="text" size="sm" required v-model="item.name"></b-form-input>
            </b-form-group>
        </b-col>
        <b-col lg="1" md="2">
            <b-form-group label="Цена" :label-for="'item' + (id+1) + 'Price'" class="required">
                <b-form-input :id="'item' + (id+1) + 'Price'" type="number" min="0" max="42949672.95" step=".01" size="sm" placeholder="0" required v-model="item.price" @input="updateSum"></b-form-input>
            </b-form-group>
        </b-col>
        <b-col lg="1" md="2">
            <b-form-group label="Кол-во" :label-for="'item' + (id+1) + 'Quantity'" class="required">
                <b-form-input :id="'item' + (id+1) + 'Quantity'" type="number" min=".001" max="99999.999" step="any" size="sm" placeholder="1" required v-model="item.quantity" @input="updateSum"></b-form-input>
            </b-form-group>
        </b-col>
        <b-col lg="1" md="2">
            <b-form-group label="Сумма" :label-for="'item' + (id+1) + 'Sum'" class="required">
                <b-form-input :id="'item' + (id+1) + 'Sum'" type="number" min="0" max="42949672.95" step=".01" size="sm" placeholder="0" required v-model="item.sum"></b-form-input>
            </b-form-group>
        </b-col>
        <b-col lg="1" md="2">
            <b-form-group label="Ед. изм." :label-for="'item' + (id+1) + 'MeasurementUnit'">
                <b-form-input :id="'item' + (id+1) + 'MeasurementUnit'" type="text" size="sm" placeholder="шт." maxlength="16" v-model="item.measurement_unit"></b-form-input>
            </b-form-group>
        </b-col>
        <b-col lg="2" md="2">
            <b-form-group label="Способ расчета" :label-for="'item' + (id+1) + 'PaymentMethod'" class="required">
                <b-form-select :id="'item' + (id+1) + 'PaymentMethod'" size="sm" v-model="item.payment_method" :options="paymentMethods"></b-form-select>
            </b-form-group>
        </b-col>
        <b-col lg="2" md="2">
            <b-form-group label="Предмет расчета" :label-for="'item' + (id+1) + 'PaymentObject'" class="required">
                <b-form-select :id="'item' + (id+1) + 'PaymentObject'" size="sm" v-model="item.payment_object" :options="paymentObjects"></b-form-select>
            </b-form-group>
        </b-col>
    </b-form-row>
</template>
