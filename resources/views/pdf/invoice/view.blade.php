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
        {{ trans('strings.pdf_invoice_payed').' '
            . trans('strings.'. strtolower(\Carbon\Carbon::parse($invoice->created_at)->formatLocalized('%A'))) . ' '
            . \Carbon\Carbon::parse($invoice->created_at)->formatLocalized('%e') . ' '
            . trans('strings.'. strtolower(\Carbon\Carbon::parse($invoice->created_at)->formatLocalized('%B'))) . ' '
            . \Carbon\Carbon::parse($invoice->created_at)->formatLocalized('%Y') }}
    </h2>
</div>