@extends('layouts.front-end.layouts')


@section('css')
    <link rel="stylesheet" href="{{ URL::asset('ext/css/plugins/datepicker.css') }}">
@stop

@section('content')

<div class="container">
    <div class="row"> 
        <div class="col-md-12">
            @if($order->status_id == 11)
            <div class="row">
                <div class="col-sm-6 col-sm-offset-3">
                    <h2 class="text-center heading">Konfirmasi Pembayaran</h2>
                </div>
            </div>
            @elseif($order->status_id == 16)
            <div class="row">
                <div class="col-sm-6 col-sm-offset-3">
                    <h2 class="text-center heading">ORDER BATAL</h2>
                    <p>Silahkan hubungi CS kami jika sudah melakukan transaksi.</p>
                </div>
            </div>
            @else
            <div class="row">
                <div class="col-sm-6 col-sm-offset-3">
                    <h2 class="text-center heading">Pesanan ini sudah dikonfirmasi</h2>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

@if($order->status_id == 11)
<div class="container-fluid contact">
    
    <?php
    $total = $order->grand_total - $order->discount_coupon - $order->discount_point;
    if ($total < 0) {
        $total = 0;
    }
    $total += $order->shipment_cost + $order->unique_nominal + $order->insurance_fee;
    ?>
    
    <div class="row">		
        <div class="col-xs-12 col-sm-2">
        </div>
        <div class="col-xs-12 col-sm-8">

            
            <form action="{{ URL::to('confirmpaymentlink') }}" class="myform" method="post">
                {!! csrf_field() !!}
                
                <input type="hidden" name="pesanan" value="{{ $order->id }}" />
                
                @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
                
                <div class="row clearfix">
                    <div class="col-xs-12 col-md-6 col-md-offset-3">
                        <div class="form-group">
                            <label class="control-label">Nama Rekening Anda</label>
                            <div class="controls">
                                <input name="nama_rekening" id="nama_rekening" placeholder="Nama Rekening Anda" class="form-control input-lg" type="text" required="required">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row clearfix">
                    <div class="col-xs-12 col-md-6 col-md-offset-3">
                        <div class="form-group">
                            <label class="control-label">Total Pembayaran</label>
                            <div class="controls">
                                <input name="total_pembayaran" id="total_pembayaran" placeholder="Total Pembayaran" class="form-control input-lg" type="text" value="{{ 'Rp. ' . number_format($total, 2, ',', '.') }}" readonly>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row clearfix">
                    <div class="col-xs-12 col-md-6 col-md-offset-3">
                        <div class="form-group" id="date_picker">
                            <label class="control-label">Tanggal Pembayaran</label>
                            <div class="controls input-group date">
                                <span class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </span>
                                <input type="text" name="tanggal_bayar" id="tanggal_bayar" class="form-control input-lg" readonly placeholder="Tanggal Pembayaran (dd/mm/yyyy) contoh 31/12/2014" value="{{ date('m/d/Y') }}" required="required"/>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row clearfix">
                    <div class="col-xs-12 col-md-6 col-md-offset-3">
                        <div class="form-group">
                            <label class="control-label">Transfer ke Bank</label>
                            <div class="controls">
                                <select name="bank" id="bank" class="form-control input-lg" required="required">
                                    <option value="">-- Silahkan Pilih --</option>
                                    @foreach($banks as $bank)
                                    <option value="{{ $bank->id }}">{!! $bank->bank_name . ' - ' . $bank->bank_account . ' a.n ' . $bank->bank_account_name !!}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>	
                </div>	
                <div class="row clearfix form-group">
                    <div class="col-xs-12 col-md-6 col-md-offset-3">
                        <label class="control-label">Note</label>
                        <div class="controls">
                            <textarea name="note" id="note" placeholder="Note" class="form-control input-lg" rows="3" style="resize:none"></textarea>
                        </div>
                    </div>
                </div>
                <div class="row clearfix">
                    <div class="col-xs-12 col-md-6 col-md-offset-3 margin-bottom-20">
                        <p><button type="submit" class="btn btn-primary pull-right"><i class="fa fa-envelope-o"></i>Konfirmasi</button></p>
                    </div>
                </div>
            </form>	
        </div>	
    </div>

    <hr>
    
    <div class="row"> 
        <div class="col-md-12">
            <div class="row">
                <div class="col-sm-6 col-sm-offset-3">
                    <h3 class="text-center">Detil Pesanan</h3>
                    <h4 class="text-center">{{ $order->invoicenumber }}</h4>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-3"></div>
        <div class="col-md-6 text-left">
            @if($order->paymentconfirmation)
            <div class="row">
                <div class="col-sm-2">
                    <div class="form-group">
                        <label class="control-label">Payment Date</label>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <div class="controls">
                            {{ date('d F Y', strtotime($order->paymentconfirmation->payment_date)) }}
                        </div>
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="form-group">
                        <label class="control-label">Paid to</label>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <div class="controls">
                            {{ $order->paymentconfirmation->bank->bank_name . ' - ' . $order->paymentconfirmation->bank->bank_account . ' a.n ' . $order->paymentconfirmation->bank->bank_account_name }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-2">
                    <div class="form-group">
                        <label class="control-label">Payment Note</label>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <div class="controls">
                            {{ $order->paymentconfirmation->note }}
                        </div>
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="form-group">
                        <label class="control-label">Shipment Method</label>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <div class="controls">
                            {{ $order->shipment_method }}
                        </div>
                    </div>
                </div>
            </div>
            @endif
            <div class="row">
                <div class="col-sm-2"><label class="control-label">Alamat Pengiriman</label></div>
                <div class="col-sm-4">
                    <div class="form-group">
                        @if($order->customeraddress_id == 0)
                        <label class="control-label">
                            {!! 
                            auth()->user()->usersetting->first_name . ' ' . auth()->user()->usersetting->last_name . '<br />'
                            . auth()->user()->usersetting->alamat . '<br />'
                            . auth()->user()->usersetting->kecamatan . '<br />'
                            . auth()->user()->usersetting->kodepos . '<br />'
                            . auth()->user()->usersetting->hp . '<br />'
                            !!}
                        </label>
                        @else
                        <label class="control-label">
                            <?php
                            $customer_address = $order->customeraddress;
                            ?>
                            {!! 
                            $customer_address->first_name . ' - ' . $customer_address->last_name . '<br />'
                            . $customer_address->alamat . '<br />'
                            . $customer_address->kecamatan . '<br />'
                            . $customer_address->kodepos . '<br />'
                            . $customer_address->hp . '<br />'
                            !!}

                        </label>
                        @endif
                    </div>
                </div>
                @if($order->dropship_id != 0)
                <div class="col-sm-2"><label class="control-label">Dropship</label></div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label class="control-label">
                            <?php
                            $dropship = $order->dropship;
                            ?>
                            {!! 
                            $dropship->name . '<br />'
                            . $dropship->alamat . '<br />'
                            . $dropship->hp
                            !!}
                        </label>
                    </div>
                </div>
                @endif
            </div>
            <div class="container-fluid cart-list">
                <div class="row">
                    <div class="col-sm-12">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Produk</th>
                                    <th>Jumlah</th>
                                    <th class="text-right">Harga</th>
                                    <th class="text-right">Total Harga</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $method = $order->shipment_method;
                                ?>

                                @foreach($order->orderdetails as $order_detail)
                                <tr>
                                    <td class="vert-align">{{ $order_detail->productDelete($order_detail->product_id)->product_name }}</td>
                                    <td class="vert-align">{{ $order_detail->qty }}</td>
                                    <td class="vert-align text-right">{!! 'Rp. ' . number_format($order_detail->price, 2, ',', '.') !!}</td>
                                    <td class="vert-align text-right" >{!! 'Rp. ' . number_format($order_detail->price * $order_detail->qty, 2, ',', '.') !!}</td>
                                </tr>
                                @endforeach

                                @if(false && $order->freesample_qty > 0)
                                <tr>
                                    <td class="vert-align"></td>
                                    <td class="vert-align"></td>
                                    <td class="vert-align text-right"><b>Free Sampel</b></td>
                                    <td class="vert-align text-right"><b>{!! number_format($order->freesample_qty, 0, ',', '.') !!}</b></td>
                                </tr>
                                @endif
                                <tr>
                                    <td class="vert-align"></td>
                                    <td class="vert-align"></td>
                                    <td class="vert-align text-right"><b>Total Belanja</b></td>
                                    <td class="vert-align text-right"><b>{!! 'Rp. ' . number_format($order->grand_total, 2, ',', '.') !!}</b></td>
                                </tr>
                                <tr>
                                    <td class="vert-align"></td>
                                    <td class="vert-align"></td>
                                    <td class="vert-align text-right"><b>Biaya pengiriman ({{ $method }})</b></td>
                                    <td class="vert-align text-right"><b>{!! 'Rp. ' . number_format($order->shipment_cost, 2, ',', '.') !!}</b></td>
                                </tr>
                                <tr>
                                    <td class="vert-align"></td>
                                    <td class="vert-align"></td>
                                    <td class="vert-align text-right"><b>Nominal Identifikasi</b></td>
                                    <td class="vert-align text-right"><b>{!! 'Rp. ' . number_format($order->unique_nominal, 2, ',', '.') !!}</b></td>
                                </tr>
                                <tr class="color-red">
                                    <td class="vert-align"></td>
                                    <td class="vert-align"></td>
                                    <td class="vert-align text-right"><b>Total Keseluruhan</b></td>
                                    <td class="vert-align text-right"><b>{!! 'Rp. ' . number_format($total, 2, ',', '.') !!}</b></td>
                                </tr>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>	
</div>
@endif

@stop


@section('script')
    <!--Datepicker-->
    <script type="text/javascript" src="{{ URL::asset('ext/js/plugins/datepicker/bootstrap-datepicker.js') }}"></script>

    <script>
        //Date picker tahun
        $('#date_picker .input-group.date').datepicker({
            todayBtn: "linked",
            keyboardNavigation: false,
            forceParse: false,
            autoclose: true,
            endDate: "true",
        });
    </script>

@stop