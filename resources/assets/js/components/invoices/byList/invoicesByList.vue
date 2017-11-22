<template>
    <div class="ui segment">
        <div class="ui active inverted dimmer" v-if="!isLoaded">
            <div class="ui large text loader">Loading</div>
        </div>
        <template v-if="invoicesList.length==0">
            <div class="item ads">
                <div class="ui info message">
                    <div class="header">{{ strings.noResultFoundHeader }}</div>
                    <p>{{ strings.noResultFoundMessage }}</p>
                </div>
            </div>
        </template>
        <template v-if="invoicesList.length>0">
            <table class="ui celled table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ strings.listHeaderDate }}</th>
                        <th>{{ strings.listHeaderUsermail }}</th>
                        <th>{{ strings.listHeaderTransactionId }}</th>
                        <th>{{ strings.listHeaderCaptured }}</th>
                        <th>{{ strings.listHeaderVoided }}</th>
                        <th>{{ strings.listHeaderRefunded }}</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <template v-for="(invoice, index) in invoicesList">
                        <invoices-by-list-item
                                :invoice="invoice"
                                @refund="$emit('refund', $event)"
                        ></invoices-by-list-item>
                    </template>
                </tbody>
            </table>
        </template>
    </div>
</template>

<script>
  /**
   * Props
   *  - routeGetInvoicesList: String. The route to get the invoices list
   *  - flagForceReload: Boolean. The flag to force reloading list
   *
   * Events:
   *  @refund: emit refund event object: {'refundUrl': this.invoice.refundUrl, 'amount': this.invoice.costWithDecimalAndCurrency}
   *  @paginate: emit the pagination object
   *
   */
  import Axios from 'axios'
  export default {
    props: {
      routeGetInvoicesList: String,
      flagForceReload: {
        type: Boolean,
        default: false,
        required: false
      }
    },
    computed: {
      strings () {
        return this.$store.state.strings['invoice-by-list']
      }
    },
    watch: {
      routeGetInvoicesList () {
        this.getInvoicesList()
      },
      flagForceReload () {
        this.getInvoicesList()
      }
    },
    data () {
      return {
        invoicesList: [],
        isLoaded: false
      }
    },
    methods: {
      getInvoicesList () {
        this.isLoaded = false
        let that = this
        this.invoicesList = []
        Axios.get(this.routeGetInvoicesList)
          .then(function (response) {
            that.invoicesList = (response.data).invoices.data
            that.isLoaded = true
            let paginate = response.data.invoices
            delete paginate.data
            that.$emit('paginate', paginate)
          })
          .catch(function () {
            that.$alertV({'message': that.strings.loadErrorMessage, 'type': 'error'})
          })
      }
    }
  }
</script>
