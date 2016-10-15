@extends('layouts.app')

@section('content')
    <div class="ui one column grid">
        <div class="column">
            <h2 class="ui header">{{ trans('strings.view_advert_approve_header') }}</h2>
            <div class="one wide column">
                <approve-advert-form
                        advert-form-title-label=""
                        route-get-adverts-list="{{ route('advert.listApprove') }}"
                        route-advert-approve="{{ route('advert.approve') }}"
                        load-error-message="{{ trans('strings.view_all_error_load_message') }}"
                        toggle-approve-label="{{ trans('strings.view_advert_approve_toggle_label') }}"
                        toggle-disapprove-label="{{ trans('strings.view_advert_disapprove_toggle_label') }}"
                        form-validation-button-label="{{ trans('strings.form_button_validation') }}"
                        modal-valid-header="{{ trans('strings.view_advert_approve_modal_valid_header') }}"
                        modal-valid-description="{{ trans('strings.view_advert_approve_modal_valid_description') }}"
                        modal-no="{{ trans('strings.modal_no') }}"
                        modal-yes="{{ trans('strings.modal_yes') }}"
                        advert-title-label="{{ trans('strings.view_advert_form_title_label') }}"
                        advert-description-label="{{ trans('strings.view_advert_form_description_label') }}"
                        advert-price-label="{{ trans('strings.view_advert_form_price_label') }}">
                </approve-advert-form>
            </div>
        </div>
    </div>
@endsection