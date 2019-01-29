@extends('layouts.front-end.layouts')


@section('content')

    <div class="container">
        <div class="row"> 
            <div class="col-md-12">
                <div class="row">
                    <div class="col-sm-6 col-sm-offset-3">
                        <h2 class="text-center heading" >Detil Pesanan</h2>
                        <h4 class="text-center">{{ $order->invoicenumber }}</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-10 col-md-offset-1 text-left">
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
                                    <tr class="color-green">
                                        <td class="vert-align"></td>
                                        <td class="vert-align"></td>
                                        <td class="vert-align text-right"><b>Diskon Kupon</b></td>
                                        <td class="vert-align text-right"><b>{!! 'Rp. ' . number_format($order->discount_coupon, 2, ',', '.') !!}</b></td>
                                    </tr>
                                    <tr class="color-green">
                                        <td class="vert-align"></td>
                                        <td class="vert-align"></td>
                                        <td class="vert-align text-right"><b>Penggunaan Poin</b></td>
                                        <td class="vert-align text-right"><b>{!! 'Rp. ' . number_format($order->discount_point, 2, ',', '.') !!}</b></td>
                                    </tr>
                                    <tr>
                                        <td class="vert-align"></td>
                                        <td class="vert-align"></td>
                                        <td class="vert-align text-right">
                                            <b>Biaya pengiriman ({{ $method }})</b>
                                            @if(strlen($order->resi_otomatis) > 0)
                                            <br>{{ $order->resi_otomatis }}
                                            @endif
                                        </td>
                                        <td class="vert-align text-right"><b>{!! 'Rp. ' . number_format($order->shipment_cost, 2, ',', '.') !!}</b></td>
                                    </tr>
                                    <tr>
                                        <td class="vert-align"></td>
                                        <td class="vert-align"></td>
                                        <td class="vert-align text-right"><b>Asuransi pengiriman</b></td>
                                        <td class="vert-align text-right"><b>{!! 'Rp. ' . number_format($order->insurance_fee, 2, ',', '.') !!}</b></td>
                                    </tr>
                                    <tr>
                                        <td class="vert-align"></td>
                                        <td class="vert-align"></td>
                                        <td class="vert-align text-right"><b>Packing Fee</b></td>
                                        <td class="vert-align text-right"><b>{!! 'Rp. ' . number_format($order->packing_fee, 2, ',', '.') !!}</b></td>
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
                                        <td class="vert-align text-right"><b>{!! 'Rp. ' . number_format($order->total_paid, 2, ',', '.') !!}</b></td>
                                    </tr>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@stop