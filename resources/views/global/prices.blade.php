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
                                    <td colspan="2">{{ trans('strings.view_price_table_row_insertion') }}</td>
                                    <td class="center aligned">
                                        <i class="large green checkmark icon"></i>
                                    </td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td rowspan="2">{{ trans('strings.view_price_table_row_photos') }}</td>
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
                                <tr>
                                    <td class="center aligned">{{ trans('strings.view_price_table_row_photos_number', ['min' => config('runtime.nbFreePictures')+1, 'max' => config('runtime.nbMaxPictures')]) }}</td>
                                    <td class="center aligned"></td>
                                    <td class="center aligned">{{  \App\Common\MoneyUtils::getPriceWithDecimal(\App\Common\CostUtils::getCostPictures(config('runtime.nbFreePictures')+1),'EUR',true) }}{{ trans('strings.view_price_table_row_photos_unit') }}</td>
                                </tr>
                                <tr>
                                    <td colspan="2">{{ trans('strings.view_price_table_row_option_toNegociate') }} <span class="ui tiny blue right floated left pointing label price negociated" style="float: right;">{{ trans('strings.view_all_negociate') }}</span></td>
                                    <td class="center aligned">
                                        <i class="large green checkmark icon"></i>
                                    </td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td colspan="2">{{ trans('strings.view_price_table_row_option_urgent') }} <span class="ui red horizontal label" style="float: right">{{ trans('strings.view_all_urgent') }}</span></td>
                                    <td class="center aligned"></td>
                                    <td class="center aligned">{{  \App\Common\MoneyUtils::getPriceWithDecimal(\App\Common\CostUtils::getCostIsUrgent(true),'EUR',true) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="2">{{ trans('strings.view_price_table_row_option_video') }}</td>
                                    <td class="center aligned"></td>
                                    <td class="center aligned">{{  \App\Common\MoneyUtils::getPriceWithDecimal(\App\Common\CostUtils::getCostVideo(true),'EUR',true) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="2">{{ trans('strings.view_price_table_row_edit') }}</td>
                                    <td class="center aligned"></td>
                                    <td class="center aligned">{{  \App\Common\MoneyUtils::getPriceWithDecimal(\App\Common\CostUtils::getCostIsEdit(true),'EUR',true) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="2">{{ trans('strings.view_price_table_row_backToTop') }}</td>
                                    <td class="center aligned"></td>
                                    <td class="center aligned">{{  \App\Common\MoneyUtils::getPriceWithDecimal(\App\Common\CostUtils::getCostIsBackToTop(true),'EUR',true) }}</td>
                                </tr>
                                <tr>
                                    <td rowspan="2">
                                        {{ trans('strings.view_price_table_row_highlight_1') }}<br />
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
                                <tr>
                                    <td colspan="2">{{ trans('strings.view_price_table_row_renew') }}</td>
                                    <td class="center aligned"></td>
                                    <td class="center aligned">{{  \App\Common\MoneyUtils::getPriceWithDecimal(\App\Common\CostUtils::getCostIsRenew(true),'EUR',true) }}</td>
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