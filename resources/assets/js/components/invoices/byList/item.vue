<template>
    <tr>
        <td>
            # {{ invoice.invoice_number }}
        </td>
        <td>
            {{ getMoment(invoice.created_at) }}
        </td>
        <td>
            {{ invoice.usermail }}
        </td>
        <td>
            {{ invoice.captureId }}
        </td>
        <td>
            {{ invoice.voidId }}
        </td>
        <td>
            {{ invoice.refundId }}
        </td>
        <td>
            <template v-if="invoice.captureId">
                <div class="ui buttons">
                    <a class="ui blue icon button" :href="invoice.url">{{ seeInvoiceLabel }}</a>
                    <div class="or" :data-text="orLabel"></div>
                    <template v-if="invoice.refundUrl">
                        <button class="ui red icon button" v-on:click="refundMe()">{{ refundInvoiceLabel }}</button>
                    </template>
                    <template v-else>
                        <button class="ui disabled icon button">{{ refundInvoiceLabel }}</button>
                    </template>
                </div>
            </template>
            <template v-else>
                <div class="ui buttons">
                    <a class="ui disabled icon button" href="">{{ seeInvoiceLabel }}</a>
                    <div class="or" :data-text="orLabel"></div>
                    <button class="ui disabled icon button">{{ refundInvoiceLabel }}</button>
                </div>
            </template>
        </td>
    </tr>
</template>

<script>
    export default {
        props: {
            invoice: Object,
            seeInvoiceLabel: String,
            refundInvoiceLabel: String,
            actualLocale: String,
            orLabel: String
        },
        data: () => {
            return {

            };
        },
        mounted () {

        },
        methods: {
            getMoment: function (dateTime) {
                moment.locale(this.actualLocale);
                return moment(dateTime).format('L');
            },
            refundMe() {
                this.$parent.$emit('refund', {'refundUrl': this.invoice.refundUrl, 'amount':this.invoice.costWithDecimalAndCurrency});
            }
        }
    }
</script>
