@extends('layouts.admin-side.default')


@section('title')
@parent
    Ubah Harga Grosir
@stop


@section('content')
    
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Ubah Harga Grosir</h1>
        </div>
    </div>
        
        
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-info">
                <div class="panel-heading">
                    Ubah Harga Grosir
                </div>
                <div class="panel-body">
                    
                    <form method="post" action="{{ URL::to('updatewholesaleprice') }}">
                        {!! csrf_field() !!}
                        <input type="hidden" value="{{ $discountqty->id }}" name="discountqty_id"/>
                        
                        <div class="col-lg-6">
                            <div class="row">
                                <div class="col-lg-6">
                                    <h3>Level</h3>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="min_qty">Minimum Qty</label>
                                        <input type="text" name="min_qty" id="min_qty" class="form-control" placeholder="MInimum Qty" value="{{ $discountqty->min_qty }}" required="required" min="1"/>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="harga_satuan">Harga Satuan</label>
                                        <input type="text" name="harga_satuan" id="harga_satuan" class="form-control" placeholder="Harga Satuan" value="{{ $discountqty->price }}" required="required" min="1"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="row">
                                <div class="col-lg-6">
                                    <h3>Daftar Produk</h3>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="kode_produk">Kode Produk</label>
                                        @foreach($discountqty->productclasses as $productclass)
                                        @endforeach
                                        <textarea rows="10" name="kode_produk" style="resize:none" id="kode_produk" class="form-control" placeholder="Kode Produk" readonly="readonly">{!! $product !!}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group text-danger">
                                    @if(!$errors->isEmpty())
                                        {!! $errors->first('min_qty') . '<br />' !!}
                                        {!! $errors->first('price') !!}
                                    @endif
                                    @if(Session::has('err'))
                                        {{ Session::get('err') }}
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <input type="submit" value="Ubah" class="btn btn-default btn-success btn-block"/>
                            </div>
                        </div>
                        
                    </form>
                    
                    
                </div>
            </div>
        </div>
    </div>
</div>
@stop