@extends('layouts.admin-side.default')


@section('title')
@parent
    Manual Sales
@stop


@section('content')

    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Penjualan Lain</h1>
        </div>
    </div>


    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-info">
                <div class="panel-heading">
                    Input Data Anda
                </div>
                <div class="panel-body">

                    <form method="post" action="{{ URL::to('manualsales') }}">
                        {!! csrf_field(); !!}


                        @if(Session::has('msg'))
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group text-danger">
                                    <div class="alert alert-success alert-dismissible" role="alert">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        <span id="copy-text">
                                            {!! '<b>' . Session::get('msg') . '</b>' !!}
                                        </span>
                                        <button type="button" class="btn btn-default btn-info" id="copy-btn"><span aria-hidden="true">Copy Text</span></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                        <div class="row">
                            <div class="col-lg-3"></div>
                            <div class="col-lg-6">
                                <div class="form-group text-danger">
                                    @if(!$errors->isEmpty())
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="form-group text-danger">
                                                <div class="alert alert-danger alert-dismissible" role="alert">
                                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                    @if(!$errors->isEmpty())
                                                        @if($errors->has('biaya_kirim'))
                                                            {{ $errors->first('biaya_kirim') }}
                                                        @endif
                                                        @if($errors->has('qty'))
                                                            {{ $errors->first('qty') }}
                                                        @endif
                                                        @if($errors->has('dikirim_oleh'))
                                                            {{ $errors->first('dikirim_oleh') }}
                                                        @endif
                                                        @if($errors->has('nomor_hp_pengirim'))
                                                            {{ $errors->first('nomor_hp_pengirim') }}
                                                        @endif
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
                                <input type="hidden" name="link_pembayaran" id="link_pembayaran" value="0" />
                                <div class="form-group">
                                    <label for="marketing">Nama Marketing</label>
                                    <input type="text" class="form-control" readonly="readonly" name="marketing" value="{{ $marketing_name }}" />
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="inisial">Inisial Marketing</label>
                                    <input type="text" class="form-control" readonly="readonly" name="inisial" value="{{ $marketing_initial }}" />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div>
                                    <label for="nama_depan">Nama Depan</label>
                                    @if($cart_data != null)
                                    <input type="text" class="form-control" required="required" name="nama_depan" value="{{ $cart_data->options->nama_depan }}" />
                                    @else
                                    <input type="text" class="form-control" required="required" name="nama_depan" />
                                    @endif
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div>
                                    <label for="hp">HP</label>
                                    @if($cart_data != null)
                                    <input type="text" class="form-control" name="hp" value="{{ $cart_data->options->hp }}"/>
                                    @else
                                    <input type="text" class="form-control" name="hp"/>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <label class="control-label" for="kecamatan">Kecamatan *</label>
                                <select name="kecamatan" id="kecamatan" class="form-control kecamatan" required style="width: 100%;">
                                    <option value="" disabled selected> -- Please Choose --</option>
                                    @foreach($kecamatans as $kecamatan)
                                    <option value="{{ $kecamatan['id'] }}"> {{ $kecamatan['kecamatan'] }} </option>
                                    @endforeach
                                </select>
                                <input type="hidden" name="kecamatan_text" id="kecamatan_text" value="" />
                            </div>
                            <div class="col-lg-6">
                                <div>
                                    <label for="ship_method">Metode</label>
                                    <select class="form-control" id="ship_method" name="ship_method" required="required">
                                        <option value="" disabled selected> Silahkan pilih kecamatan terlebih dahulu </option>
                                    </select>
                                </div>
                                <input type="hidden" value="" name="ship_method_text" id="ship_method_text" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div>
                                    <label for="alamat">Alamat</label>
                                    @if($cart_data != null)
                                    <textarea class="form-control" rows="5" style="resize:none" id="alamat" name="alamat" required="required">{{ $cart_data->options->alamat }}</textarea>
                                    @else
                                    <textarea class="form-control" rows="5" style="resize:none" id="alamat" name="alamat" required="required"></textarea>
                                    @endif
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div>
                                    <label for="marketplace_invoice">Nomor Invoice Marketplace</label>
                                    <input type="text" class="form-control" name="marketplace_invoice" value=""/>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div>
                                    <label for="biaya_kirim">Biaya Kirim</label>
                                    <input type="text" class="form-control" name="biaya_kirim" value="0"/>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div>
                                    <label for="note">Note / Catatan</label>
                                    @if($cart_data != null)
                                    <input type="text" class="form-control" name="note" required="required" value="{{ $cart_data->options->note }}"/>
                                    @else
                                    <input type="text" class="form-control" name="note" required="required"/>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div>
                                    <label for="note">Dikirim Oleh</label>
                                    @if($cart_data != null)
                                    <input type="text" class="form-control" name="dikirim_oleh" id="dikirim_oleh" min="5" value="{{ $cart_data->options->dikirim_oleh }}"/>
                                    @else
                                    <input type="text" class="form-control" name="dikirim_oleh" id="dikirim_oleh" min="5" />
                                    @endif
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div>
                                    <label for="note">Nomor HP Pengirim</label>
                                    @if($cart_data != null)
                                    <input type="text" class="form-control" name="nomor_hp_pengirim" id="nomor_hp_pengirim" min="5" value="{{ $cart_data->options->nomor_hp_pengirim }}"/>
                                    @else
                                    <input type="text" class="form-control" name="nomor_hp_pengirim" id="nomor_hp_pengirim" min="5" />
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <h2>
                                    Produk yang akan dibeli
                                </h2>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label for="tags">Produk: </label>
                                <select name="product" id="product" class="product" style="width:100%;">
                                    <option value="" disabled selected>-- Please Choose Product --</option>
                                    @foreach($products as $product)
                                    <option value="{{ $product->id }}">{{ $product->product_name . ' - Stok : ' . $product->qty . ', Cadangan : ' . $product->reserved_qty }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="qty">Kuantitas: </label>
                                <input type="number" id="qty" type="text" class="form-control" name="qty" value="1" onclick="value=''"/>
                            </div>
                            <div class="col-md-3">
                                <label for="gunakan_stok">Gunakan Stok: </label>
                                <select name="gunakan_stok" id="gunakan_stok" class="product" style="width:100%;">
                                    <option value="1" selected> Stok Utama </option>
                                    <option value="2"> Stok Cadangan </option>
                                </select>
                            </div>
                        </div>
                        <div class="row margin-top-10">
                            <div class="col-md-9 margin-bottom-20">
                                <input type="button" name="add" value="Tambah produk" class="btn btn-default btn-success col-md-12" onclick="addRow(); return false;">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group margin-top-20">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered table-hover">
                                            <thead>
                                                <tr class="info">
                                                    <th>Barang</th>
                                                    <th class="text-center">Harga</th>
                                                    <th>Qty</th>
                                                    <th class="text-center"> </th>
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
                            <div class="col-lg-3"></div>
                            <div class="col-lg-6">
                                <input type="submit" class="btn btn-default btn-success pull-right col-md-offset-5 button-sale" value="Lanjut dan Periksa"/>
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

    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>

    <script type="text/javascript">

        var token = $('input[name=_token]').val();

        $('.kecamatan').select2();
        $('.product').select2();

        $('#kecamatan').change(function(){
            $('#kecamatan_text').val($('#kecamatan option:selected').text());
        });

        $('#copy-btn').click(function(){
            var range = document.createRange();
            range.selectNode(document.getElementById('copy-text'));
            window.getSelection().addRange(range);
            document.execCommand("Copy");
        });
    </script>

    <link rel="stylesheet" href="{{ URL::asset('ext/css/front-end/r-style.css') }}">

    <script type="text/javascript" src="{{ URL::asset('ext/js/custom/manualsales.js') }}"></script>

@stop
