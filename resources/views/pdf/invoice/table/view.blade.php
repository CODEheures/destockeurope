<table>
    <thead>
    <tr>
        <th>{{ trans('strings.pdf_table_header_product') }}</th>
        <th>{{ trans('strings.pdf_table_header_cost_ht') }}</th>
        <th>{{ trans('strings.pdf_table_header_quantity') }}</th>
        <th>{{ trans('strings.pdf_table_header_vat') }}</th>
        <th>{{ trans('strings.pdf_table_header_cost_ttc') }}</th>
    </tr>
    </thead>
    <tbody>
    @foreach($invoice->options as $item)
        <tr>
            <td>{{ $item['name'] }}</td>
            <td>{{ \App\Common\MoneyUtils::getPriceWithDecimal($item['cost'], env('DEFAULT_CURRENCY')) }}</td>
            <td>{{ $item['quantity'] }}</td>
            <td>@if($invoice->tvaSubject){{ $item['tva']  }}%@else 0 @endif</td>
            <td>@if($invoice->tvaSubject){{ \App\Common\MoneyUtils::getPriceWithDecimal($item['cost']+$item['tvaVal'], env('DEFAULT_CURRENCY'))  }}@else {{ \App\Common\MoneyUtils::getPriceWithDecimal($item['cost'], env('DEFAULT_CURRENCY'))  }} @endif</td>
        </tr>
    @endforeach
    </tbody>
</table>
<p class="line-info">{{ trans_choice('strings.pdf_invoice_number_lines',count($invoice->options), ['num' => count($invoice->options)]) }}</p>
<div class="invoice-total">
    <div class="invoice-total-title">
        <p class="ht">{{ trans('strings.pdf_table_header_total_ht') }}</p>
        <p class="tva">{{ trans('strings.pdf_table_header_total_vat') }}</p>
        <p class="ttc">{{ trans('strings.pdf_table_header_total_ttc') }}</p>
    </div>
    <div class="invoice-total-value">
        <p class="ht">{{ \App\Common\MoneyUtils::getPriceWithDecimal($invoice->cost, env('DEFAULT_CURRENCY')) }}</p>
        <p class="tva">@if($invoice->tvaSubject){{ \App\Common\MoneyUtils::getPriceWithDecimal($tva, env('DEFAULT_CURRENCY')) }}@else 0 @endif</p>
        <p class="ttc">@if($invoice->tvaSubject){{ \App\Common\MoneyUtils::getPriceWithDecimal($invoice->cost+$tva, env('DEFAULT_CURRENCY')) }}@else {{ \App\Common\MoneyUtils::getPriceWithDecimal($invoice->cost, env('DEFAULT_CURRENCY')) }}@endif</p>
    </div>
</div>