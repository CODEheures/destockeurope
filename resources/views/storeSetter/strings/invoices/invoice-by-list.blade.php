<store-strings-setter
        strings="{{ json_encode([
                        'key' => 'invoice-by-list',
                        'values' => [
                                'noResultFoundHeader' => trans('strings.view_advert_list_no_result_header'),
                                'noResultFoundMessage' => trans('strings.view_advert_list_no_result_message'),
                                'listHeaderPaypalCapture' => trans('strings.view_manage_invoices_list_header_paypal_capture'),
                                'listHeaderPaypalVoid' => trans('strings.view_manage_invoices_list_header_paypal_void'),
                                'listHeaderPaypalRefund' => trans('strings.view_manage_invoices_list_header_paypal_refundId'),
                                'listHeaderUsermail' => trans('strings.view_manage_invoices_list_header_usermail'),
                                'listHeaderDate' => trans('strings.view_manage_invoices_list_header_date')
                        ]
                ])}}"
></store-strings-setter>
@include('storeSetter.strings.invoices.invoice-by-list-item')