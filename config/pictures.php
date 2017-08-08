<?php

return [

    'token' => md5(env('PICTURES_MANAGER_SECRET').date('z')),
    'watermark_path' => storage_path('app/watermark.png'),
    'max_width' => 1200,
    'picture_ratio' => env('IMAGE_RATIO'),
    'thumb_size' => 600,
    'thumb_ratio' => 1,
    'picture_back_color' => '#fafafa',
    'thumb_back_color' => '#ffffff',
    'format_encoding' => 'jpg'
];
