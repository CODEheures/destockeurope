@extends('layouts.app')

@section('content')
    <div class="ui one column grid">
        <div class="column">
            <h2 class="ui header">{{ trans('strings.view_category_index_header') }}</h2>
            <div class="one wide column">
                <categories-updatable
                        load-error-message="{{ trans('strings.view_all_error_load_message') }}"
                        add-error-message="{{ trans('strings.view_all_error_add_message') }}"
                        del-error-message="{{ trans('strings.view_all_error_del_message') }}"
                        patch-error-message="{{ trans('strings.view_all_error_patch_message') }}"
                        modal-yes="{{ trans('strings.modal_yes') }}"
                        modal-no="{{ trans('strings.modal_no') }}"
                        modal-del-header = "{{ trans('strings.view_category_index_modal_del_header') }}"
                        modal-del-description = "{{ trans('strings.view_category_index_modal_del_description') }}"
                        route-meta-category="{{ route('metaCategory.index') }}"
                        route-category="{{ route('category.index') }}">
                </categories-updatable>
            </div>
        </div>
    </div>
@endsection