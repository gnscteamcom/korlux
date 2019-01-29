@extends('layouts.admin-side.default')


@section('title')
@parent
    Ubah Data Stok Masuk
@stop


@section('content')
    
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Ubah Data Stok Masuk</h1>
        </div>
    </div>
        
        
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-info">
                <div class="panel-heading">
                    Silahkan Ubah Data
                </div>
                <div class="panel-body">
                    
                    <form method="post" action="{{ URL::to('updatestockin') }}">
                        {!! csrf_field() !!}
                        
                        <input type="hidden" name="stock_in_id" id="stock_in_id" value="{{ $stockin->id }}" />
                        
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="product">Produk</label>
                                    <input type="text" name="product" id="product" class="form-control" readonly value="{{ $stockin->product->product_name }}" />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="stock_in_date">Tanggal Stok Masuk</label>
                                    <input type="text" name="stock_in_date" id="stock_in_date" class="form-control" readonly value="{{ date('l, d F Y H:i:s' , strtotime($stockin->created_at)) }}" />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="remaining_qty">Sisa Stok</label>
                                    <input type="number" name="remaining_qty" id="remaining_qty" class="form-control" value="{{ $stockin->remaining_qty }}" readonly/>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="current_qty">Total Stok Masuk Saat Ini</label>
                                    <input type="number" name="current_qty" id="current_qty" class="form-control" value="{{ $stockin->qty }}" readonly/>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="qty">Stok Masuk Baru</label>
                                    <input type="number" name="qty" id="qty" class="form-control" autofocus min="0" placeholder="Stok Masuk Baru" />
                                </div>
                            </div>
                        </div>
                        @if(Session::has('err'))
                        <div class="form-group text-danger">
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
                        </div>
                        @endif
                        @if(!$errors->isEmpty())
                        <div class="form-group text-danger">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group text-danger">
                                        <div class="alert alert-danger alert-dismissible" role="alert">
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                            @if($errors->has('qty'))
                                                {{ $errors->first('qty') }}
                                            @endif
                                            @if($errors->has('hpp'))
                                                {{ $errors->first('hpp') }}
                                            @endif
                                        </div>
                                    </div>
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