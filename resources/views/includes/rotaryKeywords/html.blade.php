<div class="ui text shape rotary-keywords">
    <div class="sides">
        @php $initiallang_rotatry_keywords = app()->getLocale() @endphp
        @foreach(config('codeheuresUtils.availableLocales') as $lang)
            @php app()->setLocale($lang) @endphp
            @if($lang == $initiallang_rotatry_keywords)
                <div class="side active">
                    <h1>
                        <span>{{ trans('strings.view_portal_list_header_1') }}</span>
                        <span>{{ trans('strings.view_portal_list_header_2') }}</span>
                        <span>{{ trans('strings.view_portal_list_header_3') }}</span>
                        <span>{{ trans('strings.view_portal_list_header_4') }}</span>
                        <span>{{ trans('strings.view_portal_list_header_5') }}</span>
                        <span>{{ trans('strings.view_portal_list_header_6') }}</span>
                    </h1>
                </div>
            @else
                <div class="side">
                    <h1>
                        <span>{{ trans('strings.view_portal_list_header_1') }}</span>
                        <span>{{ trans('strings.view_portal_list_header_2') }}</span>
                        <span>{{ trans('strings.view_portal_list_header_3') }}</span>
                        <span>{{ trans('strings.view_portal_list_header_4') }}</span>
                        <span>{{ trans('strings.view_portal_list_header_5') }}</span>
                        <span>{{ trans('strings.view_portal_list_header_6') }}</span>
                    </h1>
                </div>
            @endif
        @endforeach
        @php app()->setLocale($initiallang_rotatry_keywords) @endphp
    </div>
</div>