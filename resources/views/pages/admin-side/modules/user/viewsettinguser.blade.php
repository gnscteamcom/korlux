@extends('layouts.admin-side.default')


@section('title')
@parent
    Ubah Konfigurasi Pengguna
@stop


@section('content')
    
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Ubah Konfigurasi Pengguna</h1>
        </div>
    </div>
        
        
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-info">
                <div class="panel-heading">
                    Silahkan Ubah Data
                </div>
                <div class="panel-body">
                    
                    <form method="post" action="{{ URL::to('updatesettinguser') }}">
                        {!! csrf_field() !!}
                        
                        <input type="hidden" name="user_id" id="user_id" value="{{ $user->id }}" />
                        
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="username">Username</label>
                                    <input type="text" name="username" id="username" class="form-control" readonly value="{{ $user->username }}" />
                                </div>
                            </div>
                        </div>
                        @if(!$errors->isEmpty())
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group text-danger">
                                    @if($errors->has('qty'))
                                        {{ $errors->first('qty') }}
                                    @endif
                                </div>
                           </div>
                        </div>
                        @endif
                        @if(Session::has('err'))
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group text-danger">
                                    {{ Session::get('err') }}
                                </div>
                           </div>
                        </div>
                        @endif
                        <div class="row">
                            <div class="col-lg-6">
                                <input type="submit" class="btn btn-default btn-success btn-block" value="Ubah" />
                            </div>
                        </div>
                        
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@stop