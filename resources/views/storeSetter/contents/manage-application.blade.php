<!--PROPS-->
<!--STRINGS-->
<store-strings-setter
        strings="{{ json_encode([
                        'key' => 'manage-application',
                        'values' => [
                                'contentHeader' => trans('strings.view_manage_index_header'),
                                'loadErrorMessage' => trans('strings.view_all_error_load_message'),
                                'patchErrorMessage' => trans('strings.view_all_error_patch_message'),
                                'patchSuccessMessage' => trans('strings.view_all_success_patch_message'),
                                'invalidImageMessage' => trans('strings.view_all_error_invalid_image_message'),
                                'advertPreferencesLabel' => trans('strings.view_manage_adverts_label'),
                                'advertNbFreePicturesLabel' => trans('strings.view_manage_adverts_nb_free_pictures_label'),
                                'advertNbMaxPicturesLabel' => trans('strings.view_manage_adverts_nb_max_pictures_label'),
                                'costLabel' => trans('strings.view_manage_cost_label'),
                                'advertPhotoCostLabel' => trans('strings.view_manage_adverts_photo_cost_label'),
                                'advertUrgentCostLabel' => trans('strings.view_manage_adverts_urgent_cost_label'),
                                'advertVideoCostLabel' => trans('strings.view_manage_adverts_video_cost_label'),
                                'advertRenewCostLabel' => trans('strings.view_manage_adverts_renew_cost_label'),
                                'advertEditCostLabel' => trans('strings.view_manage_adverts_edit_cost_label'),
                                'advertBackToTopCostLabel' => trans('strings.view_manage_adverts_backToTop_cost_label'),
                                'advertHighlightCostLabel' => trans('strings.view_manage_adverts_highlight_cost_label'),
                                'advertvalidityCostsLabel' => trans('strings.view_manage_adverts_validity_costs_label'),
                                'advertPerPageLabel' => trans('strings.view_manage_adverts_per_page_label'),
                                'advertResumeLenghtLabel' => trans('strings.view_manage_resume_length_label'),
                                'searchLabel' => trans('strings.view_manage_search_label'),
                                'minLengthSearchLabel' => trans('strings.view_manage_min_length_search_label'),
                                'maxNumberOfSearchResultsLabel' => trans('strings.view_manage_max_result_search_label'),
                                'adsPreferencesLabel' => trans('strings.view_manage_ads_label'),
                                'adsFrequencyLabel' => trans('strings.view_manage_ads_frequency_label'),
                                'masterAdsActivationLabel' => trans('strings.view_manage_master_ads_activation_label'),
                                'masterAdsUrlLabel' => trans('strings.view_manage_master_ads_url_label'),
                                'masterAdsUrlLinkLabel' => trans('strings.view_manage_master_ads_url_link_label'),
                                'masterAdsOffsetYLabel' => trans('strings.view_manage_master_offset_y_label'),
                                'appearanceLabel' => trans('strings.view_manage_appearance_label'),
                                'welcomeAppearanceLabel' => trans('strings.view_manage_welcome_type_label'),
                                'listTypeFirstMenuName' => trans('strings.view_manage_welcome_type_label'),
                        ]
                ])}}"
></store-strings-setter>
@include('storeSetter.strings.generics.type-radio-button')