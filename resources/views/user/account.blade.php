@extends('layouts.app')

@section('content')
    <div class="ui center grid">
        <h2 class="ui header"><img src="{{ asset('/images/matt.jpg') }}" alt="" class="ui circular image">{{ $user->name }}</h2>


    </div>
@endsection