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
            <template v-if="invoice.captureId">
                <a class="ui blue labeled icon button" :href="invoice.url"><i class="download icon"></i>{{ seeInvoiceLabel }}</a>
            </template>
            <template v-else>
                <a class="ui labeled icon disabled button" href=""><i class="download icon"></i>{{ seeInvoiceLabel }}</a>
            </template>
        </td>
    </tr>
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
            }
        }
    }
</script>
