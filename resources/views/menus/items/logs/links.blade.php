<div class="logs">
    @if (Auth::guest())
        <a class="item" href="{{ url('/login') }}">Login</a>
        <a class="item" href="{{ url('/register') }}">Register</a>
    @else
        <div class="ui dropdown buttons">
            <span class="text">{{ Auth::user()->name }}</span>
            <a class="item" href="{{ url('/logout') }}"
               onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                Logout
            </a>
        </div>
    @endif
</div>
