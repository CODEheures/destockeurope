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
            {{ invoice.transaction_id }}
        </td>
        <td>
            <i v-if="invoice.captured" class="circular check icon"></i>
        </td>
        <td>
            <i v-if="invoice.voided" class="circular check icon"></i>
        </td>
        <td>
            <i v-if="invoice.refunded" class="circular check icon"></i>
        </td>
        <td>
            <template v-if="invoice.captured">
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
  import moment from 'moment'
  export default {
    props: {
      invoice: Object
    },
    computed: {
      strings () {
        return this.$store.state.strings['invoice-by-list-item']
      },
      properties () {
        return this.$store.state.properties['global']
      }
    },
    methods: {
      getMoment (dateTime) {
        moment.locale(this.properties.actualLocale)
        return moment(dateTime).format('L')
      },
      refundMe () {
        this.$emit('refund', {'refundUrl': this.invoice.refundUrl, 'amount': this.invoice.costWithDecimalAndCurrency})
      }
    }
  }
</script>
