@extends('layouts.front-end.layouts')

@section('content')

<div class="container-fluid cart-list">
    <div class="row">
        @if(Session::has('err'))
        <div class="col-md-12 text-center">
            <div class="alert alert-danger" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                {{ Session::get('err') }}
            </div>
        </div>
        @endif
        <div class="col-sm-3"></div>
        
        <div class="col-sm-6">
            <form action="{{ url('resetusername') }}" method="post" class="myform">
                {!! csrf_field() !!}

                <h2 class="text-center">Lupa Username</h2>
                
                <div class="row">
                    <div class="col-sm-6 col-sm-offset-3">
                        <div class="form-group">
                            <label class="control-label" for="email">Email *</label>
                            <input name="email" id="email" placeholder="Email" class="form-control" type="email" required="required" maxlength="48" required="required">
                        </div>
                    </div>
                </div>
            
                <button type="submit" class="btn btn-primary col-sm-6 col-sm-offset-3 "><i class="fa fa-search"></i> Cari</button>
            </form>
        </div>
    </div>

</div>


@include('includes.admin-side.validation')
@stop