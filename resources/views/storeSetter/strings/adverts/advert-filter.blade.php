<store-strings-setter
        strings="{{ json_encode([
                        'key' => 'advert-filter',
                        'values' => [
                                'ribbonClose' => trans('strings.view_filter_ribbon_close'),
                                'ribbonOpen' => trans('strings.view_filter_ribbon_open'),
                                'searchPlaceHolder' => trans('strings.form_input_search_label2'),
                                'urgentLabel' => trans('strings.view_all_urgent'),
                                'isNegociatedLabel' => trans('strings.view_all_negociate'),
                                'priceTitle' => trans('strings.view_filter_price_title'),
                                'quantityTitle' => trans('strings.form_quantity_label')
                        ]
                ])}}"
></store-strings-setter>
@include('storeSetter.strings.generics.notifications-activer')
@include('storeSetter.strings.generics.currencies-button')
@include('storeSetter.strings.generics.categories-dropdown-menu')
@include('storeSetter.strings.generics.location-filter')