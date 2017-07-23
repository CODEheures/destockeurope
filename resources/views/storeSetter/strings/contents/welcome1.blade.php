<store-strings-setter
        strings="{{ json_encode([
                        'key' => 'welcome1',
                        'values' => [
                                'loadErrorMessage' => trans('strings.view_all_error_load_message'),
                                'bookmarkSuccess' => trans('strings.view_advert_show_bookmark_success'),
                                'unbookmarkSuccess' => trans('strings.view_advert_show_unbookmark_success'),
                                'modalValidHeader' => trans('strings.view_advert_show_modal_delete_header'),
                                'modalValidDescription' => trans('strings.view_advert_show_modal_delete_description'),
                                'modalNo' => trans('strings.modal_no'),
                                'modalYes' => trans('strings.modal_yes'),
                                'allLabel' => trans('strings.view_all_all'),
                                'header' => trans('strings.view_home_header'),
                        ]
                ])}}"
></store-strings-setter>
@include('storeSetter.strings.adverts.advert-filter')
@include('storeSetter.strings.adverts.advert-by-list')
@include('storeSetter.strings.adverts.advert-highlight')
@include('storeSetter.strings.generics.pagination')
@include('storeSetter.strings.categories.categories-horizontal-menu')