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
                    <a class="ui blue icon button" :href="invoice.url">{{ strings.seeInvoiceLabel }}</a>
                    <div class="or" :data-text="strings.orLabel"></div>
                    <template v-if="invoice.refundUrl">
                        <button class="ui red icon button" v-on:click="refundMe()">{{ strings.refundInvoiceLabel }}</button>
                    </template>
                    <template v-else>
                        <button class="ui disabled icon button">{{ strings.refundInvoiceLabel }}</button>
                    </template>
                </div>
            </template>
            <template v-else>
                <div class="ui buttons">
                    <a class="ui disabled icon button" href="">{{ strings.seeInvoiceLabel }}</a>
                    <div class="or" :data-text="strings.orLabel"></div>
                    <button class="ui disabled icon button">{{ strings.refundInvoiceLabel }}</button>
                </div>
            </template>
        </td>
    </tr>
</template>

<script>
    export default {
        props: {
            invoice: Object,
        },
        data: () => {
            return {
                strings: {},
                properties: {}
            };
        },
        mounted () {
            this.strings = this.$store.state.strings['invoice-by-list-item'];
            this.properties = this.$store.state.properties['global'];
        },
        methods: {
            getMoment: function (dateTime) {
                moment.locale(this.properties.actualLocale);
                return moment(dateTime).format('L');
            },
            refundMe() {
                this.$parent.$emit('refund', {'refundUrl': this.invoice.refundUrl, 'amount':this.invoice.costWithDecimalAndCurrency});
            }
        }
    }
</script>
