<!--PROPS-->
<!--STRINGS-->
<store-strings-setter
        strings="{{ json_encode([
                        'key' => 'approve-advert-form',
                        'values' => [
                                'contentHeader' => trans('strings.view_advert_approve_header'),
                                'segmentEditLabel' => trans('strings.view_advert_approve_isEditSegment_label'),
                                'advertFormPreviewHeaderLabel' => trans('strings.view_advert_preview_header_label'),
                                'advertFormParamsHeaderLabel' => trans('strings.view_advert_params_header_label'),
                                'fieldEditLabel' => trans('strings.view_advert_approve_isEditField_label'),
                                'trustedProviderLabel' => trans('strings.view_account_trusted_provider'),
                                'toggleApproveLabel' => trans('strings.view_advert_approve_toggle_label'),
                                'toggleDisapproveLabel' => trans('strings.view_advert_disapprove_toggle_label'),
                                'textDisapproveReason' => trans('strings.view_advert_disapprove_reason_label'),
                                'formValidationButtonLabel' => trans('strings.form_button_validation'),
                                'modalValidHeader' => trans('strings.view_advert_approve_modal_valid_header'),
                                'modalValidDescription' => trans('strings.view_advert_approve_modal_valid_description'),
                                'modalNo' => trans('strings.modal_no'),
                                'modalYes' => trans('strings.modal_yes'),
                                'advertApproveSuccess' => trans('strings.view_advert_approve_success'),
                                'urgentLabel' => trans('strings.view_all_urgent'),
                                'loadErrorMessage' => trans('strings.view_all_error_load_message'),
                        ]
                ])}}"
></store-strings-setter>
@include('storeSetter.strings.adverts.advert-by-id')
@include('storeSetter.strings.generics.quantities-input-field')
@include('storeSetter.strings.generics.margin-input-field')