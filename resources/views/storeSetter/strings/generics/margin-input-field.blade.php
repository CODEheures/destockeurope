<store-strings-setter
        strings="{{ json_encode([
                        'key' => 'margin-input-field',
                        'values' => [
                                'coefficientLabel' => trans('strings.view_advert_priceCoefficient_label'),
                                'coefficientTotalLabel' => trans('strings.view_advert_priceCoefficient_total_label'),
                                'coefficientUpdateLabel' => trans('strings.view_advert_show_update_label'),
                                'loadErrorMessage' => trans('strings.view_all_error_load_message'),
                                'updateSuccessMessage' => trans('strings.view_user_account_patch_success'),
                        ]
                ])}}"
></store-strings-setter>
@include('storeSetter.strings.generics.margin-table')