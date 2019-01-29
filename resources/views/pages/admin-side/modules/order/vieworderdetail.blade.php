@extends('layouts.admin-side.default')


@section('title')
@parent
    Order Detail
@stop


@section('content')
    
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Detil Pesanan</h1>
        </div>
    </div>


    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-info">
                <div class="panel-heading">
                    Detil Pesanan {{ $order->invoicenumber }}
                </div>
                <div class="panel-body">
                    

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="status">Status</label>
                                <input type="text" name="status" id="status" class="form-control" readonly value="{{ $order->status->status }}" />
                            </div>
                        </div>
                        @if(strlen($order->barcode) > 0)
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="status">Barcode Unik</label>
                                <input type="text" name="barcode" id="barcode" class="form-control" readonly value="{{ $order->barcode }}" />
                            </div>
                        </div>
                        @endif
                    </div>

                    @if($order->payment_link)
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="status">Link Pembayaran</label>
                                <input type="text" name="status" id="status" class="form-control" readonly value="{{ url('paymentlink/' . $order->payment_link) }}" />
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($order->refundrequest)
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="status">Status</label>
                                <input type="text" name="status" id="status" class="form-control" readonly value="{{ $order->refundrequest->status->status . ' - ' . $order->refundrequest->refund_reason }}" />
                            </div>
                        </div>
                    </div>
                    @endif
                    
                    @if($order->ordermarketplace)
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Invoice Marketplace</label>
                                <input type="text" name="shipment_method" id="shipment_method" class="form-control" readonly value="{{ $order->ordermarketplace->marketplace_invoice }}" />
                            </div>
                        </div>
                    </div>
                    @endif
                    

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Metode Pengiriman</label>
                                <input type="text" name="shipment_method" id="shipment_method" class="form-control" readonly value="{{ $order->shipment_method }}" />
                            </div>
                        </div>
                        @if($order->process_by > 0)
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Diproses oleh</label>
                                <input type="text" name="process_by" id="process_by" class="form-control" readonly value="{{ $order->processby->name }}" />
                            </div>
                        </div>
                        @endif
                    </div>
                    

                    @if($order->paymentconfirmation)
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Pembayaran</label>
                                <?php
                                $text = '';
                                if($order->paymentconfirmation->bank != null){
                                    $text .= $order->paymentconfirmation->bank->bank_name . ' - ' . $order->paymentconfirmation->bank->bank_account . ' a.n ' . $order->paymentconfirmation->bank->bank_account_name . '&#13;';
                                }
                                $text .= $order->paymentconfirmation->payment_date . '&#13;' . $order->paymentconfirmation->note;
                                ?>
                                <textarea style="resize: none" class="form-control" rows="3" readonly>{!! $text !!}</textarea>
                            </div>
                        </div>
                    </div>
                    @endif
                    
                    
                    
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Alamat Pengiriman</label>
                                <textarea style="resize: none" class="form-control" rows="7" readonly>{!! 
                                    $customer_address->first_name . ' - ' . $customer_address->last_name . '&#13;'
                                    . $customer_address->alamat . '&#13;'
                                    . $customer_address->kecamatan . '&#13;'
                                    . $customer_address->kota . '&#13;'
                                    . $customer_address->kodepos . '&#13;'
                                    . $customer_address->hp . '&#13;'
                                !!}</textarea>
                            </div>
                        </div>
                    </div>
                    
                    @if($order->shopeesales)
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Shopee Sales</label>
                                <textarea style="resize: none" class="form-control" rows="3" readonly>{!! 
                                    $order->shopeesales->shopee_invoice_number . '&#13;'
                                    . $order->shopeesales->shopee_resi . '&#13;'
                                    . $order->shopeesales->username . '&#13;'
                                    . $order->shopeesales->shipping_option
                                !!}</textarea>
                            </div>
                        </div>
                    </div>
                    @endif
                        
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>Notes / Catatan</label>
                                <textarea style="resize: none" class="form-control" rows="2" readonly>{!! 
                                    $order->note
                                !!}</textarea>
                            </div>
                        </div>
                        @if(strlen($order->admin_notes) > 0)
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>Notes / Catatan Admin</label>
                                <textarea style="resize: none" class="form-control" rows="2" readonly>{!! 
                                    $order->admin_notes
                                !!}</textarea>
                            </div>
                        </div>
                        @endif
                        @if(strlen($order->cancel_reason) > 0)
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>Alasan Penolakan</label>
                                <textarea style="resize: none" class="form-control" rows="2" readonly>{!! 
                                    $order->cancel_reason
                                !!}</textarea>
                            </div>
                        </div>
                        @endif
                    </div>
                    

                    @if($order->dropship_id != 0)
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Dropship</label>
                                <textarea style="resize: none" class="form-control" rows="3" readonly>{!! 
                                    $order->dropship->name . '&#13;'
                                    . $order->dropship->hp
                                !!}</textarea>
                            </div>
                        </div>
                    </div>
                    @endif
                    
                    
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Produk</th>
                                    <th>Kuantitas</th>
                                    <th>Harga</th>
                                    <th>Total Harga</th>
                                </tr>
                            </thead>
                            
                            <tbody>
                                <?php $i = 1 ?>
                                @foreach($order_details as $order_detail)
                                
                                <tr>
                                    <td>{{ $i++ }}</td>
                                    <td>
                                        {{ $order_detail->productDelete($order_detail->product_id)->product_name }}
                                    </td>
                                    <td>{{ number_format($order_detail->qty, 0, ',', '.') }}</td>
                                    <td>{!! 'Rp. ' . number_format($order_detail->price, 2, ',', '.') !!}</td>
                                    <td>{!! 'Rp. ' . number_format($order_detail->price * $order_detail->qty, 2, ',', '.') !!}</td>
                                </tr>
                                @endforeach
                                
                                
                                @if($order->freesample_qty)
                                <tr class="color-red">
                                    <td colspan="4" class="text-right"><b>Free Sampel</b></td>
                                    <td><b>{!! number_format($order->freesample_qty, 0, ',', '.') !!}</b></td>
                                </tr>
                                @endif
                                <tr>
                                    <td colspan="4" class="text-right"><b>Total Belanja</b></td>
                                    <td>{!! 'Rp. ' . number_format($order->grand_total, 2, ',', '.') !!}</td>
                                </tr>
                                <tr>
                                    <td colspan="4" class="text-right"><b>Diskon Kupon</b></td>
                                    <td>{!! 'Rp. ' . number_format($order->discount_coupon, 2, ',', '.') !!}</td>
                                </tr>
                                <tr>
                                    <td colspan="4" class="text-right"><b>Penggunaan Poin</b></td>
                                    <td>{!! 'Rp. ' . number_format($order->discount_point, 2, ',', '.') !!}</td>
                                </tr>
                                <tr>
                                    <td colspan="4" class="text-right">
                                        <b>Biaya Pengiriman ({{ $order->shipment_method }})</b>
                                        @if(strlen($order->resi_otomatis) > 0)
                                        <br>{{ $order->resi_otomatis }}
                                        @endif
                                    </td>
                                    <td>{!! 'Rp. ' . number_format($order->shipment_cost, 2, ',', '.') !!}</td>
                                </tr>
                                <tr>
                                    <td colspan="4" class="text-right"><b>Biaya Packing</b></td>
                                    <td>{!! 'Rp. ' . number_format($order->packing_fee, 2, ',', '.') !!}</td>
                                </tr>
                                <tr>
                                    <td colspan="4" class="text-right"><b>Asuransi Pengiriman</b></td>
                                    <td>{!! 'Rp. ' . number_format($order->insurance_fee, 2, ',', '.') !!}</td>
                                </tr>
                                <tr>
                                    <td colspan="4" class="text-right"><b>Nominal Identifikasi</b></td>
                                    <td>{!! 'Rp. ' . number_format($order->unique_nominal, 2, ',', '.') !!}</td>
                                </tr>
                                <tr>
                                    <td colspan="4" class="text-right"><b>Grand Total</b></td>
                                    <td>{!! 'Rp. ' . number_format($order->total_paid, 2, ',', '.') !!}</td>
                                </tr>
                            </tbody>
                        </table>
                        
                        
                    </div>
                    
                    
                    
                    @if($order->refundrequest)
                    <h3>Refund</h3>
                    
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="refund_voucher">Refund</label>
                                @if($order->refundrequest->is_refund_voucher)
                                <input type="text" name="refund_voucher" class="form-control" readonly value="Refund ke Voucher"/>
                                @else
                                <input type="text" name="refund_voucher" class="form-control" readonly value="Refund Transfer" />
                                @endif
                            </div>
                        </div>
                    </div>
                    @if(!$order->refundrequest->is_refund_voucher)
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="refund_voucher">{!! $order->refundrequest->bank_name . ' - ' . $order->refundrequest->account_number . ' a.n ' .$order->refundrequest->account_name !!}</label>
                            </div>
                        </div>
                    </div>
                    @endif
                    
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Produk</th>
                                    <th>Jumlah Refund</th>
                                    <th>Harga Satuan</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            
                            <tbody>
                                <?php $i = 1 ?>
                                @foreach($order->refundrequest->refundrequestdetails as $detail)
                                
                                <tr>
                                    <td>{{ $i++ }}</td>
                                    <td>
                                        {{ $detail->orderdetail->product->product_name }}
                                    </td>
                                    <td>{{ number_format($detail->refund_qty, 0, ',', '.') }}</td>
                                    <td>{!! 'Rp. ' . number_format($detail->price, 2, ',', '.') !!}</td>
                                    <td>{!! 'Rp. ' . number_format($detail->price * $detail->refund_qty, 2, ',', '.') !!}</td>
                                </tr>
                                @endforeach
                                
                                
                                <tr>
                                    <td colspan="4" class="text-right"><b>Total Refund</b></td>
                                    <td>{!! 'Rp. ' . number_format($order->refundrequest->total_refund, 2, ',', '.') !!}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    @endif
                    
                    
                    @if($order->orderheaderhistories->count() > 0)
                    
                    @foreach($order->orderheaderhistories as $history)
                        <h3>History Edit - {{ $history->edited_name }} : {{ date('d F Y, H:i:s', strtotime($history->created_at)) }}</h3>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="status">Status</label>
                                    <input type="text" name="status" id="status" class="form-control" readonly value="{{ $history->status->status }}" />
                                </div>
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>Metode Pengiriman</label>
                                    <input type="text" name="shipment_method" id="shipment_method" class="form-control" readonly value="{{ $history->shipment_method }}" />
                                </div>
                            </div>
                        </div>


                        @if($history->paymentconfirmation)
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>Pembayaran</label>
                                    <?php
                                    $text = '';
                                    if($history->paymentconfirmation->bank != null){
                                        $text .= $history->paymentconfirmation->bank->bank_name . ' - ' . $history->paymentconfirmation->bank->bank_account . ' a.n ' . $history->paymentconfirmation->bank->bank_account_name . '&#13;';
                                    }
                                    $text .= $history->paymentconfirmation->payment_date . '&#13;' . $history->paymentconfirmation->note;
                                    ?>
                                    <textarea style="resize: none" class="form-control" rows="3" readonly>{!! $text !!}</textarea>
                                </div>
                            </div>
                        </div>
                        @endif


                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>Alamat Pengiriman</label>
                                    @if($history->customeraddress)
                                    <textarea style="resize: none" class="form-control" rows="7" readonly>{!! 
                                        $history->customeraddress->first_name . ' - ' . $history->customeraddress->last_name . '&#13;'
                                        . $history->customeraddress->alamat . '&#13;'
                                        . $history->customeraddress->kecamatan . '&#13;'
                                        . $history->customeraddress->kota . '&#13;'
                                        . $history->customeraddress->kodepos . '&#13;'
                                        . $history->customeraddress->hp . '&#13;'
                                    !!}</textarea>
                                    @else
                                    <textarea style="resize: none" class="form-control" rows="7" readonly>{!! 
                                        $history->user->usersetting->first_name . ' - ' . $history->user->usersetting->last_name . '&#13;'
                                        . $history->user->usersetting->alamat . '&#13;'
                                        . $history->user->usersetting->kecamatan . '&#13;'
                                        . $history->user->usersetting->kota . '&#13;'
                                        . $history->user->usersetting->kodepos . '&#13;'
                                        . $history->user->usersetting->hp . '&#13;'
                                    !!}</textarea>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>Notes / Catatan</label>
                                    <textarea style="resize: none" class="form-control" rows="2" readonly>{!! 
                                        $history->note
                                    !!}</textarea>
                                </div>
                            </div>
                        </div>


                        @if($history->dropship)
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>Dropship</label>
                                    <textarea style="resize: none" class="form-control" rows="3" readonly>{!! 
                                        $history->dropship->name . '&#13;'
                                        . $history->dropship->hp
                                    !!}</textarea>
                                </div>
                            </div>
                        </div>
                        @endif


                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Produk</th>
                                        <th>Kuantitas</th>
                                        <th>Harga</th>
                                        <th>Total Harga</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php $i = 1 ?>
                                    @foreach($history->orderdetailhistories as $history_detail)

                                    <tr>
                                        <td>{{ $i++ }}</td>
                                        <td>
                                            {{ $history_detail->product->product_name }}
                                        </td>
                                        <td>{{ number_format($history_detail->qty, 0, ',', '.') }}</td>
                                        <td>{!! 'Rp. ' . number_format($history_detail->price, 2, ',', '.') !!}</td>
                                        <td>{!! 'Rp. ' . number_format($history_detail->price * $history_detail->qty, 2, ',', '.') !!}</td>
                                    </tr>
                                    @endforeach


                                    @if($history->freesample_qty)
                                    <tr class="color-red">
                                        <td colspan="4" class="text-right"><b>Free Sampel</b></td>
                                        <td><b>{!! number_format($history->freesample_qty, 0, ',', '.') !!}</b></td>
                                    </tr>
                                    @endif
                                    <tr>
                                        <td colspan="4" class="text-right"><b>Total Belanja</b></td>
                                        <td>{!! 'Rp. ' . number_format($history->grand_total, 2, ',', '.') !!}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="4" class="text-right"><b>Diskon Kupon</b></td>
                                        <td>{!! 'Rp. ' . number_format($history->discount_coupon, 2, ',', '.') !!}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="4" class="text-right"><b>Penggunaan Poin</b></td>
                                        <td>{!! 'Rp. ' . number_format($history->discount_point, 2, ',', '.') !!}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="4" class="text-right"><b>Biaya Pengiriman ({{ $history->shipment_method }})</b></td>
                                        <td>{!! 'Rp. ' . number_format($history->shipment_cost, 2, ',', '.') !!}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="4" class="text-right"><b>Biaya Packing</b></td>
                                        <td>{!! 'Rp. ' . number_format($history->packing_fee, 2, ',', '.') !!}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="4" class="text-right"><b>Asuransi Pengiriman</b></td>
                                        <td>{!! 'Rp. ' . number_format($history->insurance_fee, 2, ',', '.') !!}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="4" class="text-right"><b>Nominal Identifikasi</b></td>
                                        <td>{!! 'Rp. ' . number_format($history->unique_nominal, 2, ',', '.') !!}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="4" class="text-right"><b>Grand Total</b></td>
                                        <td>{!! 'Rp. ' . number_format($history->total_paid, 2, ',', '.') !!}</td>
                                    </tr>
                                </tbody>
                            </table>


                        </div>
                    @endforeach
                    @endif
                    
                </div>
            </div>
        </div>
    </div>
</div>
@stop