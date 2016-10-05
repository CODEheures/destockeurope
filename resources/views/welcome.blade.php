@extends('layouts.app')

@section('content')
    <div class="ui container">
        @if(auth()->check())
            <p>Check</p>
        @endif
    </div>
@endsection
