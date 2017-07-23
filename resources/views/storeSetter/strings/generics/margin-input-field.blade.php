<store-strings-setter
        strings="{{ json_encode([
                        'key' => 'margin-input-field',
                        'values' => [
                                'coefficientLabel' => trans('strings.view_advert_priceCoefficient_label'),
                                'coefficientTotalLabel' => trans('strings.view_advert_priceCoefficient_total_label'),
                                'coefficientUpdateLabel' => trans('strings.view_advert_show_update_label')
                        ]
                ])}}"
></store-strings-setter>
@include('storeSetter.strings.generics.margin-table')