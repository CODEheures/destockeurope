<store-strings-setter
        strings="{{ json_encode([
                        'key' => 'showAdvert1',
                        'values' => [
                                'loadErrorMessage' => trans('strings.view_all_error_load_message'),
                                'sendSuccessMessage' => trans('strings.view_advert_show_message_send'),
                                'sendSuccessReportMessage' => trans('strings.view_advert_show_report_send'),
                                'formValidationEmail' => trans('strings.form_validation_email'),
                                'formPointingMinimumChars' => trans('strings.form_pointing_minimum_chars'),
                                'formPointingMaximumChars' => trans('strings.form_pointing_maximum_chars'),
                                'contactLabel' => trans('strings.view_advert_show_contact_label'),
                                'reportLabel' => trans('strings.view_advert_report_label'),
                                'bookmarkInfo' => trans('strings.view_advert_show_bookmark_info'),
                                'bookmarkLabel' => trans('strings.view_advert_show_bookmark_label'),
                                'unbookmarkLabel' => trans('strings.view_advert_show_unbookmark_label'),
                                'formMessageLabel' => trans('strings.form_label_message_input'),
                                'formMessageEmailLabel' => trans('strings.form_label_email'),
                                'formMessageNameLabel' => trans('strings.form_label_name'),
                                'formMessagePhoneLabel' => trans('strings.form_label_phone'),
                                'formMessageCompagnyLabel' => trans('strings.view_user_account_compagny_name_label'),
                                'formSeePhoneLabel' => trans('strings.form_see_phone_label'),
                                'formMessageSendLabel' => trans('strings.modal_send'),
                                'formMessageCancelLabel' => trans('strings.modal_cancel'),
                                'bookmarkSuccess' => trans('strings.view_advert_show_bookmark_success'),
                                'unbookmarkSuccess' => trans('strings.view_advert_show_unbookmark_success'),
                                'modalValidHeader' => trans('strings.view_advert_show_modal_delete_header'),
                                'modalValidDescription' => trans('strings.view_advert_show_modal_delete_description'),
                                'modalNo' => trans('strings.modal_no'),
                                'modalYes' => trans('strings.modal_yes'),
                                'allLabel' => trans('strings.view_all_all'),
                                'urgentLabel' => trans('strings.view_all_urgent')
                        ]
                ])}}"
></store-strings-setter>
@include('storeSetter.strings.adverts.advert-by-id')
@include('storeSetter.strings.generics.advert-manage-button')