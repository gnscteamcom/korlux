@extends('layouts.admin-side.default')


@section('title')
@parent
    Shopee Sales
@stop


@section('content')
    
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Penjualan Shopee</h1>
        </div>
    </div>
        
        
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-info">
                <div class="panel-heading">
                    Input Data Anda
                </div>
                <div class="panel-body">
                    
                    <form method="post" action="{{ url('shopeesales/continue') }}">
                        {!! csrf_field(); !!}
                        

                        @if(session('msg'))
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group text-success">
                                    <div class="alert alert-success alert-dismissible" role="alert">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        {!! '<b>' . session('msg') . '</b>' !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                        @if(session('err'))
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group text-danger">
                                    <div class="alert alert-danger alert-dismissible" role="alert">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        {!! '<b>' . session('err') . '</b>' !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                        
                        @if($errors->any())
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group text-danger">
                                    <div class="alert alert-danger alert-dismissible" role="alert">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                        
                        <input type="hidden" name="orderheader_id" value="{{ $sale->orderheader_id }}" />
                        <input type="hidden" name="shopeesales_id" value="{{ $sale->id }}" />
                        <input type="hidden" name="kecamatan_text" id="kecamatan_text" value="" />
                        <input type="hidden" id="biaya_kirim_text" name="biaya_kirim_text" value=""/>
                        <input type="hidden" id="biaya_kirim" name="biaya_kirim" value="0"/>
                        <input type="hidden" value="" name="ship_method_text" id="ship_method_text" />
                        <input type="hidden" value="0" name="kecamatan" id="kecamatan" />
                        <input type="hidden" value="0" name="ship_method" id="kecamatan" />
                            
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="nomor_order">Nomor Order</label>
                                    <input type="text" class="form-control" name="nomor_order" value="{{ $sale->orderheader->invoicenumber }}" readonly/>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="invoice_shopee">Nomor Invoice Shopee</label>
                                    <input type="text" class="form-control" required="required" name="invoice_shopee" value="{{ $sale->shopee_invoice_number }}" readonly/>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="resi_shopee">Kode Resi Shopee</label>
                                    <input type="text" class="form-control" required="required" name="resi_shopee" value="{{ $sale->shopee_resi }}" readonly/>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="username">Username Shopee</label>
                                    <input type="text" class="form-control" required="required" name="username" value="{{ $sale->username }}" readonly/>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="nama_depan">Nama Depan</label>
                                    <input type="text" class="form-control" required="required" name="nama_depan" value="{{ $sale->customeraddress->first_name }}" readonly/>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="hp">HP</label>
                                    <input type="text" class="form-control" name="hp" value="{{ $sale->customeraddress->hp }}" readonly/>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="alamat">Alamat</label>
                                    <textarea class="form-control" rows="5" style="resize:none" id="alamat" name="alamat" required="required" readonly>{{ $sale->customeraddress->alamat }}</textarea>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="opsi">Opsi Pengiriman Customer</label>
                                    <input type="text" class="form-control" name="opsi" required="required" value="{{ $sale->shipping_option }}" readonly/>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="kirim_sebelum">Kirim Sebelum</label>
                                    <input type="text" class="form-control" name="kirim_sebelum" required="required" value="{{ $sale->send_before }}" readonly/>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="note">Note / Catatan</label>
                                    <input type="text" class="form-control" name="note" />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="note">Daftar Produk</label>
                                    <textarea class="form-control" rows="5" style="resize:none" name="daftar_produk" readonly>{{ $sale->product_list }}</textarea>
                                </div>
                            </div>
                            @if($sale->orderheader->dropship)
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="dikirim">Dikirim oleh</label>
                                    <textarea class="form-control" rows="5" style="resize:none" name="dikirim" required="required" readonly>{!! $sale->orderheader->dropship->name . '&#13' . $sale->orderheader->dropship->hp !!}</textarea>
                                </div>
                            </div>
                            @endif
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
                            <input type="hidden" value="1" name="gunakan_stok" />
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
        
    </script>
    
    <link rel="stylesheet" href="{{ URL::asset('ext/css/front-end/r-style.css') }}">

    <script type="text/javascript" src="{{ URL::asset('ext/js/custom/manualsales.js') }}"></script>

@stop