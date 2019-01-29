@extends('layouts.admin-side.default')


@section('title')
@parent
    Tambah Banner
@stop


@section('content')
    
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Tambah Banner</h1>
        </div>
    </div>
        
        
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-info">
                <div class="panel-heading">
                    Silahkan masukkan banner
                </div>
                <div class="panel-body">
                    
                    <form method="post" action="{{ URL::to('uploadbanner') }}" enctype="multipart/form-data">
                        {!! csrf_field() !!}
                        
                        <div class="row">
                            <div class="col-lg-6">
                                @if(Session::has('err'))
                                    <div class="form-group text-danger">
                                        {{ Session::get('err') }}
                                    </div>
                                @endif
                                @if(!$errors->isEmpty())
                                    <div class="form-group text-danger">
                                        @if($errors->has('banner'))
                                            {{ $errors->first('banner') }}
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="banner">Banner ( Ukuran yang cocok adalah 1300 x 440 )</label>
                                    <input type="file" name="banner" id="banner" required="required" accept="image/*"/>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="tautan">Tautan</label>
                                    <input type="text" name="tautan" id="tautan" class="form-control" autofocus="autofocus" placeholder="tautan" required="required"/>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <input type="submit" value="Tambah" class="btn btn-default btn-success btn-block"/>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@include('includes.admin-side.validation')
@stop