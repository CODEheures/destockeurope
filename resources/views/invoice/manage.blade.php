@extends('layouts.app')

@section('content')

    <manage-invoices
            clear-storage="{{ session()->has('clear') ? true : false }}"

            content-header="{{ trans('strings.view_manage_invoices_label') }}"
            load-error-message="{{ trans('strings.view_all_error_load_message') }}"

            filter-min-length-search="{{ config('runtime.minLengthSearch') }}"
            filter-ribbon-open="{{ trans('strings.view_filter_ribbon_open') }}"
            filter-ribbon-close="{{ trans('strings.view_filter_ribbon_close') }}"
            filter-search-place-holder="{{ trans('strings.form_input_search_label3') }}"

            route-get-invoices-list="{{ route('admin.invoice.list') }}"
            no-result-found-header="{{ trans('strings.view_advert_list_no_result_header') }}"
            no-result-found-message="{{ trans('strings.view_advert_list_no_result_message') }}"
            actual-locale="{{ \Illuminate\Support\Facades\App::getLocale() }}"
            see-invoice-label="{{ trans('strings.view_manage_invoices_list_see_button_label') }}"
            list-header-paypal-capture="{{ trans('strings.view_manage_invoices_list_header_paypal_capture') }}"
            list-header-paypal-void="{{ trans('strings.view_manage_invoices_list_header_paypal_void') }}"
            list-header-usermail="{{ trans('strings.view_manage_invoices_list_header_usermail') }}"
            list-header-date="{{ trans('strings.view_manage_invoices_list_header_date') }}"

            page-label="{{ trans('strings.view_pagination_page_label') }}"
            page-previous-label="{{ trans('strings.view_pagination_prev_label') }}"
            page-next-label="{{ trans('strings.view_pagination_next_label') }}"
    ></manage-invoices>

@endsection