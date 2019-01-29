@extends('layouts.admin-side.default')


@section('title')
@parent
    Tambah Produk
@stop


@section('content')
    
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Tambah Produk Baru</h1>
        </div>
    </div>
        
        
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-info">
                <div class="panel-heading">
                    Silahkan masukkan produk baru
                </div>
                <div class="panel-body">
                    
                    <form method="post" action="{{ URL::to('addproduct') }}">
                        {!! csrf_field() !!}
                        
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="brand">Merk</label>
                                    <select class="form-control" id="brand" name="brand" required="required">
                                    <option value="">-- Silahkan pilih --</option>
                                        @foreach($brands as $brand)
                                        <option value="{{ $brand->id }}">{{ $brand->brand }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="kategori">Kategori</label>
                                    <select class="form-control" id="kategori" name="kategori" required="required">
                                    <option value="">-- Silahkan pilih --</option>
                                        @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->category }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="subkategori">SubKategori</label>
                                    <select class="form-control" id="subkategori" name="subkategori" required="required">
                                        <option value=""> -- Silahkan pilih kategori dahulu -- </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="barcode">Barcode</label>
                                    <input type="text" name="barcode" id="barcode" class="form-control" placeholder="Barcode" value="{{ old('barcode') }}" required="required"/>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="kode_produk">Kode Produk</label>
                                    <input type="text" name="kode_produk" id="kode_produk" class="form-control" placeholder="Kode Produk" value="{{ old('kode_produk') }}" required="required"/>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="nama_produk">Nama Produk</label>
                                    <input type="text" name="nama_produk" id="nama_produk" class="form-control" placeholder="Nama Produk" value="{{ old('nama_produk') }}" required="required"/>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="deskripsi">Deskripsi</label>
                                    <textarea name="deskripsi" id="deskripsi" class="form-control" placeholder="Deskripsi" rows="4" style="resize:none">{{ old('deskripsi') }}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="berat">Berat</label>
                                    <input type="number" name="berat" id="berat" class="form-control" placeholder="Berat dalam gram" min="1" value="{{ old('berat') }}" required="required"/>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                    @if(!$errors->isEmpty())
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="form-group text-danger">
                                                <div class="alert alert-danger alert-dismissible" role="alert">
                                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                    @if($errors->has('brand'))
                                                        {{ $errors->first('brand') }}
                                                    @endif
                                                    @if($errors->has('barcode'))
                                                        {{ $errors->first('barcode') }}
                                                    @endif
                                                    @if($errors->has('kode_produk'))
                                                        {{ $errors->first('kode_produk') }}
                                                    @endif
                                                    @if($errors->has('nama_produk'))
                                                        {{ $errors->first('nama_produk') }}
                                                    @endif
                                                    @if($errors->has('deskripsi'))
                                                        {{ $errors->first('deskripsi') }}
                                                    @endif
                                                    @if($errors->has('berat'))
                                                        {{ $errors->first('berat') }}
                                                    @endif
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
                                <input type="submit" value="Tambah" class="btn btn-default btn-success btn-block"/>
                            </div>
                        </div>
                        
                    </form>
                    
                    
                </div>
            </div>
        </div>
</div>

@include('includes.admin-side.validation')
@stop


@section('script')

    <script>
        
        var token = $('input[name=_token]').val();
        
    </script>
    
    
    <script type="text/javascript" src="{{ URL::asset('ext/js/custom/listsubkategori.js') }}"></script>
@stop