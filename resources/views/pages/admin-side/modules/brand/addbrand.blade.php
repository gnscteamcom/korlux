@extends('layouts.admin-side.default')


@section('title')
@parent
    Add Brand
@stop


@section('content')
    
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Tambah Merk</h1>
        </div>
    </div>
        
        
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-info">
                <div class="panel-heading">
                    Input Data Anda
                </div>
                <div class="panel-body">
                    
                    <form method="post" action="{{ URL::to('addbrand') }}">
                        
                        {!! csrf_field() !!}

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="merk">Merk</label>
                                    <input type="text" name="merk" id="merk" class="form-control" autofocus="autofocus" placeholder="Merk" maxlength="32" required="required"/>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="inisial">Inisial</label>
                                    <input type="text" name="inisial" id="inisial" class="form-control" autofocus="autofocus" placeholder="Inisial" maxlength="6" required="required"/>
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
                                                    @if($errors->has('merk'))
                                                        {{ $errors->first('merk') }}
                                                    @endif
                                                    @if($errors->has('inisial'))
                                                        {{ $errors->first('inisial') }}
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
@include('includes.admin-side.validation')
@stop