<!--PROPS-->
<!--STRINGS-->
<store-strings-setter
        strings="{{ json_encode([
                        'key' => 'create-invoice-for-delegation',
                        'values' => [
                                'contentHeader' => trans('strings.view_create_invoice_for_delegation_label', ['delegateName' => env('DELEGATE_NAME')]),
                                'loadErrorMessage' => trans('strings.view_all_error_load_message'),
                                'modalValidHeader' => trans('strings.view_create_invoice_for_delegation_modal_valid_header'),
                                'modalValidDescriptionOne' => trans('strings.view_create_invoice_for_delegation_modal_valid_description1', ['delegateName' => env('DELEGATE_NAME')]),
                                'modalValidDescriptionTwo' => trans('strings.view_create_invoice_for_delegation_modal_valid_description2'),
                                'modalNo' => trans('strings.modal_no'),
                                'modalYes' => trans('strings.modal_yes'),
                                'customerLabel' => trans('strings.view_create_invoice_for_delegation_form_customer_label'),
                                'descriptionLabel' => trans('strings.view_create_invoice_for_delegation_form_description_label'),
                                'dateLabel' => trans('strings.view_create_invoice_for_delegation_form_date_label'),
                                'margeLabel' => trans('strings.view_create_invoice_for_delegation_form_marge_label'),
                                'buttonLabel' => trans('strings.view_create_invoice_for_delegation_form_button_label'),
                                'goToInvoiceLabel' => trans('strings.view_create_invoice_for_delegation_goto_button_label')
                        ]
                ])}}"
></store-strings-setter>