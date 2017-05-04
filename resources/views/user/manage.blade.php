@extends('layouts.app')

@section('content')

    <manage-users
            clear-storage="{{ session()->has('clear') ? true : false }}"

            content-header="{{ trans('strings.view_manage_users_header') }}"
            load-error-message="{{ trans('strings.view_all_error_load_message') }}"

            filter-min-length-search="{{ config('runtime.minLengthSearch') }}"
            filter-ribbon-open="{{ trans('strings.view_filter_ribbon_open') }}"
            filter-ribbon-close="{{ trans('strings.view_filter_ribbon_close') }}"
            filter-search-place-holder="{{ trans('strings.form_input_search_label4') }}"

            route-get-users-list="{{ route('admin.user.list') }}"
            no-result-found-header="{{ trans('strings.view_advert_list_no_result_header') }}"
            no-result-found-message="{{ trans('strings.view_advert_list_no_result_message') }}"
            actual-locale="{{ \Illuminate\Support\Facades\App::getLocale() }}"
            role-user-label="{{ trans('strings.view_manage_invoices_list_role_button_label') }}"
            list-header-usermail="{{ trans('strings.view_manage_users_list_header_mail') }}"
            list-header-name="{{ trans('strings.view_manage_users_list_header_name') }}"
            list-header-compagny="{{ trans('strings.view_manage_users_list_header_compagny') }}"
            list-header-register-date="{{ trans('strings.view_manage_users_list_header_register_date') }}"
            list-header-vat-number="{{ trans('strings.view_manage_users_list_header_vat_number') }}"
            list-vat-verification-number-label="{{ trans('strings.view_manage_users_list_vat_verification_number_label') }}"

            page-label="{{ trans('strings.view_pagination_page_label') }}"
            page-previous-label="{{ trans('strings.view_pagination_prev_label') }}"
            page-next-label="{{ trans('strings.view_pagination_next_label') }}"
    ></manage-users>

@endsection