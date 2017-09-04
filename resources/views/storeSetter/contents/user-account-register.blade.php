<!--PROPS-->
<!--STRINGS-->
<store-strings-setter
        strings="{{ json_encode([
                        'key' => 'user-account-register',
                        'values' => [
                                'contentHeader' => trans('strings.menu_register'),
                                'formNameLabel' => trans('strings.form_label_name'),
                                'formEmailLabel' => trans('strings.form_label_email'),
                                'formPasswordLabel' => trans('strings.form_label_password'),
                                'formPasswordConfirmLabel' => trans('strings.form_label_confimr_password'),
                                'formCguCheckLabel' => trans('strings.auth_register_cgu_check', ['link' => route('cgu')]),
                                'formNewsletterCheckLabel' => trans('strings.auth_register_newsletter_check'),
                                'formButtonLabel' => trans('strings.menu_register'),
                                'dividerLabel' => trans('strings.divider_register'),
                        ]
                ])}}"
></store-strings-setter>