<?php

return [
    'watermark_path' => storage_path('app/watermark.png'),
    'max_width' => 1200,
    'picture_ratio' => env('IMAGE_RATIO'),
    'thumb_size' => 600,
    'thumb_ratio' => 1,
    'picture_back_color' => '#fafafa',
    'thumb_back_color' => '#ffffff',
    'format_encoding' => 'jpg',
    'routeGetMd5' => 'http://statics.destockeurope.progress/private/getmd5',
    'routeCancelMd5' => 'http://statics.destockeurope.progress/private/cancelmd5',
    'routeSavePicture' => 'http://statics.destockeurope.progress/private/savepicture',
    'routeGetInfos' => 'http://statics.destockeurope.progress/private/infos'
];
