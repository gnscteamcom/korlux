@extends('layouts.admin-side.default')


@section('title')
@parent
    Tambah Foto Produk
@stop


@section('content')

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
    
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Tambah Foto Produk</h1>
        </div>
    </div>
        
        
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-info">
                <div class="panel-heading">
                    Silahkan masukkan foto produk
                </div>
                <div class="panel-body">
                    
                    <form method="post" action="{{ URL::to('uploadproductimage') }}" enctype="multipart/form-data">
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
                                        @if($errors->has('produk'))
                                            {{ $errors->first('produk') }}
                                        @endif
                                        @if($errors->has('foto1'))
                                            {{ $errors->first('foto1') }}
                                        @endif
                                        @if($errors->has('foto2'))
                                            {{ $errors->first('foto2') }}
                                        @endif
                                        @if($errors->has('foto3'))
                                            {{ $errors->first('foto3') }}
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="produk">Produk</label>
                                    <select class="form-control" id="produk" name="produk" required="required">
                                    <option value="">-- Silahkan pilih --</option>
                                        @foreach($products as $product)
                                        <option value="{{ $product->id }}">{{ $product->product_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="foto1">Foto 1</label>
                                    <input type="file" name="foto1" id="foto1" required="required" accept="image/*"/>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="foto2">Foto 2</label>
                                    <input type="file" name="foto2" id="foto2" accept="image/*"/>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="foto3">Foto 3</label>
                                    <input type="file" name="foto3" id="foto3" accept="image/*"/>
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