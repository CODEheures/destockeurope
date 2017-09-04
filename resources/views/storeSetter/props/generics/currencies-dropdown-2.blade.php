<store-properties-setter
        properties="{{ json_encode([
                        'key' => 'currencies-dropdown-menu-2',
                        'values' => [
                                'datas' => \App\Common\MoneyUtils::listUserCurrencies()
                        ]
                ])}}"
></store-properties-setter>