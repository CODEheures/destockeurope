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
            }
        },
        data: () => {
            return {
                strings: {},
                invoicesList: [],
                isLoaded: false,
            };
        },
        mounted () {
            this.strings = this.$store.state.strings['invoice-by-list'];
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
