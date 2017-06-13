@extends('layouts.app')

@section('content')

    <review-for-payment
            route-paypal-choice="{{ route('advert.payByPaypal', ['invoiceId' => $invoice->id]) }}"
            route-card-choice="{{ route('advert.payByCard', ['invoiceId' => $invoice->id]) }}"
            route-prices="{{ route('prices') }}"

            invoice="{{ json_encode($invoice) }}"
            cards-types="{{ json_encode($listCardTypes) }}"
            url-img-paypal-disabled="{{ asset('/images/frenchPayButton2_disable.png') }}"
            url-img-paypal-enabled="{{ asset('/images/frenchPayButton2.png') }}"

            content-header="{{ trans('strings.view_reviewForPayment_header') }}"
            load-error-message="{{ trans('strings.view_all_error_load_message') }}"
            table-header-option-name="{{ trans('strings.view_reviewForPayment_table_option_name') }}"
            table-header-option-quantity="{{ trans('strings.view_reviewForPayment_table_option_quantity') }}"
            table-header-option-cost="{{ trans('strings.view_reviewForPayment_table_option_cost') }}"
            table-total-excl-vat="{{ trans('strings.view_reviewForPayment_table_total_excl_vat') }}"
            table-total-vat="{{ trans('strings.view_reviewForPayment_table_vat') }}"
            table-total-incl-vat="{{ trans('strings.view_reviewForPayment_table_total_incl_vat') }}"
            toggle-cgv-label="{{ trans('strings.view_reviewForPayment_cgv', ['link' => route('cgv')]) }}"
            lock-info-header="{{ trans('strings.view_reviewForPayment_lock_info_header') }}"
            lock-info-content="{{ trans('strings.view_reviewForPayment_lock_info_content') }}"
            paypal-btn-title="{{ trans('strings.view_reviewForPayment_paypal_title') }}"
            divider-choice-label="{{ trans('strings.divider_payment') }}"
            payment-card-type-label="{{ trans('strings.payment_card_type_label') }}"
            payment-card-name-label="{{ trans('strings.payment_card_name_label') }}"
            payment-card-name-error="{{ trans('strings.request_input_regex_name', ['name' => trans('strings.payment_card_name_label')]) }}"
            payment-card-number-label="{{ trans('strings.payment_card_number_label') }}"
            payment-card-number-error="{{ trans('strings.payment_card_number_error') }}"
            payment-card-number-placeholder="{{ trans('strings.payment_card_number_placeholder') }}"
            payment-card-cvc-label="{{ trans('strings.payment_card_cvc_label') }}"
            payment-card-cvc-error="{{ trans('strings.request_input_generic_error', ['name' => trans('strings.payment_card_cvc_label')]) }}"
            payment-card-expiration-label="{{ trans('strings.payment_card_expiration_label') }}"
            payment-card-expiration-month-placeholder="{{ trans('strings.payment_card_expiration_month_placeholder') }}"
            payment-card-expiration-year-placeholder="{{ trans('strings.payment_card_expiration_year_placeholder') }}"
            payment-card-year-error="{{ trans('strings.request_input_generic_error', ['name' => trans('strings.payment_card_expiration_year_placeholder')]) }}"
            january="{{ trans('strings.january') }}"
            february="{{ trans('strings.february') }}"
            march="{{ trans('strings.march') }}"
            april="{{ trans('strings.april') }}"
            may="{{ trans('strings.may') }}"
            june="{{ trans('strings.june') }}"
            july="{{ trans('strings.july') }}"
            august="{{ trans('strings.august') }}"
            september="{{ trans('strings.september') }}"
            october="{{ trans('strings.october') }}"
            november="{{ trans('strings.november') }}"
            december="{{ trans('strings.december') }}"
            advert-price="{{ trans('strings.view_price_header') }}"

            step-one-title="{{ isset($title) ?  $title : trans('strings.view_advert_steps_1_title') }}"
            step-two-title="{{ trans('strings.view_advert_steps_2_title') }}"
            step-three-title="{{ trans('strings.view_advert_steps_3_title') }}"
            step-three-title-post="{{ trans('strings.view_advert_steps_3_title_post') }}"
            step-one-description="{{ isset($title) ?  '' : trans('strings.view_advert_steps_1_description') }}"
            step-two-description="{{ trans('strings.view_advert_steps_2_description') }}"
            step-three-description="{{ trans('strings.view_advert_steps_3_description') }}">
    </review-for-payment>

@endsection