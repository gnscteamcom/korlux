@extends('layouts.admin-side.default')


@section('title')
@parent
    Tambah Harga Grosir
@stop


@section('content')
    
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Tambah Harga Grosir</h1>
        </div>
    </div>
        
        
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-info">
                <div class="panel-heading">
                    Silahkan input harga grosir yang baru
                </div>
                <div class="panel-body">
                    
                    <form method="post" action="{{ URL::to('addwholesaleprice') }}">
                        {!! csrf_field() !!}
                        <div class="col-lg-3">
                            <div class="row">
                                <div class="col-lg-12">
                                    <h3>Regular</h3>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <h3>Level 1</h3>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="qty_minimum">Qty Minimum</label>
                                        <input type="number" name="qty_minimum_1" id="qty_minimum_1" class="form-control" placeholder="Qty Minimum" value="{{ old('qty_minimum_1') }}" min="1" required="required"/>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="harga_satuan_1">Harga Satuan</label>
                                        <input type="number" name="harga_satuan_1" id="harga_satuan_1" class="form-control" placeholder="Harga Satuan" value="{{ old('harga_satuan_1') }}" min="1" required="required"/>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <h3>Level 2</h3>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="qty_minimum_2">Qty Minimum</label>
                                        <input type="number" name="qty_minimum_2" id="qty_minimum_2" class="form-control" placeholder="Qty Minimum" value="{{ old('qty_minimum_5') }}" min="1" />
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="harga_satuan_2">Harga Satuan</label>
                                        <input type="number" name="harga_satuan_2" id="harga_satuan_2" class="form-control" placeholder="Harga Satuan" value="{{ old('harga_satuan_5') }}" min="1" />
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <h3>Level 3</h3>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="qty_minimum_3">Qty Minimum</label>
                                        <input type="number" name="qty_minimum_3" id="qty_minimum_3" class="form-control" placeholder="Qty Minimum" value="{{ old('qty_minimum_3') }}" min="1" />
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="harga_satuan_3">Harga Satuan</label>
                                        <input type="number" name="harga_satuan_3" id="harga_satuan_3" class="form-control" placeholder="Harga Satuan" value="{{ old('harga_satuan_3') }}" min="1" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="row">
                                <div class="col-lg-12">
                                    <h3>Reseller</h3>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <h3>Level 1</h3>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="qty_minimum">Qty Minimum</label>
                                        <input type="number" name="qty_minimum_4" id="qty_minimum_4" class="form-control" placeholder="Qty Minimum" value="{{ old('qty_minimum_4') }}" min="1"/>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="harga_satuan_4">Harga Satuan</label>
                                        <input type="number" name="harga_satuan_4" id="harga_satuan_4" class="form-control" placeholder="Harga Satuan" value="{{ old('harga_satuan_4') }}" min="1"/>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <h3>Level 2</h3>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="qty_minimum_5">Qty Minimum</label>
                                        <input type="number" name="qty_minimum_5" id="qty_minimum_5" class="form-control" placeholder="Qty Minimum" value="{{ old('qty_minimum_5') }}" min="1" />
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="harga_satuan_5">Harga Satuan</label>
                                        <input type="number" name="harga_satuan_5" id="harga_satuan_5" class="form-control" placeholder="Harga Satuan" value="{{ old('harga_satuan_5') }}" min="1" />
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <h3>Level 3</h3>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="qty_minimum_6">Qty Minimum</label>
                                        <input type="number" name="qty_minimum_6" id="qty_minimum_6" class="form-control" placeholder="Qty Minimum" value="{{ old('qty_minimum_6') }}" min="1" />
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="harga_satuan_6">Harga Satuan</label>
                                        <input type="number" name="harga_satuan_6" id="harga_satuan_6" class="form-control" placeholder="Harga Satuan" value="{{ old('harga_satuan_6') }}" min="1" />
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
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="kode_produk">Kode Produk (pisahkan dengan enter)</label>
                                        <textarea rows="20" name="kode_produk" style="resize:none" id="kode_produk" class="form-control" placeholder="Kode Produk" value="{{ old('kode_produk') }}" required="required"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group text-danger">
                                    @if(!$errors->isEmpty())
                                        {!! $errors->first('qty_minimum_1') . '<br />' !!}
                                        {!! $errors->first('harga_satuan_1') . '<br />' !!}
                                        {!! $errors->first('qty_minimum_2') . '<br />' !!}
                                        {!! $errors->first('harga_satuan_2') . '<br />' !!}
                                        {!! $errors->first('qty_minimum_3') . '<br />' !!}
                                        {!! $errors->first('harga_satuan_3') . '<br />' !!}
                                        {!! $errors->first('qty_minimum_4') . '<br />' !!}
                                        {!! $errors->first('harga_satuan_4') . '<br />' !!}
                                        {!! $errors->first('qty_minimum_5') . '<br />' !!}
                                        {!! $errors->first('harga_satuan_5') . '<br />' !!}
                                        {!! $errors->first('qty_minimum_6') . '<br />' !!}
                                        {!! $errors->first('harga_satuan_6') . '<br />' !!}
                                        {!! $errors->first('kode_produk') !!}
                                    @endif
                                    @if(Session::has('err'))
                                        {{ Session::get('err') }}
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <input type="submit" value="Tambah" class="btn btn-default btn-success btn-block"/>
                            </div>
                        </div>
                        
                    </form>
                    
                    
                </div>
            </div>
        </div>
    </div>
</div>
@stop