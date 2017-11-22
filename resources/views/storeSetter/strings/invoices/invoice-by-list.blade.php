<store-strings-setter
        strings="{{ json_encode([
                        'key' => 'invoice-by-list',
                        'values' => [
                                'noResultFoundHeader' => trans('strings.view_advert_list_no_result_header'),
                                'noResultFoundMessage' => trans('strings.view_advert_list_no_result_message'),
                                'listHeaderTransactionId' => trans('strings.view_manage_invoices_list_header_transaction_id'),
                                'listHeaderCaptured' => trans('strings.view_manage_invoices_list_header_captured'),
                                'listHeaderVoided' => trans('strings.view_manage_invoices_list_header_voided'),
                                'listHeaderRefunded' => trans('strings.view_manage_invoices_list_header_refunded'),
                                'listHeaderUsermail' => trans('strings.view_manage_invoices_list_header_usermail'),
                                'listHeaderDate' => trans('strings.view_manage_invoices_list_header_date'),
                                'loadErrorMessage' => trans('strings.view_all_error_load_message'),
                        ]
                ])}}"
></store-strings-setter>
@include('storeSetter.strings.invoices.invoice-by-list-item')