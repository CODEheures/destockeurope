@extends('layouts.portal')

@section('titlePagePlus')
{{ trans('strings.view_prices_title') }}
@endsection

@section('meta-description')
{{ trans('strings.app_meta_description_prices') }}
@endsection

@section('content')
<!-- main page -->
<div class="ui grid">
    <div class="four wide tablet only four wide computer only column">
        <double-square
                :centered="true"
        ></double-square>
    </div>
    <div class="sixteen wide mobile twelve wide tablet twelve wide computer column">
        <div class="ui segment">
            <div class="ui grid">
                <div class="sixteen wide column">
                    <div class="ui segment">
                        <h1>{{ trans('strings.view_price_header') }}</h1>
                        <p>{!! trans('strings.view_price_cgu', ['link' => route('cgv')]) !!}</p>
                        @if(\Carbon\Carbon::parse(config('runtime.validityCosts'))->isFuture())
                        <p>{!! trans('strings.view_manage_adverts_validity_costs_label') !!}: {{ \App\Common\LocaleUtils::getTransDate(config('runtime.validityCosts')) }}</p>
                        @endif
                        <table class="ui unstackable celled structured fixed table" style="border: none">
                            <thead>
                            <tr>
                                <th colspan="2" style="background: transparent"></th>
                                <th  class="center aligned" style="border-top: 1px solid rgba(34,36,38,.1);">{{ trans('strings.view_price_table_header_in') }}</th>
                                <th  class="center aligned" style="border-top: 1px solid rgba(34,36,38,.1);">{{ trans('strings.view_price_table_header_price') }}</th>
                            </tr>
                            </thead>
                            <tbody style="border: 1px solid rgba(34,36,38,.1);">
                            <tr>
                                <td colspan="2">{{ trans('strings.view_price_table_row_insertion', ['lifetime' => env('ADVERT_LIFE_TIME')]) }}</td>
                                <td class="center aligned">
                                    <i class="large green checkmark icon"></i>
                                </td>
                                <td></td>
                            </tr>
                            <tr>
                                @if(config('runtime.nbFreePictures') < config('runtime.nbMaxPictures'))
                                <td rowspan="2">{{ trans('strings.view_price_table_row_photos') }}</td>
                                @else
                                <td>{{ trans('strings.view_price_table_row_photos') }}</td>
                                @endif
                                <td class="center aligned">
                                    @if(config('runtime.nbFreePictures')==1)
                                        1
                                    @else
                                        {{ trans('strings.view_price_table_row_photos_number', ['min' => 1, 'max' => config('runtime.nbFreePictures')]) }}
                                    @endif
                                </td>
                                <td class="center aligned">
                                    <i class="large green checkmark icon"></i>
                                </td>
                                <td></td>
                            </tr>
                            @if(config('runtime.nbFreePictures') < config('runtime.nbMaxPictures'))
                            <tr>
                                <td class="center aligned">{{ trans('strings.view_price_table_row_photos_number', ['min' => config('runtime.nbFreePictures')+1, 'max' => config('runtime.nbMaxPictures')]) }}</td>
                                <td class="center aligned"></td>
                                <td class="center aligned">{{  \App\Common\MoneyUtils::getPriceWithDecimal(\App\Common\CostUtils::getCostPictures(config('runtime.nbFreePictures')+1),'EUR',true) }}{{ trans('strings.view_price_table_row_photos_unit') }}</td>
                            </tr>
                            @endif
                            <tr>
                                <td colspan="2">{{ trans('strings.view_price_table_row_option_toNegociate') }} <span class="ui tiny blue right floated left pointing label price negociated" style="float: right;">{{ trans('strings.view_all_negociate') }}</span></td>
                                <td class="center aligned">
                                    <i class="large green checkmark icon"></i>
                                </td>
                                <td></td>
                            </tr>
                            <tr>
                                <td colspan="2">{{ trans('strings.view_price_table_row_option_urgent') }} <span class="ui red horizontal label" style="float: right">{{ trans('strings.view_all_urgent') }}</span></td>
                                <td class="center aligned">
                                    @if(\App\Common\MoneyUtils::getPriceWithDecimal(\App\Common\CostUtils::getCostIsUrgent(true),'EUR',false) <= 0)
                                    <i class="large green checkmark icon"></i>
                                    @endif
                                </td>
                                <td class="center aligned">
                                    @if(\App\Common\MoneyUtils::getPriceWithDecimal(\App\Common\CostUtils::getCostIsUrgent(true),'EUR',false) > 0)
                                    {{  \App\Common\MoneyUtils::getPriceWithDecimal(\App\Common\CostUtils::getCostIsUrgent(true),'EUR',true) }}
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">{{ trans('strings.view_price_table_row_option_video') }}</td>
                                <td class="center aligned">
                                    @if(\App\Common\MoneyUtils::getPriceWithDecimal(\App\Common\CostUtils::getCostVideo(true),'EUR',false) <= 0)
                                    <i class="large green checkmark icon"></i>
                                    @endif
                                </td>
                                <td class="center aligned">
                                    @if(\App\Common\MoneyUtils::getPriceWithDecimal(\App\Common\CostUtils::getCostVideo(true),'EUR',false) > 0)
                                    {{  \App\Common\MoneyUtils::getPriceWithDecimal(\App\Common\CostUtils::getCostVideo(true),'EUR',true) }}
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">{{ trans('strings.view_price_table_row_edit') }}</td>
                                <td class="center aligned">
                                    @if(\App\Common\MoneyUtils::getPriceWithDecimal(\App\Common\CostUtils::getCostIsEdit(true),'EUR',false) <= 0)
                                    <i class="large green checkmark icon"></i>
                                    @endif
                                </td>
                                <td class="center aligned">
                                    @if(\App\Common\MoneyUtils::getPriceWithDecimal(\App\Common\CostUtils::getCostIsEdit(true),'EUR',false) > 0)
                                    {{  \App\Common\MoneyUtils::getPriceWithDecimal(\App\Common\CostUtils::getCostIsEdit(true),'EUR',true) }}
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">{{ trans('strings.view_price_table_row_backToTop') }}</td>
                                <td class="center aligned">
                                    @if(\App\Common\MoneyUtils::getPriceWithDecimal(\App\Common\CostUtils::getCostIsBackToTop(true),'EUR',false) <= 0)
                                    <i class="large green checkmark icon"></i>
                                    @endif
                                </td>
                                <td class="center aligned">
                                    @if(\App\Common\MoneyUtils::getPriceWithDecimal(\App\Common\CostUtils::getCostIsBackToTop(true),'EUR',false) > 0)
                                    {{  \App\Common\MoneyUtils::getPriceWithDecimal(\App\Common\CostUtils::getCostIsBackToTop(true),'EUR',true) }}
                                    @endif
                                </td>
                            </tr>
                            @if(\App\Common\MoneyUtils::getPriceWithDecimal(\App\Common\CostUtils::getCostIsHighlight(true, 0),'EUR', false) > 0)
                                <tr>
                                    <td rowspan="2">
                                        {{ trans('strings.view_price_table_row_highlight_1', ['hightlightTime' => env('HIGHLIGHT_HOURS_DURATION')]) }}<br />
                                        {{ trans('strings.view_price_table_row_highlight_2') }} {{ config('runtime.highlightCost') }}{{ trans('strings.view_price_table_row_highlight_3') }}</td>
                                    <td class="center aligned">NbU=0</td>
                                    <td class="center aligned"></td>
                                    <td class="center aligned">{{ \App\Common\MoneyUtils::getPriceWithDecimal(\App\Common\CostUtils::getCostIsHighlight(true, 0),'EUR', true) }}</td>
                                </tr>
                                <?php $cases = [5] ?>
                                @foreach($cases as $case)
                                    <tr>
                                        <td class="center aligned">NbU={{ $case }}</td>
                                        <td class="center aligned"></td>
                                        <td class="center aligned">{{ \App\Common\MoneyUtils::getPriceWithDecimal(\App\Common\CostUtils::getCostIsHighlight(true, $case),'EUR', true) }}</td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="2">{{ trans('strings.view_price_table_row_highlight_1') }}</td>
                                    <td class="center aligned"><i class="large green checkmark icon"></i></td>
                                    <td></td>
                                </tr>
                            @endif
                            <tr>
                                <td colspan="2">{{ trans('strings.view_price_table_row_renew', ['lifetime' => env('ADVERT_LIFE_TIME')]) }}</td>
                                <td class="center aligned">
                                    @if(\App\Common\MoneyUtils::getPriceWithDecimal(\App\Common\CostUtils::getCostIsRenew(true),'EUR',false) <= 0)
                                    <i class="large green checkmark icon"></i>
                                    @endif
                                </td>
                                <td class="center aligned">
                                    @if(\App\Common\MoneyUtils::getPriceWithDecimal(\App\Common\CostUtils::getCostIsRenew(true),'EUR',false) > 0)
                                    {{  \App\Common\MoneyUtils::getPriceWithDecimal(\App\Common\CostUtils::getCostIsRenew(true),'EUR',true) }}
                                    @endif
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection