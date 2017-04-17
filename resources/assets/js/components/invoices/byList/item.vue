<template>
    <div class="item">
        <div class="content">
            <div class="ui stackable grid">
                <div class="one wide column">
                    <p># {{ invoice.invoice_number }}</p>
                </div>
                <div class="three wide column">
                    <p>{{ getMoment(invoice.created_at) }}</p>
                </div>
                <div class="four wide column">
                    <p>{{ invoice.captureId }}</p>
                </div>
                <div class="four wide column">
                    <p>{{ invoice.usermail }}</p>
                </div>
                <div class="four wide column">
                    <p>
                        <template v-if="invoice.captureId">
                            <a class="ui blue labeled icon button" :href="invoice.url"><i class="download icon"></i>{{ seeInvoiceLabel }}</a>
                        </template>
                        <template v-else>
                            <a class="ui labeled icon disabled button" href=""><i class="download icon"></i>{{ seeInvoiceLabel }}</a>
                        </template>
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            invoice: Object,
            seeInvoiceLabel: String,
            actualLocale: String
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
            getInvoice: function () {

            }
        }
    }
</script>
