@extends('layouts.app')

@section('content')

    <review-for-payment
            route-get-advert="{{ route('advert.show', ['id' => $advert->id]) }}"
            route-paypal-choice="{{ route('advert.payByPaypal', ['id' => $advert->id]) }}"
            route-card-choice="{{ route('advert.payByCard', ['id' => $advert->id]) }}"

            url-img-paypal-disabled="{{ asset('/images/frenchPayButton2_disable.png') }}"
            url-img-paypal-enabled="{{ asset('/images/frenchPayButton2.png') }}"
            url-img-divider-stripe="{{ asset('/images/stripe.png')  }}"

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
            payment-card-name-label="{{ trans('strings.payment_card_name_label') }}"
            payment-card-number-label="{{ trans('strings.payment_card_number_label') }}"
            payment-card-number-placeholder="{{ trans('strings.payment_card_number_placeholder') }}"
            payment-card-cvc-label="{{ trans('strings.payment_card_cvc_label') }}"
            payment-card-expiration-label="{{ trans('strings.payment_card_expiration_label') }}"
            payment-card-expiration-month-placeholder="{{ trans('strings.payment_card_expiration_month_placeholder') }}"
            payment-card-expiration-year-placeholder="{{ trans('strings.payment_card_expiration_year_placeholder') }}"
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

            step-one-title="{{ trans('strings.view_advert_steps_1_title') }}"
            step-two-title="{{ trans('strings.view_advert_steps_2_title') }}"
            step-three-title="{{ trans('strings.view_advert_steps_3_title') }}"
            step-one-description="{{ trans('strings.view_advert_steps_1_description') }}"
            step-two-description="{{ trans('strings.view_advert_steps_2_description') }}"
            step-three-description="{{ trans('strings.view_advert_steps_3_description') }}">
    </review-for-payment>

@endsection