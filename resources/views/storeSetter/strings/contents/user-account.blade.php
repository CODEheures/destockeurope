<store-strings-setter
        strings="{{ json_encode([
                        'key' => 'user-account',
                        'values' => [
                                'loadErrorMessage' => trans('strings.view_all_error_load_message'),
                                'accountPatchSuccess' => trans('strings.view_user_account_patch_success'),
                                'accountPreferencesLabel' => trans('strings.view_user_account_preferences_label'),
                                'nameLabel' => trans('strings.form_label_name'),
                                'emailLabel' => trans('strings.form_label_email'),
                                'emailChangeLabel' => trans('strings.link_change_email_link'),
                                'passwordChangeLabel' => trans('strings.link_change_password_link'),
                                'phoneLabel' => trans('strings.form_label_phone'),
                                'compagnyDivider' => trans('strings.view_user_account_compagny_divider_label'),
                                'compagnyNameLabel' => trans('strings.view_user_account_compagny_name_label'),
                                'compagnyNumberLabel' => trans('strings.view_user_account_compagny_number_label'),
                                'contentHeader' => isset($title) ?  $title : trans('strings.view_advert_create_header'),
                                'googlemapDivider' => trans('strings.form_googlemap_label'),
                                'formValidationButtonLabel' => trans('strings.form_button_validation'),
                                'formValidationFailsButtonLabel' => trans('strings.view_all_error_reload_message'),
                                'formPointingMinimumChars' => trans('strings.form_pointing_minimum_chars'),
                                'formVatWarningLabel' => trans('strings.view_user_account_compagny_number_warning_label'),
                                'formVatOnCheckProgressLabel' => trans('strings.view_user_account_compagny_number_check_progress'),
                                'formVatIdentifierLabel' => trans('strings.view_user_account_compagny_number_identifier'),
                                'stepOneTitle' => isset($title) ?  $title : trans('strings.view_advert_steps_1_title'),
                                'stepTwoTitle' => trans('strings.view_advert_steps_2_title'),
                                'stepThreeTitle' => trans('strings.view_advert_steps_3_title'),
                                'stepThreeTitlePost' => trans('strings.view_advert_steps_3_title_post'),
                                'stepOneDescription' => isset($title) ?  '' : trans('strings.view_advert_steps_1_description'),
                                'stepTwoDescription' => trans('strings.view_advert_steps_2_description'),
                                'stepThreeDescription' => trans('strings.view_advert_steps_3_description'),
                                'localesFirstMenuName' => trans('strings.view_user_account_locale_dropdown_label'),
                                'currenciesFirstMenuName' => trans('strings.view_user_account_currency_dropdown_label')
                        ]
                ])}}"
></store-strings-setter>
@include('storeSetter.strings.generics.currencies-dropdown-2')
@include('storeSetter.strings.generics.locales-dropdown-2')
@include('storeSetter.strings.generics.locales-dropdown')
@include('storeSetter.strings.generics.googleMap')