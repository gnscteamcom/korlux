@extends('layouts.admin-side.default')


@section('title')
@parent
    Tambah Kategori
@stop


@section('content')
    
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Tambah Kategori</h1>
        </div>
    </div>
        
        
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-info">
                <div class="panel-heading">
                    Input Data Anda
                </div>
                <div class="panel-body">
                    
                    <form method="post" action="{{ URL::to('addcategory') }}">
                        
                        {!! csrf_field() !!}

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="kategori">Kategori</label>
                                    <input type="text" name="kategori" id="kategori" class="form-control" autofocus="autofocus" placeholder="Kategori" maxlength="32" required="required"/>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="posisi">Posisi</label>
                                    <input type="number" name="posisi" id="posisi" class="form-control" autofocus="autofocus" placeholder="Posisi" min="1" required="required"/>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group text-danger">
                                    @if(!$errors->isEmpty())
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="form-group text-danger">
                                                <div class="alert alert-danger alert-dismissible" role="alert">
                                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                    @if($errors->has('kategori'))
                                                        {{ $errors->first('kategori') }}
                                                    @endif
                                                    @if($errors->has('posisi'))
                                                        {{ $errors->first('posisi') }}
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                    @if(Session::has('err'))
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="form-group text-danger">
                                                <div class="alert alert-danger alert-dismissible" role="alert">
                                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                        {{ Session::get('err') }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <input type="submit" value="Tambah" class="btn btn-default btn-success btn-block" />
                            </div>
                        </div>
                        
                    </form>
                    
                </div>
            </div>
        </div>
    </div>
</div>
@stop