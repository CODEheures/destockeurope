@if(auth()->check())
    <a class="logo item" href="{{ route('user.account') }}">
        <img src="{{ asset('/images/matt.jpg') }}" alt="" class="ui tini circular image">
        <p>{{ auth()->user()->name }} @if(auth()->user()->role != 'user')({{ auth()->user()->role }})@endif</p>
    </a>
@else
    <a class="logo item" href="{{ route('home') }}">
        <img class="ui tiny image" src="{{ asset('images/logo.png') }}">
        <p>{{ config('app.name', 'Laravel') }}</p>
    </a>
@endif
