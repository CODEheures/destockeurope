<store-properties-setter
        properties="{{ json_encode([
                        'key' => 'adverts-by-list-item',
                        'values' => [
                                'ranges' => isset($ranges) ? $ranges: null,
                                'list' => $advertsList
                        ]
                ])}}"
></store-properties-setter>