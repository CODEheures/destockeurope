<a class="logo item" href="{{ route('home') }}">
    @if(auth()->check())
        <img src="{{ asset('/images/matt.jpg') }}" alt="" class="ui tini circular image">
        <p>{{ auth()->user()->name }}</p>
    @else
        <img class="ui tiny image" src="{{ asset('images/logo.png') }}">
        <p>{{ config('app.name', 'Laravel') }}</p>
    @endif
</a>