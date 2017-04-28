@extends('layouts.pdf')

@section('pdf')
    <div class="seller">
        <div class="from-seller">{{ trans('strings.pdf_invoice_seller_designation') }}</div>
        <div class="infos">
            {{ env('LEGAL_COMPAGNY_NAME') }}<br />
            {{ env('LEGAL_COMPAGNY_ADDRESS') }}<br />
            SIRET: {{ env('LEGAL_COMPAGNY_SIRET') }}<br/>
            {{ trans('strings.pdf_invoice_vat_number') . $invoice->tva_requester }}
        </div>
    </div>

    <div class="customer">
        <div class="to-customer">{{ trans('strings.pdf_invoice_customer_designation') }}</div>
        <div class="infos">
            {{ $invoice->user->compagnyName }}<br />
            {{ $address }}
            @if($invoice->tva_requester && $invoice->vatIdentifier)
                <br />{{  trans('strings.pdf_invoice_vat_number') . $invoice->tva_customer}}
                @if($invoice->vatIdentifier && strtoupper(substr($invoice->tva_customer,0,2))!='FR')
                     {{ trans('strings.pdf_table_header_autoliquidation') }}
                @endif
                <br />{{ '(' . trans('strings.view_user_account_compagny_number_identifier') . $invoice->vatIdentifier .')' }}
            @else
                <br />{{ trans('strings.pdf_invoice_vat_number') . '-/-' }}
            @endif
        </div>
    </div>

    @include('pdf.invoice.view')
@endsection