<!--PROPS-->
@include('storeSetter.props.generics.categories-dropdown-menu')
@include('storeSetter.props.generics.currencies-dropdown-2')
@include('storeSetter.props.generics.categories-select-menu')
<!--STRINGS-->
<store-strings-setter
        strings="{{ json_encode([
                        'key' => 'createOrEditAdvert',
                        'values' => [
                                'contentHeader' => isset($user) && $user->isSupplier ? trans('strings.view_advert_create_header_delegation'):trans('strings.view_advert_create_header'),
                                'formPreviewHeaderLabel' => trans('strings.view_advert_preview_header_label'),
                                'formParamsHeaderLabel' => trans('strings.view_advert_params_header_label'),
                                'formTitleLabel' => trans('strings.view_advert_form_title_label'),
                                'formRefLabel' => trans('strings.view_advert_form_ref_label'),
                                'formDescriptionLabel' => trans('strings.view_advert_form_description_label'),
                                'formPriceLabel' => trans('strings.view_advert_form_price_label'),
                                'formDiscountLabel' => trans('strings.view_advert_form_discount_total_label'),
                                'formBuyingPriceLabel' => trans('strings.view_advert_form_buyingPrice_label'),
                                'formGooglemapLabel' => trans('strings.form_googlemap_label'),
                                'formPhotoSeparator' => trans('strings.form_file_add_photo_separator'),
                                'validationButtonLabel' => trans('strings.form_button_validation'),
                                'validationButtonLabel2' => trans('strings.form_button_validation2'),
                                'validationButtonLabel2b' => trans('strings.form_button_validation2b'),
                                'formPointingMinimumChars' => trans('strings.form_pointing_minimum_chars'),
                                'formTotalQuantityLabel' => trans('strings.form_quantity_label'),
                                'formLotMiniQuantityLabel' => trans('strings.form_lot_mini_label'),
                                'formUrgentLabel' => isset($user) && $user->isSupplier ? trans('strings.form_urgent_delegation', ['price' => config('runtime.urgentCost')]):trans('strings.form_urgent', ['price' => config('runtime.urgentCost')]),
                                'exampleUrgentLabel' => trans('strings.view_all_urgent'),
                                'formIsNegociatedLabel' => trans('strings.form_isNegociated'),
                                'exampleIsNegociatedLabel' => trans('strings.view_all_negociate'),
                                'marginHeader' => trans('strings.view_advert_form_margin_header'),
                                'categoryHeader' => trans('strings.menu_category'),
                                'currenciesHeader' => trans('strings.view_user_account_currency_dropdown_label'),
                                'videoHeader' => trans('strings.form_file_add_video_separator'),
                                'categoryFieldRequired' => trans('strings.view_advert_form_category_field_required'),
                                'photoFieldRequired' => trans('strings.view_advert_form_photo_field_required'),
                                'stepOneTitle' => trans('strings.view_advert_steps_1_title'),
                                'stepTwoTitle' => trans('strings.view_advert_steps_2_title'),
                                'stepThreeTitle' => trans('strings.view_advert_steps_3_title'),
                                'stepThreeTitlePost' => trans('strings.view_advert_steps_3_title_post'),
                                'stepOneDescription' => trans('strings.view_advert_steps_1_description'),
                                'stepTwoDescription' => isset($user) && $user->isSupplier ? trans('strings.view_advert_steps_2_description_delegation'):trans('strings.view_advert_steps_2_description'),
                                'stepThreeDescription' => isset($user) && $user->isSupplier ? trans('strings.view_advert_steps_3_description_delegation'):trans('strings.view_advert_steps_3_description'),
                                'listTypeFirstMenuName' => trans('strings.view_advert_list_type_dropdown_label'),
                                'allLabel' => trans('strings.view_all_all'),
                                'defaultBreadcrumb' => trans('strings.view_advert_form_default_breadcrumb'),
                                'urgentLabel' => trans('strings.view_all_urgent'),
                                'loadErrorMessage' => trans('strings.view_all_error_load_message'),
                                'CompletePriceLabel' => trans('strings.view_advert_form_complete_package_price')
                        ]
                ])}}"
></store-strings-setter>
@include('storeSetter.strings.generics.type-radio-button')
@include('storeSetter.strings.generics.margin-table')
@include('storeSetter.strings.generics.currencies-dropdown-2')
@include('storeSetter.strings.generics.googleMap')
@include('storeSetter.strings.generics.photo-uploader')
@include('storeSetter.strings.generics.vimeo-uploader')
@include('storeSetter.strings.generics.swiper-top')
@include('storeSetter.strings.generics.categories-dropdown-menu')
@include('storeSetter.strings.generics.categories-select-menu')