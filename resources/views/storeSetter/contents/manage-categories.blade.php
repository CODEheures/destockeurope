<!--PROPS-->
<!--STRINGS-->
<store-strings-setter
        strings="{{ json_encode([
                        'key' => 'manage-categories',
                        'values' => [
                            'contentHeader' => trans('strings.view_category_index_header'),
                            'loadErrorMessage' => trans('strings.view_all_error_load_message'),
                            'addErrorMessage' => trans('strings.view_all_error_add_message'),
                            'delErrorMessage' => trans('strings.view_all_error_del_message'),
                            'patchErrorMessage' => trans('strings.view_all_error_patch_message'),
                            'patchSuccessMessage' => trans('strings.view_all_success_patch_message'),
                            'modalYes' => trans('strings.modal_yes'),
                            'modalNo' => trans('strings.modal_no'),
                            'modalDelHeader' => trans('strings.view_category_index_modal_del_header'),
                            'modalDelDescription' => trans('strings.view_category_index_modal_del_description'),
                        ]
                ])}}"
></store-strings-setter>
@include('storeSetter.strings.categories.categories-list-move-to')