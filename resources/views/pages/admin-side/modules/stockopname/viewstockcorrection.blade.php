@extends('layouts.admin-side.default')


@section('title')
@parent
    Koreksi Stok
@stop


@section('content')
    
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Koreksi Stok</h1>
        </div>
    </div>
        
        
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-info">
                <div class="panel-heading">
                    Input Koreksi Anda
                </div>
                <div class="panel-body">
                    
                    <form method="post" action="{{ URL::to('stockcorrection') }}">
                        
                        {!! csrf_field() !!}
                        @if(Session::has('msg'))
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group text-danger">
                                    <div class="alert alert-success alert-dismissible" role="alert">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                            {!! '<b>' . Session::get('msg') . '</b>' !!}
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
                                            {!! '<b>' . Session::get('err') . '</b>' !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                        
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="stok">Stok</label>
                                    <input type="number" name="stok" class="form-control" required="required" min="0" required="required"/>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="stok_cadangan">Stok Cadangan</label>
                                    <input type="number" name="stok_cadangan" class="form-control" required="required" min="0" required="required"/>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="barcode">Barcode</label>
                                    <input type="text" id="barcode" name="barcode" class="form-control" required="required" required="required"/>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-lg-6">
                                @if(!$errors->isEmpty())
                                <div class="form-group text-danger">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="form-group text-danger">
                                                <div class="alert alert-danger alert-dismissible" role="alert">
                                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                @if($errors->has('barcode'))
                                                    {{ $errors->first('barcode') }}
                                                @endif
                                                @if($errors->has('stok'))
                                                    {{ $errors->first('stok') }}
                                                @endif
                                                @if($errors->has('stok_cadangan'))
                                                    {{ $errors->first('stok_cadangan') }}
                                                @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <input type="submit" value="Ubah" class="btn btn-default btn-success btn-block" />
                            </div>
                        </div>
                        
                    </form>
                    
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@include('includes.admin-side.validation')