<template>
    <div class="ui segment">
        <div class="ui relaxed divided list">
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
                <div class="item">
                    <div class="content">
                        <div class="ui grid">
                            <div class="one wide column">
                                <p>#</p>
                            </div>
                            <div class="three wide column">
                                <p>{{ listHeaderDate }}</p>
                            </div>
                            <div class="four wide column">
                                <p>{{ listHeaderPaypal }}</p>
                            </div>
                            <div class="four wide column">
                                <p>{{ listHeaderUsermail }}</p>
                            </div>
                            <div class="four wide column">
                                <p></p>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
            <template v-for="(invoice, index) in invoicesList">
                <invoices-by-list-item
                        :invoice="invoice"
                        :actual-locale="actualLocale"
                        :see-invoice-label="seeInvoiceLabel"
                ></invoices-by-list-item>
            </template>
        </div>
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
            seeInvoiceLabel: String,
            noResultFoundHeader: String,
            noResultFoundMessage: String,
            listHeaderPaypal: String,
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
