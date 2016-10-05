<div class="logs">
    @if (Auth::guest())
        <a class="{{ $routeName == 'login' ? 'active' : '' }} item" href="{{ route('login') }}">{{ trans('strings.menu_login') }}</a>
        <a class="{{ $routeName == 'register' ? 'active' : '' }} item" href="{{ route('register') }}">{{ trans('strings.menu_register') }}</a>
    @else
        <a class="{{ $routeName == 'user.account' ? 'active' : '' }} item" href="{{ route('user.account') }}" > {{ trans('strings.menu_account') }} </a>
        <a class="item" href="{{ url('/logout') }}"
           onclick="event.preventDefault();
           document.getElementById('logout-form2').submit();">
           {{ trans('strings.menu_logout') }}
        </a>
        <form id="logout-form2" action="{{ url('/logout') }}" method="POST" style="display: none;">
            {{ csrf_field() }}
        </form>
    @endif

</div>
