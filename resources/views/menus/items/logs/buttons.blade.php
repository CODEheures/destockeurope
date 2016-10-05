<div class="logs">
    @if (Auth::guest())
        <a class="ui basic button" href="{{ url('/login') }}"><i class="icon sign in"></i>{{ trans('strings.menu_login') }}</a>
        <a class="ui basic button" href="{{ url('/register') }}"><i class="icon add user"></i>{{ trans('strings.menu_register') }}</a>
    @else
        <div class="ui dropdown item">
            {{ Auth::user()->name }}
            <i class="dropdown icon"></i>
            <div class="menu">
                <a class="item" href="{{ route('user.account') }}" > {{ trans('strings.menu_account') }} </a>
                <a class="item" href="{{ url('/logout') }}"
                   onclick="event.preventDefault();
                   document.getElementById('logout-form1').submit();">
                   {{ trans('strings.menu_logout') }}
                </a>
            </div>
        </div>
        <form id="logout-form1" action="{{ url('/logout') }}" method="POST" style="display: none;">
            {{ csrf_field() }}
        </form>
    @endif
</div>
