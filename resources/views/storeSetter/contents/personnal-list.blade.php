<!--PROPS-->
@include('storeSetter.props.adverts.advert-by-list')
<!--STRINGS-->
<store-strings-setter
        strings="{{ json_encode([
                        'key' => 'personnal-list',
                        'values' => [
                                'loadErrorMessage' => trans('strings.view_all_error_load_message'),
                                'updateSuccessMessage' => trans('strings.view_user_account_patch_success'),
                                'modalValidHeader' => trans('strings.view_advert_show_modal_delete_header'),
                                'modalValidDescription' => trans('strings.view_advert_show_modal_delete_description'),
                                'modalNo' => trans('strings.modal_no'),
                                'modalYes' => trans('strings.modal_yes'),
                        ]
                ])}}"
></store-strings-setter>
@include('storeSetter.strings.adverts.advert-by-list')
@include('storeSetter.strings.adverts.advert-simple-search-filter')
@include('storeSetter.strings.generics.pagination')