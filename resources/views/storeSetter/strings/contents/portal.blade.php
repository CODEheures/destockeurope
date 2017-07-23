<store-strings-setter
        strings="{{ json_encode([
                        'key' => 'portal',
                        'values' => [
                                'seeAdvertLabel' => trans('strings.view_portal_button_filter_label'),
                                'newsLetterTitle' => trans('strings.view_portal_newsletter_title'),
                                'newsLetterDescription' => trans('strings.view_portal_newsletter_description'),
                                'newsLetterButtonLabel' => trans('strings.menu_subscribe'),
                                'newsLetterEmailPlaceHolder' => trans('strings.form_label_email'),
                                'newsLetterPhonePlaceHolder' => trans('strings.form_label_phone'),
                                'newsLetterNamePlaceHolder' => trans('strings.form_label_name'),
                        ]
                ])}}"
></store-strings-setter>
@include('storeSetter.strings.generics.location-filter')