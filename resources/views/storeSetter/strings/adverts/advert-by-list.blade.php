<store-strings-setter
        strings="{{ json_encode([
                        'key' => 'adverts-by-list',
                        'values' => [
                                'priceInfoLabel' => trans('strings.view_advert_list_price_info'),
                                'noResultFoundHeader' => trans('strings.view_advert_list_no_result_header'),
                                'noResultFoundMessage' => trans('strings.view_advert_list_no_result_message'),
                        ]
                ])}}"
></store-strings-setter>
@include('storeSetter.strings.adverts.advert-by-list-item')