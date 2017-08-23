<?php

return [
    'watermark_path' => storage_path('app/watermark.png'),

    'formats' => [
        [
            'name' => 'normal',
            'width' => 1200,
            'ratio' => env('IMAGE_RATIO'),
            'back_color' => '#fafafa',
            'format_encoding' => 'webp',
        ],
        [
            'name' => 'thumb',
            'width' => 600,
            'ratio' => 1,
            'back_color' => '#ffffff',
            'format_encoding' => 'webp',
        ]
    ],

    'service' => [
        'domains' => [
            'http://statics.destockeurope.progress',
            'http://statics2.destockeurope.progress',
        ],
        'urls' => [
            'routeGetMd5' => '/private/getmd5',
            'routeCancelMd5' => '/private/cancelmd5',
            'routeSavePicture' => '/private/savepicture',
            'routeGetInfos' => '/private/infos',
            'routeDelete' => '/private'
        ]
    ],

];
