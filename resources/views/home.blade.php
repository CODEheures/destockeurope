@extends('layouts.app')

@section('content')
<div class="ui container">
    @if(auth()->check())
    <h2 class="ui header"><img src="{{ asset('/images/matt.jpg') }}" alt="" class="ui circular image">{{ auth()->user()->name }}</h2>
    @endif
</div>
@endsection
