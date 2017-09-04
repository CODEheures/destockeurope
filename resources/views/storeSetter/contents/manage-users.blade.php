<!--PROPS-->
<!--STRINGS-->
<store-strings-setter
        strings="{{ json_encode([
                        'key' => 'manage-users',
                        'values' => [
                                'contentHeader' => trans('strings.view_manage_users_header'),
                                'loadErrorMessage' => trans('strings.view_all_error_load_message'),
                                'modalValidHeader' => trans('strings.view_manage_users_modal_valid_header'),
                                'modalValidDescription' => trans('strings.view_manage_users_modal_valid_description'),
                                'modalNo' => trans('strings.modal_no'),
                                'modalYes' => trans('strings.modal_yes'),
                                'deleteUserSuccess' => trans('strings.view_manage_users_delete_success'),
                        ]
                ])}}"
></store-strings-setter>
@include('storeSetter.strings.generics.pagination')
@include('storeSetter.strings.users.user-filter')
@include('storeSetter.strings.users.users-by-list')