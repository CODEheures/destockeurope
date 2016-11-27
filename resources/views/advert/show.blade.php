@extends('layouts.app')

@section('content')

    <show-advert1
            load-error-message="{{ trans('strings.view_all_error_load_message') }}"

            route-get-advert="{{ route('advert.show', ['id' => $advert->id]) }}"
            route-home="{{ route('home') }}"
            actual-locale="{{ \Illuminate\Support\Facades\App::getLocale() }}"
            image-ratio="{{ env('IMAGE_RATIO') }}"
            total-quantity-label="{{ trans('strings.form_quantity_label') }}"
            lot-mini-quantity-label="{{ trans('strings.form_lot_mini_label') }}"
            urgent-label="{{ trans('strings.view_all_urgent') }}"
            price-info-label="{{ trans('strings.view_advert_list_price_info') }}"
            price-label="{{ trans('strings.view_advert_form_price_label') }}">
    </show-advert1>

@endsection