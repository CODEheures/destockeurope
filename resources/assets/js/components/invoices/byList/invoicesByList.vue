<template>
    <div class="ui segment">
        <div class="ui active inverted dimmer" v-if="!isLoaded">
            <div class="ui large text loader">Loading</div>
        </div>
        <template v-if="invoicesList.length==0">
            <div class="item ads">
                <div class="ui info message">
                    <div class="header">{{ noResultFoundHeader }}</div>
                    <p>{{ noResultFoundMessage }}</p>
                </div>
            </div>
        </template>
        <template v-if="invoicesList.length>0">
            <table class="ui celled table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ listHeaderDate }}</th>
                        <th>{{ listHeaderUsermail }}</th>
                        <th>{{ listHeaderPaypalCapture }}</th>
                        <th>{{ listHeaderPaypalVoid }}</th>
                        <th>{{ listHeaderPaypalRefund }}</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <template v-for="(invoice, index) in invoicesList">
                        <invoices-by-list-item
                                :invoice="invoice"
                                :actual-locale="actualLocale"
                                :see-invoice-label="seeInvoiceLabel"
                                :refund-invoice-label="refundInvoiceLabel"
                                :or-label="orLabel"
                        ></invoices-by-list-item>
                    </template>
                </tbody>
            </table>
        </template>
    </div>
</template>

<script>
    export default {
        props: {
            routeGetInvoicesList: String,
            flagForceReload: {
                type: Boolean,
                default: false,
                required: false
            },
            actualLocale: String,
            orLabel: String,
            seeInvoiceLabel: String,
            refundInvoiceLabel: String,
            noResultFoundHeader: String,
            noResultFoundMessage: String,
            listHeaderPaypalCapture: String,
            listHeaderPaypalVoid: String,
            listHeaderPaypalRefund: String,
            listHeaderUsermail: String,
            listHeaderDate: String
        },
        data: () => {
            return {
                invoicesList: [],
                isLoaded: false,
            };
        },
        mounted () {
            let that = this;
            this.$watch('routeGetInvoicesList', function () {
                this.getInvoicesList();
            });
            this.$watch('flagForceReload', function () {
                this.getInvoicesList();
            });
            this.$on('sendToast', function (message) {
                that.$parent.$emit('sendToast', message);
            });
            this.$on('loadError', function () {
                that.$parent.$emit('loadError');
            });
            this.$on('refund', function (refund) {
                this.$parent.$emit('refund', refund);
            })
        },
        methods: {
            getInvoicesList: function (withLoadIndicator) {
                withLoadIndicator == undefined ? withLoadIndicator = true : null;
                withLoadIndicator ? this.isLoaded = false : this.isLoaded = true;
                let that = this;
                this.invoicesList = [];
                axios.get(this.routeGetInvoicesList)
                    .then(function (response) {
                        that.invoicesList = (response.data).invoices.data;
                        that.isLoaded = true;
                        let paginate = response.data.invoices;
                        delete paginate.data;
                        that.$parent.$emit('paginate', paginate);
                    })
                    .catch(function (error) {
                        that.$parent.$emit('loadError')
                    });
            }
        }
    }
</script>
