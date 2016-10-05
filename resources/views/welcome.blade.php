@extends('layouts.app')

@section('content')
    <div class="ui center grid">
        @if(auth()->check())
            <p>Check</p>
        @endif
    </div>
@endsection
