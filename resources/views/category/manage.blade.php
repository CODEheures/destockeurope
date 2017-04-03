@extends('layouts.app')

@section('content')

    <manage-categories
            route-category="{{ route('category.index') }}"
            route-shift-up-category="{{ route('category.shiftUp') }}"
            route-shift-down-category="{{ route('category.shiftDown') }}"
            route-append-to-category="{{ route('category.appendTo') }}"

            content-header="{{ trans('strings.view_category_index_header') }}"
            load-error-message="{{ trans('strings.view_all_error_load_message') }}"
            add-error-message="{{ trans('strings.view_all_error_add_message') }}"
            del-error-message="{{ trans('strings.view_all_error_del_message') }}"
            patch-error-message="{{ trans('strings.view_all_error_patch_message') }}"
            patch-success-message="{{ trans('strings.view_all_success_patch_message') }}"
            modal-yes="{{ trans('strings.modal_yes') }}"
            modal-no="{{ trans('strings.modal_no') }}"
            modal-del-header="{{ trans('strings.view_category_index_modal_del_header') }}"
            modal-del-description="{{ trans('strings.view_category_index_modal_del_description') }}"

            route-get-available-move-to-category="{{ route('category.availableMoveTo') }}"
            categories-dropdown-menu-first-menu-name="{{ trans('strings.form_dropdown_move_item') }}"
            actual-locale="{{ \Illuminate\Support\Facades\App::getLocale() }}"

            availables-locales-list="{{ json_encode(config('codeheuresUtils.availableLocales')) }}">
    </manage-categories>

@endsection