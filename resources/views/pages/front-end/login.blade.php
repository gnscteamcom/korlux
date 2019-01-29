@extends('layouts.front-end.layouts')


@section('content')

<div class="container-fluid cart-list">
    <div class="row col-md-8 col-md-offset-2">
        @if(Session::has('err'))
        <div class="col-md-12 text-center">
            <div class="alert alert-danger" role="alert" id="err">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                {{ Session::get('err') }}
            </div>
        </div>
        @endif
        @if(Session::has('msg'))
        <div class="col-md-12 text-center">
            <div class="alert alert-success" role="alert" id="msg">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                {{ Session::get('msg') }}
            </div>
        </div>
        @endif
        <div class="col-sm-3"></div>
        <div class="col-sm-6 text-center">
            <form action="{{ URL::to('auth/login') }}" method="post" class="myform">
                {!! csrf_field() !!}

                <h2 class="text-center">Login untuk berbelanja</h2>
                
                <div class="form-group">
                    <input name="username" id="username" placeholder="Username" class="form-control" type="text" autofocus required="required"/>
                    @if($errors->first('username'))
                    <span class="text-danger">
                        {{ $errors->first('username') }}
                    </span>
                    @endif
                </div>
                <div class="form-group">
                    <input name="password" id="password" placeholder="Password" class="form-control" type="password" required="required" />
                    @if($errors->first('password'))
                    <span class="text-danger">
                        {{ $errors->first('password') }}
                    </span>
                    @endif
                </div>

                <button type="submit" class="btn btn-primary col-sm-12"><i class="fa fa-envelope-o"></i> Login</button>
                               
                               @if(false)
                <!--FACEBOOK LOGIN-->
                <script>
                    window.fbAsyncInit = function() {
                      FB.init({
                        appId      : '1020193841450402',
                        xfbml      : true,
                        version    : 'v2.9'
                      });
                      FB.AppEvents.logPageView();
                    };
                </script>
                <div id="fb-root"></div>
                <button type="button" class="btn btn-info col-sm-12 btn-store btn-block" style="margin-top: 10px;" onclick="loginFB();"><i class="fa fa-fw fa-2x fa-facebook"></i>Login using Facebook</button>
                @endif
            </form>
                
                
            <hr class=" col-md-12">

            <div class="col-md-12 text-center">
                <div class="col-md-6">
                    <p class="credit"><a href="{{URL::to('viewregister')}}">Daftar Baru</a></p>
                </div>
                <div class="col-md-6">
                    <p class="credit"><a href="{{URL::to('resetpassword')}}">Lupa Password</a></p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection