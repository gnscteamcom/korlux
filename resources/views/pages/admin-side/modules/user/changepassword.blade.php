@extends('layouts.admin-side.default')


@section('title')
@parent
    Change Password
@stop


@section('content')
    
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Ubah Kata Sandi</h1>
        </div>
    </div>
        
        
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-info">
                <div class="panel-heading">
                    Ubah kata sandi anda secara berkala
                </div>
                <div class="panel-body">
                    
                    
                    <form method="post" action="{{ URL::to('changepassword') }}">
                        {!! csrf_field() !!}
                        
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="oldpassword">Kata sandi lama</label>
                                    <input type="password" name="oldpassword" id="oldpassword" class="form-control" autofocus="autofocus" placeholder="Old Password" />
                                    <span class="text-danger">
                                    {{ $errors->first('oldpassword') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="newpassword">Kata sandi baru</label>
                                    <input type="password" name="newpassword" id="newpassword" class="form-control" placeholder="New Password" />
                                    <span class="text-danger">
                                    {{ $errors->first('newpassword') }}
                                    </span>
                                </div>
                            </div>
                       </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="confpassword">Ulangi kata sandi baru</label>
                                    <input type="password" name="confpassword" id="confpassword" class="form-control" placeholder="Confirm New Password" />
                                    <span class="text-danger">
                                    {{ $errors->first('confpassword') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group text-danger">
                                    @if(Session::has('err'))
                                        {{ Session::get('err') }}
                                    @endif
                                </div>

                                @if(Session::has('msg'))
                                    <div class="form-group text-success">
                                        {{ Session::get('msg') }}
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <input type="submit" value="Perbarui" class="btn btn-default btn-success btn-block" />
                            </div>
                        </div>
                        
                    </form>
                    
                    
                </div>
            </div>
        </div>
    </div>
</div>
@stop