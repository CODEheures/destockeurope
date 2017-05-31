@if(session()->has('success'))
    <div class="ui success small message destock-flash">
        <i class="close icon"></i>
        <div class="header">{{ trans('strings.flash_header_success') }}</div>
        <ul class="list">
            {{ session('success') }}
        </ul>
    </div>
@endif

@if(session()->has('info'))
    <div class="ui info small message destock-flash">
        <i class="close icon"></i>
        <div class="header">{{ trans('strings.flash_header_info') }}</div>
        <ul class="list">
            {{ session('info') }}
        </ul>
    </div>
@endif

@if(session()->has('status'))
    <div class="ui info small message destock-flash">
        <i class="close icon"></i>
        <div class="header">{{ trans('strings.flash_header_status') }}</div>
        <ul class="list">
            {{ session('status') }}
        </ul>
    </div>
@endif

@if(session()->has('warning'))
    <div class="ui warning small message destock-flash">
        <i class="close icon"></i>
        <div class="header">{{ trans('strings.flash_header_warning') }}</div>
        <ul class="list">
            {{ session('warning') }}
        </ul>
    </div>
@endif

@if(session()->has('sessionExpired'))
    <div class="ui info small message destock-flash">
        <i class="close icon"></i>
        <div class="header">{{ trans('strings.flash_header_status') }}</div>
        <ul class="list">
            {{ trans('strings.view_all_error_session_expired') }}
        </ul>
    </div>
@endif

@if(count($errors)>0)
    <div class="ui error small message destock-flash">
        <i class="close icon"></i>
        <div class="header">{{ trans('strings.flash_header_error') }}</div>
        <ul class="list">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif