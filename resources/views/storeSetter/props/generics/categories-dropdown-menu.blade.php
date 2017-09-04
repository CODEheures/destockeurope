<store-properties-setter
        properties="{{ json_encode([
                        'key' => 'categories-dropdown-menu',
                        'values' => [
                                'datas' => \App\Common\CategoryUtils::getAllCategories()
                        ]
                ])}}"
></store-properties-setter>