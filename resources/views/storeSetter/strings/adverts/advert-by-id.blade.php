<store-strings-setter
        strings="{{ json_encode([
                        'key' => 'advert-by-id',
                        'values' => [
                                'totalQuantityLabel' => trans('strings.form_quantity_label'),
                                'lotMiniQuantityLabel' => trans('strings.form_lot_mini_label'),
                                'isNegociatedLabel' => trans('strings.view_all_negociate'),
                                'priceInfoLabel' => trans('strings.view_advert_list_price_info'),
                                'priceLabel' => trans('strings.view_advert_form_price_label'),
                                'refLabel' => trans('strings.view_advert_form_ref_label'),
                                'discountOnTotalLabel' => trans('strings.view_advert_form_discount_total_label'),
                                'CompletePriceLabel' => trans('strings.view_advert_form_complete_package_price')
                        ]
                ])}}"
></store-strings-setter>
@include('storeSetter.strings.generics.swiper-top')