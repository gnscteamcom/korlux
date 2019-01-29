@extends('layouts.admin-side.default')


@section('title')
@parent
    Manual Sales
@stop


@section('content')
    
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Penjualan {{ $cart_data->options->link_pembayaran ? 'Chat' : 'Marketplace' }} Step 2</h1>
        </div>
    </div>
        
        
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-info">
                <div class="panel-heading">
                    Periksa Penjualan Anda
                </div>
                <div class="panel-body">
                    
                    <form method="post" action="{{ URL::to('submitmanualsales') }}">
                        {!! csrf_field() !!}
                        
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
                                <div class="form-group">
                                    <div class="col-lg-12">
                                        @if($cart_data)
                                            @if($cart_data->options->link_pembayaran)
                                            <label for="link_pembayaran" style="font-size: 20px; color: red;">Otomatis Membuat Link Pembayaran</label>
                                            @else
                                            <label for="link_pembayaran" style="font-size: 20px; color: red;">Tidak Membuat Link Pembayaran</label>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <div class="col-lg-6">
                                        <label for="marketing">Nama Marketing</label>
                                    </div>
                                    <div class="col-lg-6">
                                        @if($cart_data)
                                        {!! $cart_data->options->marketing !!}
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <div class="col-lg-6">
                                        <label for="inisial">Inisial Marketing</label>
                                    </div>
                                    <div class="col-lg-6">
                                        @if($cart_data)
                                        {!! $cart_data->options->inisial !!}
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <div class="col-lg-6">
                                        <label for="nama_depan">Nama Depan</label>
                                    </div>
                                    <div class="col-lg-6">
                                        @if($cart_data)
                                        {!! $cart_data->options->nama_depan !!}
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <div class="col-lg-6">
                                        <label for="hp">HP</label>
                                    </div>
                                    <div class="col-lg-6">
                                        @if($cart_data)
                                        {!! $cart_data->options->hp !!}
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <div class="col-lg-6">
                                        <label for="kecamatan">Kecamatan</label>
                                    </div>
                                    <div class="col-lg-6">
                                        @if($cart_data)
                                        {!! $cart_data->options->kecamatan_text !!}
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <div class="col-lg-6">
                                        <label for="metode">Metode</label>
                                    </div>
                                    <div class="col-lg-6">
                                        @if($cart_data)
                                        {!! $cart_data->options->ship_method_text !!}
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <div class="col-lg-6">
                                        <label for="alamat">Alamat</label>
                                    </div>
                                    <div class="col-lg-6">
                                        @if($cart_data)
                                        {!! $cart_data->options->alamat !!}
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @if(strlen($cart_data->options->marketplace_invoice) > 0)
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <div class="col-lg-6">
                                        <label for="marketplace_invoice">Invoice Marketplace</label>
                                    </div>
                                    <div class="col-lg-6">
                                        @if($cart_data)
                                        {!! $cart_data->options->marketplace_invoice !!}
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <div class="col-lg-6">
                                        <label for="dikirim_oleh">Dikirim Oleh</label>
                                    </div>
                                    <div class="col-lg-6">
                                        @if($cart_data)
                                        {!! $cart_data->options->dikirim_oleh !!}
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <div class="col-lg-6">
                                        <label for="nomor_hp_pengirim">Nomor HP Pengirim</label>
                                    </div>
                                    <div class="col-lg-6">
                                        @if($cart_data)
                                        {!! $cart_data->options->nomor_hp_pengirim !!}
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <div class="col-lg-6">
                                        <label for="note">Note / Catatan</label>
                                    </div>
                                    <div class="col-lg-6">
                                        @if($cart_data)
                                        {!! $cart_data->options->note !!}
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group margin-top-20">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered table-hover">
                                            <thead>
                                                <tr class="">
                                                    <th class="col-sm-2">#</th>
                                                    <th class="col-sm-2">Produk</th>
                                                    <th class="col-sm-1">Quantity</th>
                                                    <th class="col-sm-1">Gunakan Stok</th>
                                                    <th class="col-sm-2">Harga</th>
                                                    <th class="col-sm-2">Subtotal</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php 
                                                $i = 1; 
                                                $total_weight = 0;
                                                ?>
                                                @foreach(Cart::instance('manualsalescart')->content() as $cart)
                                                <?php 
                                                $total_weight += $cart->options->weight * $cart->qty;
                                                ?>
                                                <tr>
                                                    <td>{!! $i++ !!}</td>
                                                    <td>{!! $cart->name !!}</td>
                                                    <td>{!! $cart->qty !!}</td>
                                                    <td>
                                                        @if($cart->options->gunakan_stok == 1)
                                                        Stok Utama
                                                        @else
                                                        Stok Cadangan
                                                        @endif
                                                    </td>
                                                    <td>{!! 'Rp. ' . number_format($cart->price, 2, ',', '.') !!}</td>
                                                    <td>{!! 'Rp. ' . number_format($cart->price * $cart->qty, 2, ',', '.') !!}</td>
                                                </tr>
                                                @endforeach
                                                
                                                <input type="hidden" value="{{ $total_weight }}" name="total_weight"/>
                                                <tr>
                                                    <td colspan="5" class="text-right"><b>Total Belanja</b></td>
                                                    <td>{!! 'Rp. ' . number_format(Cart::instance('manualsalescart')->total(), 2, ',', '.') !!}</td>
                                                </tr>
                                                <tr>
                                                    @if($cart_data)
                                                    <td colspan="5" class="text-right"><b>Biaya Pengiriman </b></td>
                                                    <td>{!! 'Rp. ' . number_format($cart_data->options->biaya_kirim, 2, ',', '.') !!}</td>
                                                    @else
                                                    <td colspan="5" class="text-right"><b>Biaya Pengiriman (NO DATA)</b></td>
                                                    <td></td>
                                                    @endif
                                                </tr>
                                                @if($cart_data->options->nominal_unik)
                                                <tr>
                                                    <td colspan="5" class="text-right"><b>Nominal Unik </b></td>
                                                    @if($cart_data)
                                                    <td>{!! 'Rp. ' . number_format($cart_data->options->nominal_unik, 2, ',', '.') !!}</td>
                                                    @else
                                                    <td></td>
                                                    @endif
                                                </tr>
                                                @endif
                                                <tr>
                                                    <td colspan="5" class="text-right"><b>Grand Total</b></td>
                                                    @if($cart_data)
                                                    <td>{!! 'Rp. ' . number_format($cart_data->options->biaya_kirim + $cart_data->options->nominal_unik + Cart::instance('manualsalescart')->total(), 2, ',', '.') !!}</td>
                                                    @else
                                                    <td></td>
                                                    @endif
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <a class="btn btn-warning btn-success col-lg-12" href="{{ URL::to('manualsales') }}"/>Kembali dan Ulang</a>
                            </div>
                            <div class="col-lg-6">
                                <input type="submit" class="btn btn-default btn-success col-lg-12" value="Konfirmasi Pesanan"/>
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
    <link rel="stylesheet" href="{{ URL::asset('ext/css/front-end/r-style.css') }}">
        
@include('includes.admin-side.validation')
@stop