@extends('layouts.admin-side.default')


@section('title')
@parent
Ubah Data Produk Set
@stop


@section('content')

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Ubah Data Produk Set</h1>
    </div>
</div>


<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                Silahkan Ubah Data
            </div>
            <div class="panel-body">

                <form method="post" action="{{ URL::to('updateproductset') }}">
                    {!! csrf_field() !!}

                    <input type="hidden" name="productset_id" id="product_id" value="{{ $product->id }}" />
                    <div class="row">
                        <div class="col-md-12">
                            <label for="tags">Produk: </label>
                            <input id="tags" type="text" class="form-control" name="tags" />
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
                                            @foreach($current_products as $current_product)
                                            <tr>
                                                <input type="hidden" name="product_id[]" value="{{ $current_product->id }}" />
                                                <td>{{ $current_product->barcode }}</td>
                                                <td>{{ $current_product->product_name }}</td>
                                                <td>{{ 'Rp. ' . number_format($current_product->regular_price, 2, ',', '.') }}</td>
                                                <td>{{ $current_product->qty }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="current_brand">Merk Saat Ini</label>
                                        <input type="text" name="current_brand" id="current_brand" class="form-control" readonly="readonly" value="{{ $product->brand->brand }}" />
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="brand">Merk Baru</label>
                                        <select class="form-control" id="brand" name="brand">
                                            <option value="">-- Silahkan Pilih --</option>
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
                                        <label for="current_category">Kategori Saat Ini</label>
                                        @if($product->category_id == 0)
                                        <input type="text" name="current_category" id="current_category" class="form-control" readonly="readonly"/>
                                        @else
                                        <input type="text" name="current_category" id="current_category" class="form-control" readonly="readonly" value="{{ $product->category->category }}" />
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="kategori">Kategori Baru</label>
                                        <select class="form-control" id="kategori" name="kategori">
                                            <option value="">-- Silahkan Pilih --</option>
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
                                        <label for="current_category">Sub Kategori Saat Ini</label>
                                        @if($product->subcategory_id == 0 || $product->subcategory == null)
                                        <input type="text" name="current_subcategory" id="current_subcategory" class="form-control" readonly="readonly" />
                                        @else
                                        <input type="text" name="current_subcategory" id="current_subcategory" class="form-control" readonly="readonly" value="{{ $product->subcategory->subcategory }}" />
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="kategori">Sub Kategori Baru</label>
                                        <select class="form-control" id="subkategori" name="subkategori">
                                            <option value=""> -- Silahkan pilih kategori dahulu -- </option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="qty">Qty</label>
                                        <input type="number" name="qty" id="qty" class="form-control" value="{{ $product->qty }}" readonly="readonly"/>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="barcode">Barcode</label>
                                        <input type="text" name="barcode" id="barcode" class="form-control" placeholder="Barcode" value="{{ $product->barcode }}"/>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <!--                                    <div class="col-lg-6">
                                                                        <div class="form-group">
                                                                            <label for="current_price">Harga Saat Ini</label>
                                                                            <input type="text" name="current_price" id="current_price" class="form-control" placeholder="Harga Saat ini" value="{{ 'Rp. ' . number_format(100, 2, ',', '.') }}" readonly="readonly"/>
                                                                        </div>
                                                                    </div>-->
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="product_code">Kode Produk</label>
                                        <input type="text" name="product_code" id="product_code" class="form-control" placeholder="Kode Produk" value="{{ $product->product_code }}"/>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="desc">Deskripsi</label>
                                        <textarea name="desc" id="desc" rows="4" style="resize:none" class="form-control" placeholder="Deskripsi">{!! $product->product_desc !!}</textarea>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="product_name">Nama Produk</label>
                                        <input type="text" name="product_name" id="product_name" class="form-control" placeholder="Nama Produk" value="{{ $product->product_name }}"/>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="weight">Berat</label>
                                        <input type="number" name="weight" id="weight" class="form-control" placeholder="Berat dalam gram" value="{{ $product->weight }}" min="1" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            @if(Session::has('err'))
                            <div class="form-group text-danger">
                                {{ Session::get('err') }}
                            </div>
                            @endif
                            @if(!$errors->isEmpty())
                            <div class="form-group text-danger">
                                @if($errors->has('brand'))
                                {{ $errors->first('brand') }}
                                @endif
                                @if($errors->has('barcode'))
                                {{ $errors->first('barcode') }}
                                @endif
                                @if($errors->has('product_code'))
                                {{ $errors->first('product_code') }}
                                @endif
                                @if($errors->has('product_name'))
                                {{ $errors->first('product_name') }}
                                @endif
                                @if($errors->has('desc'))
                                {{ $errors->first('desc') }}
                                @endif
                                @if($errors->has('weight'))
                                {{ $errors->first('weight') }}
                                @endif
                            </div>
                            @endif
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-3"></div>
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


@section('script')
<script src="http://code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<script type="text/javascript">
    var token = $('input[name=_token]').val();

    $(function () {
        var availableTags = [
                @foreach($products as $product)
        "{{ $product->id }} | {{ $product->product_name }}, stok: {{ $product->qty }}",
                @endforeach
        ];
        $("#tags").autocomplete({
            source: availableTags
        });
    });
</script>

<link rel="stylesheet" href="{{ URL::asset('ext/css/front-end/r-style.css') }}">
<script type="text/javascript" src="{{ URL::asset('ext/js/custom/productset.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('ext/js/custom/listsubkategori.js') }}"></script>
<link rel="stylesheet" href="http://code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<script src="http://code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
@stop