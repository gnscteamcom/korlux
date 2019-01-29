@extends('layouts.admin-side.default')


@section('title')
@parent
Tambah Harga
@stop


@section('content')

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Tambah Harga</h1>
    </div>
</div>


<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                Tambah Harga Baru
            </div>
            <div class="panel-body">

                <form method="post" action="{{ URL::to('insertprice') }}">
                    {!! csrf_field() !!}

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="nama_produk">Nama Produk</label>
                                        <input type="text" name="nama_produk" id="nama_produk" class="form-control" required />
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="harga_regular">Harga Regular</label>
                                        <input type="number" name="harga_regular" id="harga_regular" class="form-control" placeholder="Harga Regular" min="1" required/>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="harga_reseller">Harga Silver</label>
                                        <input type="number" name="harga_reseller" id="harga_reseller" placeholder="Harga Reseller 1" class="form-control" min="1" required/>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="harga_vip">Harga Gold</label>
                                        <input type="number" name="harga_vip" id="harga_vip" placeholder="Harga VIP" class="form-control" min="1" required/>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="harga_vvip">Harga Platinum</label>
                                        <input type="number" name="harga_vvip" id="harga_vvip" placeholder="Harga VVIP" class="form-control" min="1" required/>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="harga_sale">Harga Sale</label>
                                        <input type="number" name="harga_sale" id="harga_sale" placeholder="Harga Sale" class="form-control" value="0" min="0" required/>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="mulai_berlaku">Mulai Berlaku</label>
                                        <input type="text" name="mulai_berlaku" id="mulai_berlaku" class="form-control" placeholder="Mulai Berlaku" required>
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
                                @if($errors->has('nama_produk'))
                                {{ $errors->first('nama_produk') }}
                                @endif
                                @if($errors->has('harga_regular'))
                                {{ $errors->first('harga_regular') }}
                                @endif
                                @if($errors->has('harga_reseller'))
                                {{ $errors->first('harga_reseller') }}
                                @endif
                                @if($errors->has('harga_vip'))
                                {{ $errors->first('harga_vip') }}
                                @endif
                                @if($errors->has('harga_sale'))
                                {{ $errors->first('harga_sale') }}
                                @endif
                                @if($errors->has('mulai_berlaku'))
                                {{ $errors->first('mulai_berlaku') }}
                                @endif
                            </div>
                            @endif
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

@section('script')
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<script type="text/javascript">
var token = $('input[name=_token]').val();
$(function () {
    $("#mulai_berlaku").datepicker({
        altField: "#valid_date",
        altFormat: "yy-mm-dd"
    });
    $("#datepicker").datepicker("option", "dateFormat", "d MM yy");
});

$(function () {
    var availableTags = [
            @foreach($products as $product)
    "{{ $product->id }} | {{ $product->product_name }}, stok: {{ $product->qty }}",
            @endforeach
    ];
    $("#nama_produk").autocomplete({
        source: availableTags
    });
});
</script>

@stop