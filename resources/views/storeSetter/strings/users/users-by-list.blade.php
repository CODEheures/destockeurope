<store-strings-setter
        strings="{{ json_encode([
                        'key' => 'users-by-list',
                        'values' => [
                                'noResultFoundHeader' => trans('strings.view_advert_list_no_result_header'),
                                'noResultFoundMessage' => trans('strings.view_advert_list_no_result_message'),
                                'roleUserLabel' => trans('strings.view_manage_invoices_list_role_button_label'),
                                'listHeaderUsermail' => trans('strings.view_manage_users_list_header_mail'),
                                'listHeaderName' => trans('strings.view_manage_users_list_header_name'),
                                'listHeaderCompagny' => trans('strings.view_manage_users_list_header_compagny'),
                                'listHeaderRegisterDate' => trans('strings.view_manage_users_list_header_register_date'),
                                'listHeaderVatNumber' => trans('strings.view_manage_users_list_header_vat_number'),
                                'listVatVerificationNumberLabel' => trans('strings.view_manage_users_list_vat_verification_number_label'),
                                'listHeaderAddress' => trans('strings.view_manage_users_list_header_address')
                        ]
                ])}}"
></store-strings-setter>
@include('storeSetter.strings.users.users-by-list-item')