@extends('layouts.front-end.layouts')


@section('content')

<div class="container">
    <div class="row page-top">
        <div class="col-sm-6 col-sm-offset-3">
            <h1>Checkout</h1>
        </div>
    </div>
    <div id="checkout" class="col-md-12">
        <div class="box">
            <form method="post" action="{{ URL::to('checkout') }}" id="form-order">
                {!! csrf_field() !!}
                <input type="hidden" value="{{ $total_weight }}" id="total_weight_kg" />
                <input type="hidden" name="kecamatan_text" id="kecamatan_text" value="{{ $user->usersetting->kecamatan }}" />
                <input type="hidden" name="kecamatan" id="kecamatan" value="{{ $user->usersetting->kecamatan_id }}" />
                <input type="hidden" name="userstatus" id="userstatus" value="{{ $user->usersetting->status_id }}" />
                <input type="hidden" name="ship_cost" id="ship_cost" value="0" />

                <ul id = "myTab" class = "nav nav-tabs">
                   <li class = "active col-md-6 col-xs-6 text-center" id="tab-1">
                        <a href = "#step1" data-toggle = "tab">
                            <i class="fa fa-map-marker"></i><br>Alamat & Pengiriman
                        </a>
                   </li>
                   <li class = " col-md-6 col-xs-6 text-center" id="tab-2">
                        <a href = "#step2" data-toggle = "tab">
                            <i class="fa fa-eye"></i><br>Lihat Pesanan
                        </a>
                    </li>
                </ul>
                @if(Session::has('msg'))
                <div class="col-md-12 text-center">
                    <div class="alert alert-success" role="alert" id="msg">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        {{ Session::get('msg') }}
                    </div>
                </div>
                @endif

                @if(Session::has('err'))
                <div class="col-md-12 text-center">
                    <div class="alert alert-danger" role="alert" id="err">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        {{ Session::get('err') }}
                    </div>
                </div>
                @endif

                <div id = "myTabContent" class = "tab-content">
                    <div class = "tab-pane fade in active" id = "step1">
                        <div class="content">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <input type="checkbox" id="kirim_alamat_saya" name="kirim_alamat_lain" style="transform: scale(1.5)" value="1" />
                                        <label for="kirim_alamat_saya">&nbsp; Kirim ke alamat lain</label>
                                    </div>
                                    <div class="form-group">
                                        <label for="alamat_kirim">Alamat Pengiriman</label>
                                        <div id="alamat_kirim_saya">
                                            <div class="row">
                                                <div class="col-lg-10 col-lg-offset-1">
                                                    <input type="text" class="form-control" placeholder="Nama Penerima" value="{{ $user->name }}" readonly/>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-10 col-lg-offset-1">
                                                    <input type="text" class="form-control" placeholder="Alamat Penerima" value="{{ $user->usersetting->alamat }}" readonly/>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-10 col-lg-offset-1">
                                                    <input type="text" class="form-control" id="kecamatan_utama_text" placeholder="Kecamatan Penerima" value="{{ $user->usersetting->kecamatan }}" readonly/>
                                                    <input type="hidden" id="kecamatan_utama_id" value="{{ $user->usersetting->kecamatan_id }}" />
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-10 col-lg-offset-1">
                                                    <input type="text" class="form-control" placeholder="Nomor Telepon Penerima" value="{{ $user->usersetting->hp }}" readonly/>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="alamat_kirim_baru" style="display:none;">
                                            <div class="row">
                                                <div class="col-lg-10 col-lg-offset-1">
                                                    <input type="text" class="form-control" name="nama_penerima" id="nama_penerima" placeholder="Nama Penerima" />
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-10 col-lg-offset-1">
                                                    <input type="text" class="form-control" name="alamat_penerima" id="alamat_penerima" placeholder="Alamat Penerima" />
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-10 col-lg-offset-1">
                                                    <select name="kecamatan_dropdown" id="kecamatan_dropdown" class="form-control kecamatan" style="width: 100%;">
                                                        @if($kecamatan_count > 0)
                                                            <option value="" disabled selected> -- Kecamatan Penerima --</option>
                                                            @foreach($kecamatans as $kecamatan)
                                                            <option value="{{ $kecamatan['id'] }}"> {{ $kecamatan['kecamatan'] }} </option>
                                                            @endforeach
                                                        @else
                                                        <option value="" disabled selected> -- Lost connection, please refresh --</option>
                                                        @endif
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-10 col-lg-offset-1">
                                                    <input type="text" class="form-control" name="nomor_telepon_penerima" id="nomor_telepon_penerima" placeholder="Nomor Telepon Penerima" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <input type="checkbox" id="kirim_dropship" name="kirim_dropship" style="transform: scale(1.5)" value="1"/>
                                        <label for="kirim_dropship">&nbsp; Kirim sebagai dropship</label>
                                    </div>
                                    <div class="form-group">
                                        <label for="dropship">Dropship</label>
                                        <div id="dropship_utama">
                                            <div class="row">
                                                <div class="col-lg-10 col-lg-offset-1">
                                                    <input type="text" class="form-control" placeholder="Nama Pengirim" value="{{ $contact->owner_name }}" readonly/>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-10 col-lg-offset-1">
                                                    <input type="text" class="form-control" placeholder="Nomor HP Pengirim" value="{{ $contact->whatsapp }}" readonly/>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="dropship_baru" style="display:none;">
                                            <div class="row">
                                                <div class="col-lg-10 col-lg-offset-1">
                                                    <input type="text" class="form-control" name="nama_pengirim" id="nama_pengirim" placeholder="Nama Pengirim" />
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-10 col-lg-offset-1">
                                                    <input type="text" class="form-control" name="nomor_hp_pengirim" id="nomor_hp_pengirim" placeholder="Nomor HP Pengirim" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6 col-md-6">
                                    <div class="form-group">
                                        <label for="note">Note</label>
                                        <textarea class="form-control" name="note" style="resize:none" rows="4"></textarea>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-6">
                                    <label for="ship_method" class="">Metode Pengiriman</label>
                                    <select class="form-control" id="ship_method" name="ship_method" required="required">
                                        <option value="" disabled selected> -- Mohon isi kecamatan tujuan pengiriman -- </option>
                                    </select>
                                    <input type="hidden" name="ship_method_text" id="ship_method_text" value="" />
                                </div>
                                <div class="col-sm-6 col-md-6" id="resi_otomatis_div" style="display: none;">
                                    <label for="resi_otomatis" class="">Resi Otomatis ( Kosongkan Jika Tidak Ada )</label>
                                    <input type="text" class="form-control" name="resi_otomatis" id="resi_otomatis" value="" placeholder="Masukkan Nomor Resi Otomatis"/>
                                </div>
                            </div>
                            <div class="box-footer">
                                 <div class="pull-right">
                                     <a class="btn btn-default" href = "#step2" onclick="step1next()" data-toggle = "tab" id="step1-next">Selanjutnya <i class="fa fa-chevron-right"></i></a>
                                 </div>
                            </div>
                        </div>
                    </div>
                    <div class = "tab-pane fade" id = "step2">
                        <div class="content">

                            <div class="margin-top-20">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th colspan="2">Produk</th>
                                            <th>Qty</th>
                                            <th>Harga</th>
                                            <th class="text-right">SubTotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach(Cart::instance('main')->content() as $cart)
                                        <tr>
                                            <td>
                                                @if(strlen($cart->options->image_path) > 0)
                                                <img src="{{ URL::asset($cart->options->image_path) }}" alt="{{ $cart->name }}"></a>
                                                @else
                                                <img src="{{ URL::asset('/storage/default.jpg') }}" alt="{{ $cart->name }}"></a>
                                                @endif
                                            </td>
                                            <td>{{ $cart->name }}</td>
                                            <td>{{ $cart->qty }}</td>
                                            <td>{!! 'Rp. ' . number_format($cart->price, 0, ',', '.') !!}</td>
                                            <td class="text-right">{!! 'Rp. ' . number_format($cart->price * $cart->qty, 0, ',', '.') !!}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="4"></th>
                                            <td colspan="1" class="text-right"><input type="checkbox" name="insurance" id="insurance"></input> &nbsp; <label for="insurance"><strong>Gunakan Asuransi <span title="Dengan menggunakan asuransi, maka kerusakan produk karena cacat bawaan maupun karena pengiriman akan ditanggung oleh pihak Korean Luxury">[?]</span></strong></label></td>
                                        </tr>

                                        <!--FREE SAMPLE-->
                                        <input type="hidden" value="{{ $free_sample }}" name="free_sample" id="free_sample"/>
                                        @if($is_active)
                                        <tr id="free_sample_notif">
                                            <th colspan="3" class="color-green">Free Sample</th>
                                            <td colspan="2">
                                                <div class="input-group col-md-9 col-md-offset-3">
                                                @if($free_sample == 0)
                                                    <strong><span class="color-red pull-right">Ayo belanja lagi sampai {{ 'Rp. ' . number_format($freesample_minimum_nominal, 2, ',', '.') }} dan dapatkan free sampel.<br>
                                                    @if(false)
                                                    @if($is_accumulative)
                                                    (Berlaku kelipatan)
                                                    @endif
                                                    @endif
                                                    </span></strong>
                                                @else
                                                    <strong><span class="color-green pull-right">Kamu berhak mendapatkan free sampel dari kami.
                                                    @if(false)
                                                        setiap pembelanjaan sebesar {{ 'Rp. ' . number_format($freesample_minimum_nominal, 2, ',', '.') }}.
                                                        @if($is_accumulative)
                                                        (Berlaku kelipatan)
                                                        @endif
                                                    @endif
                                                    </span></strong>
                                                @endif
                                                </div>
                                            </td>
                                        </tr>
                                        @endif

                                        <tr>
                                            <td colspan="3">Kode Kupon</td>
                                            <td colspan="2">
                                                <div class="input-group col-md-5 col-md-offset-7">
                                                    <input type="text" name="kode" class="form-control" id="kode_val">
                                                    <span class="input-group-btn">
                                                        <button class="btn btn-default" type="button" id="kode_btn">Cek</button>
                                                    </span>
                                                </div>
                                            </td>
                                        </tr>
                                        @if($user_status == 1 && $config->is_active == 1)
                                        <tr>
                                            <td colspan="3">Pakai Poin <span class="text-muted">(Jumlah poin : <b>{{ $total_point }}</b>)</span></td>
                                            <td colspan="2">
                                                <div class="input-group col-md-5 col-md-offset-7">
                                                    <input type="number" name="poin" class="form-control" id="poin_val" max="{{ $total_point }}" />
                                                    <span class="input-group-btn">
                                                        <button class="btn btn-default" type="button" id="poin_btn">Cek</button>
                                                    </span>
                                                </div>
                                            </td>
                                        </tr>
                                        @endif
                                        <tr>
                                            <th colspan="3"></th>
                                            <th colspan="1" class="text-right">Total Belanja</th>
                                            <th colspan="1" class="text-right">{!! 'Rp. ' . number_format(Cart::instance('main')->total(), 0, ',', '.') !!}</th>
                                            <input type="hidden" id="shop_total" value="{{ Cart::instance('main')->total() }}" />
                                        </tr>
                                        <tr class="color-green">
                                            <th colspan="3"></th>
                                            <th colspan="1" class="text-right">Diskon Kupon</th>
                                            <th colspan="1" id="discountcoupon" class="text-right">{!! 'Rp. ' . number_format(Cart::instance('discountcoupon')->total(), 0, ',', '.') !!}</th>
                                        </tr>
                                        @if($user_status == 1)
                                        <tr class="color-green">
                                            <th colspan="3"></th>
                                            <th colspan="1" class="text-right">Penggunaan Poin</th>
                                            <th colspan="1" id="discountpoint" class="text-right">{!! 'Rp. ' . number_format(Cart::instance('discountpoint')->total(), 0, ',', '.') !!}</th>
                                        </tr>
                                        @endif
                                        <tr>
                                            <th colspan="3"></th>
                                            <th colspan="1" class="text-right">Biaya Pengiriman (<span id="weight"></span> kg)</th>
                                            <th colspan="1" id="shipcost" class="text-right">{!! 'Rp. ' . number_format(Cart::instance('shipcost')->total(), 0, ',', '.') !!}</th>
                                        </tr>
                                        <tr>
                                            <th colspan="3"></th>
                                            <th colspan="1" class="text-right">Biaya Asuransi Pengiriman</th>
                                            <th colspan="1" id="insurancecost" class="text-right">{!! 'Rp. ' . number_format(Cart::instance('insurancecost')->total(), 0, ',', '.') !!}</th>
                                        </tr>
                                        <tr>
                                            <th colspan="3"></th>
                                            <th colspan="1" class="text-right">Biaya Packing</th>
                                            <th colspan="1" id="packing_fee_text" class="text-right">{!! 'Rp. ' . number_format(Cart::instance('packingfee')->total(), 0, ',', '.') !!}</th>
                                        </tr>
                                        <tr>
                                            <th colspan="3"></th>
                                            <th colspan="1" class="text-right">Nominal Identifikasi</th>
                                            <th colspan="1" id="unique" class="text-right">{!! 'Rp. ' . number_format(Cart::instance('unique')->total(), 0, ',', '.') !!}</th>
                                        </tr>
                                        <tr class="color-red">
                                            <th colspan="3"></th>
                                            <th colspan="1" class="text-right">Total Keseluruhan</th>
                                            <th colspan="1" id="grandtotal" class="text-right">{!! 'Rp. ' . number_format(Cart::instance('total')->total(), 0, ',', '.') !!}</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                        <div class="box-footer">
                            <div class="pull-left">
                                <a class="btn btn-default" href = "#step1" onclick="step2before()" data-toggle = "tab" id="step2-before"><i class="fa fa-chevron-left"></i> Sebelumnya</a>
                            </div>
                            <div class="pull-right">
                                <button class="btn btn-primary" type="submit" id="order-btn">Pesan Sekarang <i class="fa fa-chevron-right"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>


@stop



@section('script')
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
<script type="text/javascript" src="{{ asset('ext/js/custom/checkout.js?2') }}"></script>
@stop
