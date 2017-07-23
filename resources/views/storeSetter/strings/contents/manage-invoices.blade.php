<store-strings-setter
        strings="{{ json_encode([
                        'key' => 'manage-invoices',
                        'values' => [
                                'contentHeader' => trans('strings.view_manage_invoices_label'),
                                'loadErrorMessage' => trans('strings.view_all_error_load_message'),
                                'modalValidHeader' => trans('strings.view_manage_invoice_modal_valid_header'),
                                'modalValidDescriptionOne' => trans('strings.view_manage_invoice_modal_valid_description1'),
                                'modalValidDescriptionTwo' => trans('strings.view_manage_invoice_modal_valid_description2'),
                                'modalNo' => trans('strings.modal_no'),
                                'modalYes' => trans('strings.modal_yes'),
                                'invoiceRefundSuccess' => trans('strings.view_manage_invoice_refund_success'),
                        ]
                ])}}"
></store-strings-setter>
@include('storeSetter.strings.generics.pagination')
@include('storeSetter.strings.invoices.invoice-filter')
@include('storeSetter.strings.invoices.invoice-by-list')