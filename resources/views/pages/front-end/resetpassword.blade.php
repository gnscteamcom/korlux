@extends('layouts.front-end.layouts')


@section('content')

<div class="container-fluid cart-list">
    <div class="row">
        @if(Session::has('err'))
        <div class="col-md-12 text-center">
            <div class="alert alert-success" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                {{ Session::get('err') }}
            </div>
        </div>
        @endif
        <div class="col-sm-3"></div>
        <div class="col-sm-6">
            <form action="{{ URL::to('resetpassword') }}" method="post" class="myform">
                {!! csrf_field() !!}

                <h2 class="text-center">Reset Password</h2>
                
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="control-label" for="username">Username *</label>
                            <input name="username" id="username" placeholder="Username" class="form-control" type="text" required="required" autofocus maxlength="48" required="required">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="control-label" for="email">Email *</label>
                            <input name="email" id="email" placeholder="Email" class="form-control" type="email" required="required" maxlength="48" required="required">
                        </div>
                    </div>
                </div>
            
                <button type="submit" class="btn btn-primary col-sm-12 "><i class="fa fa-envelope-o"></i> Reset</button>
                <div class="">&nbsp;</div>

                <div class="col-md-12 text-center">
                    <div class="col-md-6 col-md-offset-3">
                        <p class="credit"><a href="{{ url('resetusername') }}">Lupa Username? Klik disini</a></p>
                    </div>
                </div>
            </form>
        </div>
    </div>

</div>


@include('includes.admin-side.validation')
@stop