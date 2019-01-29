@extends('layouts.admin-side.default')


@section('title')
@parent
Tambah Produk Set
@stop


@section('content')

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Tambah Produk Set Baru</h1>
    </div>
</div>


<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                Silahkan masukkan produk set baru
            </div>
            <div class="panel-body">

                <form method="post" action="{{ URL::to('insertproductset') }}">
                    {!! csrf_field() !!}

                    <div class="row">
                        <div class="col-md-12">
                            <label>Produk: </label>
                            <select name="products" id="products" class="form-control select2">
                                <option value="" disabled selected> -- Select Product -- </option>
                                @foreach($products as $product)
                                <option value="{{ $product->id }}"> {{ $product->product_name }} </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row margin-top-10">
                        <div class="col-md-9 margin-bottom-20">
                            <input type="button" name="add" value="Tambah produk" class="btn btn-default btn-success col-md-12" onclick="addRow()">    
                        </div>
                        <div class="col-md-3 margin-bottom-20">
                            <input type="button" name="remove" value="Hapus produk terakhir" class="btn btn-default btn-success col-md-12" onclick="removeLastRow()">    
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group margin-top-20">
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered table-hover">
                                        <thead>
                                            <tr class="info">
                                                <th class="col-sm-2">Barcode</th>
                                                <th class="col-sm-2">Barang</th>
                                                <th class="col-sm-2">Harga</th>
                                                <th class="col-sm-2">Stok Tersedia</th>
                                            </tr>
                                        </thead>

                                        <tbody id="product_list">
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    
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
                                            @if($errors->has('product_id'))
                                            {{ $errors->first('product_id') }}
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
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

@stop


@section('script')

    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
    <link rel="stylesheet" href="{{ URL::asset('ext/css/front-end/r-style.css') }}">
    <script type="text/javascript" src="{{ URL::asset('ext/js/custom/productset.js?2') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('ext/js/custom/listsubkategori.js') }}"></script>

    <script type="text/javascript">
        $('.select2').select2();
    </script>
@stop