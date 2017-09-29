<!--PROPS-->
<!--STRINGS-->
<store-strings-setter
        strings="{{ json_encode([
                        'key' => 'review-for-payment',
                        'values' => [
                                'contentHeader' => trans('strings.view_reviewForPayment_header'),
                                'tableHeaderOptionName' => trans('strings.view_reviewForPayment_table_option_name'),
                                'tableHeaderOptionQuantity' => trans('strings.view_reviewForPayment_table_option_quantity'),
                                'tableHeaderOptionCost' => trans('strings.view_reviewForPayment_table_option_cost'),
                                'tableTotalExclVat' => trans('strings.view_reviewForPayment_table_total_excl_vat'),
                                'tableTotalVat' => trans('strings.view_reviewForPayment_table_vat'),
                                'tableTotalInclVat' => trans('strings.view_reviewForPayment_table_total_incl_vat'),
                                'toggleCgvLabel' => trans('strings.view_reviewForPayment_cgv', ['link' => route('cgv')]),
                                'lockInfoHeader' => trans('strings.view_reviewForPayment_lock_info_header'),
                                'lockInfoContent' => trans('strings.view_reviewForPayment_lock_info_content'),
                                'dividerChoiceLabel' => trans('strings.divider_payment'),
                                'paymentCardNumberLabel' => trans('strings.payment_card_number_label'),
                                'paymentCardExpirationLabel' => trans('strings.payment_card_expiration_label'),
                                'paymentCardCvcLabel' => trans('strings.payment_card_cvc_label'),
                                'payment_btn_check_card' => trans('strings.payment_btn_check_card'),
                                'payment_btn_valid' => trans('strings.payment_btn_valid'),
                                'stepOneTitle' => isset($title) ?  $title : trans('strings.view_advert_steps_1_title'),
                                'stepTwoTitle' => trans('strings.view_advert_steps_2_title'),
                                'stepThreeTitle' => trans('strings.view_advert_steps_3_title'),
                                'stepThreeTitlePost' => trans('strings.view_advert_steps_3_title_post'),
                                'stepOneDescription' => isset($title) ?  '' : trans('strings.view_advert_steps_1_description'),
                                'stepTwoDescription' => trans('strings.view_advert_steps_2_description'),
                                'stepThreeDescription' => trans('strings.view_advert_steps_3_description'),
                                'errorInPaymentProcess' => trans('strings.payment_error_process'),
                                'formInvalid' => trans('strings.payment_form_invalid'),
                                'cardInvalid' => trans('strings.payment_card_invalid'),
                                'billingAgreementDescription' => trans('strings.payment_billing_description')
                        ]
                ])}}"
></store-strings-setter>