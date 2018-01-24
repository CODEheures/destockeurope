<div class="invoice-title">
    <h2>{{ trans('strings.pdf_invoice_number') . $invoice->invoice_number }}:</h2>
    <div class="price">
        <div class="total-price">
            @if($invoice->tvaSubject)
                {{ trans('strings.pdf_invoice_total_cost_incl_vat', ['cost' => \App\Common\MoneyUtils::getPriceWithDecimal($invoice->cost+$tva, env('DEFAULT_CURRENCY'))])  }}
            @else
                {{ trans('strings.pdf_invoice_total_cost_excl_vat', ['cost' => App\Common\MoneyUtils::getPriceWithDecimal($invoice->cost, env('DEFAULT_CURRENCY'))])  }}
            @endif
        </div>
    </div>
</div>
<div class="invoice">
    @include('pdf.invoice.table.view')
</div>

<div class="invoice-title">
    <h2>
        {{ trans('strings.pdf_invoice_payed').' '. \App\Common\LocaleUtils::getTransDate($invoice->created_at) }}
        <span class="detail">{{ trans('strings.pdf_invoice_transactionId', ['transactionid' => (!is_null($invoice->transaction_id) ? $invoice->transaction_id : $invoice->captureId)]) }}</span>
    </h2>
</div>
@if($invoice->refunded)
<div class="invoice-title">
    <h2>
        {{ trans('strings.pdf_invoice_refund').' '. \App\Common\LocaleUtils::getTransDate($invoice->updated_at) }}
    </h2>
</div>
@endif
<!-- FOR OLD VERSIONS -->
@if(!is_null($invoice->refundId))
    <div class="invoice-title">
        <h2>
            {{ trans('strings.pdf_invoice_refund').' '. \App\Common\LocaleUtils::getTransDate($invoice->updated_at) }}
            <span class="detail">{{ trans('strings.pdf_invoice_transactionId', ['transactionid' => $invoice->refundId]) }}</span>
        </h2>
    </div>
@endif