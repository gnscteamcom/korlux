@extends('layouts.login.login')


@section('title')
    @parent
    Login
@stop


@section('content')
    
<div class="container">
    <div class="row">
        <div class="col-md-4 col-md-offset-4">
            <div class="login-panel panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Admin Side</h3>
                </div>

                <div class="panel-body">
                    <form method="post" action="{{ URL::to('auth/login') }}">
                        {!! csrf_field() !!}
                        
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" name="username" id="username" class="form-control" autofocus="autofocus" placeholder="Input your username" value="{{ old('username') }}" />
                            
                            <div class="text-danger">
                            {{ $errors->first('username') }}
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" name="password" id="password" class="form-control" placeholder="Input your password" />
                            
                            <div class="text-danger">
                            {{ $errors->first('password') }}
                            </div>
                        </div>

                        @if(Session::has('err'))
                        <div class="form-group">
                            <div class="text-danger">
                                {{ Session::get('err') }}
                            </div>
                        </div>
                        @endif

                        <input type="submit" class="btn btn-lg btn-success btn-block" value="Login">
                        
                    </form>


                </div>
            </div>
        </div>
    </div>
</div>
    
@stop