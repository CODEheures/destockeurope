@extends('layouts.app')

@section('content')
<div class="ui container">
    <form class="ui form" method="POST" action="{{ url('/login') }}">
        {{ csrf_field() }}
        <div class="fields">
            <div class="field">
                <label>E-Mail</label>
                <input id="email" type="email" placeholder="Username" value="{{ old('email') }}" required autofocus>
            </div>
            <div class="field">
                <label>Password</label>
                <input id="password" type="password" name="password" required>
            </div>
            <div class="inline field">
                <div class="ui toggle checkbox">
                    <input type="checkbox" tabindex="0" class="hidden" name="remember">
                    <label>Remember me</label>
                </div>
            </div>
            <button type="submit" class="ui primary button">
                Login
            </button>
            <a class="btn btn-link" href="{{ url('/password/reset') }}">
                Forgot Your Password?
            </a>
        </div>
    </form>
    <div class="segment">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Login</div>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ url('/login') }}">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-4 control-label">E-Mail Address</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required autofocus>

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password" class="col-md-4 control-label">Password</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control" name="password" required>

                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="remember"> Remember Me
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-8 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Login
                                </button>
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
            </div>
        </div>
    </div>
</div>
<div class="ui fluid multiple search normal selection dropdown">
    <input type="hidden" name="user">
    <i class="dropdown icon"></i>
    <div class="default text">Select Friend</div>
    <div class="menu">
        <div class="item" data-value="jenny">
            <img class="ui mini avatar image" src="/images/jenny.jpg">
            Jenny Hess
        </div>
        <div class="item" data-value="elliot">
            <img class="ui mini avatar image" src="/images/elliot.jpg">
            Elliot Fu
        </div>
        <div class="item" data-value="stevie">
            <img class="ui mini avatar image" src="/images/stevie.jpg">
            Stevie Feliciano
        </div>
        <div class="item" data-value="christian">
            <img class="ui mini avatar image" src="/images/christian.jpg">
            Christian
        </div>
        <div class="item" data-value="matt">
            <img class="ui mini avatar image" src="/images/matt.jpg">
            Matt
        </div>
        <div class="item" data-value="justen">
            <img class="ui mini avatar image" src="/images/justen.jpg">
            Justen Kitsune
        </div>
    </div>
</div>
@endsection
