<!--PROPS-->
<!--STRINGS-->
<store-strings-setter
        strings="{{ json_encode([
                        'key' => 'portal',
                        'values' => [
                                'seeAdvertLabel' => trans('strings.view_portal_button_filter_label'),
                                'newsLetterTitle' => trans('strings.view_portal_newsletter_title'),
                                'newsLetterDescription' => trans('strings.view_portal_newsletter_description'),
                                'newsLetterButtonLabel' => trans('strings.menu_subscribe'),
                                'newsLetterUnsubscribeLinkLabel' => trans('strings.menu_unsubscibeNewsLetter_short'),
                                'newsLetterEmailPlaceHolder' => trans('strings.form_label_email'),
                                'newsLetterPhonePlaceHolder' => trans('strings.form_label_phone'),
                                'newsLetterNamePlaceHolder' => trans('strings.form_label_name'),
                                'header' => trans('strings.view_home_header'),
                                'divider' => trans('strings.view_portal_divider'),
                                'concept' => trans('strings.view_portal_concept')
                        ]
                ])}}"
></store-strings-setter>
@include('storeSetter.strings.generics.location-filter')
@include('storeSetter.strings.adverts.advert-highlight')