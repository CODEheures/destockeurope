@extends('layouts.app')

@section('content')

        <manage-categories
                content-header="{{ trans('strings.view_category_index_header') }}"
                load-error-message="{{ trans('strings.view_all_error_load_message') }}"
                add-error-message="{{ trans('strings.view_all_error_add_message') }}"
                del-error-message="{{ trans('strings.view_all_error_del_message') }}"
                patch-error-message="{{ trans('strings.view_all_error_patch_message') }}"
                patch-success-message="{{ trans('strings.view_all_success_patch_message') }}"
                modal-yes="{{ trans('strings.modal_yes') }}"
                modal-no="{{ trans('strings.modal_no') }}"
                modal-del-header = "{{ trans('strings.view_category_index_modal_del_header') }}"
                modal-del-description = "{{ trans('strings.view_category_index_modal_del_description') }}"
                route-category="{{ route('category.index') }}"
                availables-locales-list="{{ json_encode(config('app.locales')) }}">
        </manage-categories>

@endsection