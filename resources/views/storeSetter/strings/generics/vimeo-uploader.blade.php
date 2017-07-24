<store-strings-setter
        strings="{{ json_encode([
                        'key' => 'vimeo-uploader',
                        'values' => [
                                'videoBtnLabel' => trans('strings.form_file_add_video_separator'),
                                'videoLabel' => trans('strings.form_file_add_video_label', ['maxsize' => env('VIDEO_MAX_SIZE_MB')]),
                                'videoBtnDelete' => trans('strings.form_file_del_video_label'),
                                'videoBtnCancel' => trans('strings.form_file_cancel_video_label'),
                                'waitingMessage' => trans('strings.form_waiting_for_process'),
                                'transcodeMessage' => trans('strings.form_waiting_for_transcode'),
                        ]
                ])}}"
></store-strings-setter>