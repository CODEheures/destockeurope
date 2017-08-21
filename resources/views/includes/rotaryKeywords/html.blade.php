<div class="rotary-keywords">
    @php $initiallang_rotatry_keywords = app()->getLocale() @endphp
    <h1>
        @foreach(config('codeheuresUtils.availableLocales') as $lang)
            @php app()->setLocale($lang) @endphp
            @if($lang == $initiallang_rotatry_keywords)
                <span>{{ trans('strings.view_portal_list_header_1') }}</span>
                <span>{{ trans('strings.view_portal_list_header_2') }}</span>
                <span>{{ trans('strings.view_portal_list_header_3') }}</span>
                <span>{{ trans('strings.view_portal_list_header_4') }}</span>
                <span>{{ trans('strings.view_portal_list_header_5') }}</span>
                <span>{{ trans('strings.view_portal_list_header_6') }}</span>
            @else
                <span>{{ trans('strings.view_portal_list_header_1') }}</span>
                <span>{{ trans('strings.view_portal_list_header_2') }}</span>
                <span>{{ trans('strings.view_portal_list_header_3') }}</span>
                <span>{{ trans('strings.view_portal_list_header_4') }}</span>
                <span>{{ trans('strings.view_portal_list_header_5') }}</span>
                <span>{{ trans('strings.view_portal_list_header_6') }}</span>
            @endif
        @endforeach
    </h1>

    @php app()->setLocale($initiallang_rotatry_keywords) @endphp
</div>