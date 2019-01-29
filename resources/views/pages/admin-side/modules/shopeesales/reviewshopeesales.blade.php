@extends('layouts.admin-side.default')


@section('title')
@parent
    Shopee Sales
@stop


@section('content')
    
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Penjualan Shopee Step 2</h1>
        </div>
    </div>
        
        
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-info">
                <div class="panel-heading">
                    Periksa Penjualan Anda
                </div>
                <div class="panel-body">
                    
                    <form method="post" action="{{ url('shopeesales/save') }}">
                        {!! csrf_field() !!}
                        
                        <input type="hidden" name="orderheader_id" value="{{ $order->id }}" />
                        <input type="hidden" name="shopeesales_id" value="{{ $shopeesales_id }}" />
                        
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
                                    <div class="col-lg-6">
                                        <label for="nomor_invoice">Nomor Invoice</label>
                                    </div>
                                    <div class="col-lg-6">
                                        @if($order)
                                        {!! $order->invoicenumber !!}
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <div class="col-lg-6">
                                        <label>Invoice Shopee</label>
                                    </div>
                                    <div class="col-lg-6">
                                        @if($order)
                                        {!! $order->shopeesales->shopee_invoice_number !!}
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if(strlen($order->shopeesales->shopee_resi) > 0)
                        <div class="row">
                            <div class="col-lg-6 col-lg-offset-6">
                                <div class="form-group">
                                    <div class="col-lg-6">
                                        <label>Kode Resi Shopee</label>
                                    </div>
                                    <div class="col-lg-6">
                                        @if($order)
                                        {!! $order->shopeesales->shopee_resi !!}
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <div class="col-lg-6">
                                        <label>Nama </label>
                                    </div>
                                    <div class="col-lg-6">
                                        @if($order)
                                            @if($order->customeraddress)
                                            {!! $order->customeraddress->first_name . ' ' . $order->customeraddress->last_name !!}
                                            @else
                                            {!! $order->user->usersetting->first_name . ' ' . $order->user->usersetting->last_name !!}
                                            @endif
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
                                        @if($order)
                                            @if($order->customeraddress)
                                            {!! $order->customeraddress->hp !!}
                                            @else
                                            {!! $order->user->usersetting->hp !!}
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
                                        <label for="alamat">Alamat</label>
                                    </div>
                                    <div class="col-lg-6">
                                        @if($order)
                                            @if($order->customeraddress)
                                            {!! $order->customeraddress->alamat !!}
                                            @else
                                            {!! $order->user->usersetting->alamat !!}
                                            @endif
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
                                        @if($order)
                                            {!! $order->shipment_method !!}
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if($order->dropship)
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <div class="col-lg-6">
                                        <label for="dikirim_oleh">Dikirim Oleh</label>
                                    </div>
                                    <div class="col-lg-6">
                                        {!! $order->dropship->name !!}
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <div class="col-lg-6">
                                        <label for="nomor_hp_pengirim">Nomor HP Pengirim</label>
                                    </div>
                                    <div class="col-lg-6">
                                        {!! $order->dropship->hp !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <div class="col-lg-6">
                                        <label for="note">Note / Catatan</label>
                                    </div>
                                    <div class="col-lg-6">
                                        @if($order)
                                        {!! $order->note !!}
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
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <a class="btn btn-warning btn-success col-lg-12" href="{{ url('shopeesales/add') }}"/>Kembali dan Ulang</a>
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