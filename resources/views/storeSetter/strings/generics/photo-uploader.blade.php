<store-strings-setter
        strings="{{ json_encode([
                        'key' => 'photo-uploader',
                        'values' => [
                                'photoBtnLabel' => trans('strings.form_file_add_photo_btn_label'),
                                'photoBtnCancel' => trans('strings.form_file_cancel_video_label'),
                                'photoLabel' => trans('strings.form_file_add_photo_label', ['minwidth' => env('MIN_WIDTH'), 'minheight' => env('MIN_HEIGHT'), 'maxsize' => env('PHOTO_MAX_SIZE_MB')]),
                                'freePhotoHelpHeaderSingular' => trans_choice('strings.form_file_add_free_photo_help_header',1),
                                'freePhotoHelpHeaderPlural' => trans_choice('strings.form_file_add_free_photo_help_header',2),
                                'payPhotoHelpHeaderSingular' => trans_choice('strings.form_file_add_pay_photo_help_header',1),
                                'payPhotoHelpHeaderPlural' => trans_choice('strings.form_file_add_pay_photo_help_header',2),
                                'photoHelpContent' => trans('strings.form_file_add_photo_help_content',['nb' => config('runtime.nbFreePictures'), 'link' => route('home')]),
                                'mainPhotoLabel' => trans('strings.form_radio_main_photo_label'),
                        ]
                ])}}"
></store-strings-setter>