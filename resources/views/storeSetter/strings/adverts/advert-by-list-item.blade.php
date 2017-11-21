<store-strings-setter
        strings="{{ json_encode([
                        'key' => 'adverts-by-list-item',
                        'values' => [
                                'totalQuantityLabel' => trans('strings.form_quantity_label'),
                                'lotMiniQuantityLabel' => trans('strings.form_lot_mini_label'),
                                'urgentLabel' => trans('strings.view_all_urgent'),
                                'isNegociatedLabel' => trans('strings.view_all_negociate'),
                                'renewAdvertLabel' => trans('strings.view_advert_show_renew_label'),
                                'deleteAdvertLabel' => trans('strings.view_advert_show_delete2_label'),
                                'validationOnProgressLabel' => trans('strings.view_advert_validation_on_progress'),
                                'bookmarkInfo' => trans('strings.view_advert_show_bookmark_info'),
                                'bookmarkSuccess' => trans('strings.view_advert_show_bookmark_success'),
                                'unbookmarkSuccess' => trans('strings.view_advert_show_unbookmark_success'),
                                'viewsInfo' => trans('strings.view_advert_show_views_info'),
                                'trustedProviderLabel' => trans('strings.view_account_trusted_provider'),
                                'refLabel' => trans('strings.view_advert_form_ref_label'),
                                'providerLabel' => trans('strings.view_advert_form_provider_label'),
                                'loadErrorMessage' => trans('strings.view_all_error_load_message'),
                        ]
                ])}}"
></store-strings-setter>
@include('storeSetter.strings.generics.margin-input-field')
@include('storeSetter.strings.generics.advert-manage-button')
@include('storeSetter.strings.generics.quantities-input-field')