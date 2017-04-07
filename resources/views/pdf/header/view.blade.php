<div class="pdfHeader">
    <div class="logo">
        <a href="{{ route('home') }}" class="navbar-logo ">
            <img src="{{ asset('/images/logopdf.svg') }}"/>
        </a>
    </div>

    @if(isset($invoice))
        <p class="navbar-menu">
            <a href="{{ route('home') }}">{{ trans('strings.pdf_invoice_number') . $invoice->invoice_number }}<br/>
                <span class="created_at">
                    {{ trans('strings.pdf_invoice_emission'). ': ' . \App\Common\LocaleUtils::getTransDate($invoice->created_at)}}
                </span>
            </a>
        </p>
    @endif

</div>