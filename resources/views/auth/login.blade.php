@extends('layouts.app')

@section('content')
<div class="ui container">
    <form class="ui form login" method="POST" action="{{ url('/login') }}">
        {{ csrf_field() }}
        <div class="fields">
            <div class="four wide field">
                <label>E-Mail</label>
                <input id="email" type="email" placeholder="Username" value="{{ old('email') }}" required autofocus>
            </div>
            <div class="four wide field">
                <label>Password</label>
                <input id="password" type="password" name="password" required>
            </div>
        </div>
        <div class="fields">
            <div class="inline field">
                <div class="ui toggle checkbox">
                    <input type="checkbox" tabindex="0" class="hidden" name="remember">
                    <label>Remember me</label>
                </div>
            </div>
        </div>
        <div class="fields">
            <div class="field">
                <button type="submit" class="ui primary button">
                    Login
                </button>
                <a class="btn btn-link" href="{{ url('/password/reset') }}">
                    Forgot Your Password?
                </a>
            </div>
        </div>
    </form>
</div>
@endsection
