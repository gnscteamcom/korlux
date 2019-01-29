@extends('layouts.admin-side.default')


@section('title')
@parent
    Order Detail
@stop


@section('content')
    

    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Revise Order Detail</h1>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


    
    @if(Session::has('err'))
    <div class="col-md-12 text-center" id="msg">
        <div class="alert alert-danger" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            {{ Session::get('err') }}
        </div>
    </div>
    @endif

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-info">
                <div class="panel-heading">
                    Order Detail {{ $order->invoicenumber }}
                </div>
                <div class="panel-body">
                    
                    <form method="post" action="{{ URL::to('reviseorderdetail') }}">
                        {!! csrf_field(); !!}
                        <input type="hidden" name="user_id" id="user_id" value="{{ $order->user_id }}" />

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="status">Status</label>
                                    <input type="text" name="status" id="status" class="form-control" readonly value="{{ $order->status->status }}" />
                                </div>
                            </div>
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
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>Ganti Alamat Penerima</label>
                                    <input type="text" class="form-control" name="nama_penerima" placeholder="Nama Penerima" />
                                    <input type="text" class="form-control" name="alamat_penerima" placeholder="Alamat Penerima" />
                                    <select name="kecamatan" id="kecamatan" class="form-control kecamatan" style="width: 100%;">
                                        @if($kecamatan_count > 0)
                                        <option value="" disabled selected> -- Please Choose --</option>
                                        @foreach($kecamatans as $kecamatan)
                                        <option value="{{ $kecamatan['id'] }}"> {{ $kecamatan['kecamatan'] }} </option>
                                        @endforeach
                                        @else
                                        <option value="" disabled selected> -- Lost connection, please refresh --</option>
                                        @endif
                                    </select>
                                    <input type="hidden" name="kecamatan_text" id="kecamatan_text" value="" />
                                    <input type="text" class="form-control" name="kodepos_penerima" placeholder="Kodepos Penerima" />
                                    <input type="text" class="form-control" name="hp_penerima" placeholder="HP Penerima" />
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>Metode Pengiriman</label>
                                    <input type="text" class="form-control" readonly value="{{ $order->shipment_method }}" />
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>Ganti Metode Pengiriman</label>
                                    <select class="form-control" id="ship_method" name="ship_method" required>
                                        <option value="{{ $order->shipmethod_id }}" readonly selected>{{ $order->shipment_method }}</option>
                                    </select>
                                    <input type="hidden" name="ship_method_text" id="ship_method_text" value="" />
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6">
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>Biaya Kirim Baru (per KG)</label>
                                    <input type="hidden" name="biaya_kirim" id="biaya_kirim" value="{{ $ongkir_per_kg }}" />
                                    <input type="text" class="form-control" name="biaya_kirim_text" id="biaya_kirim_text" readonly value="Tidak Berubah" />
                                </div>
                            </div>
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
                            @if($order->dropship_id != 0)
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>Dropship</label>
                                    <textarea style="resize: none" class="form-control" rows="3" readonly>{!! 
                                        $order->dropship->name . '&#13;'
                                        . $order->dropship->alamat . '&#13;'
                                        . $order->dropship->hp
                                    !!}</textarea>
                                </div>
                            </div>
                            @else
                            <div class="col-lg-6">
                            </div>
                            @endif
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>Ganti Dropship</label>
                                    <input type="text" class="form-control" name="dropship_name" placeholder="Nama Penerima" />
                                    <input type="text" class="form-control" name="dropship_hp" placeholder="Nomor HP Penerima" />
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>Notes / Catatan</label>
                                    <input type="text" class="form-control" readonly value="{{ $order->note }}" />
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>Ganti Notes / Catatan</label>
                                    <input type="text" class="form-control" name="notes" placeholder="Notes / Catatan" />
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
                                        . $order->shopeesales->username . '&#13;'
                                        . $order->shopeesales->shipping_option
                                    !!}</textarea>
                                </div>
                            </div>
                        </div>
                        @endif
        
                        <div class="table-responsive">
                            <input type="hidden" name="orderheader_id" value="{{ $order->id }}" />

                            <table class="table table-striped table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Product</th>
                                        <th>Qty</th>
                                        <th>Revise Qty</th>
                                        <th>Price</th>
                                        <th>Total Price</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php $i = 1 ?>
                                    @foreach($order->orderdetails as $order_detail)
                                    <?php 
                                        $max_revise_qty = $order_detail->qty + $order_detail->product->qty;
                                    ?>

                                    <tr>
                                        <td>{{ $i++ }}</td>
                                        <td>
                                            {{ $order_detail->productWithTrashed($order_detail->product_id)->product_name }}
                                        </td>
                                        <td>{{ number_format($order_detail->qty, 0, ',', '.') }}</td>
                                        <td>
                                            <input type="hidden" name="curr_qty[]" value="{{ $order_detail->qty }}" />
                                            <input type="hidden" name="orderdetail_id[]" value="{{ $order_detail->id }}" />
                                            <input type="number" min="0" name="revise_qty[]" max="{{ $max_revise_qty }}" min="0" class="form-control" value="{{ $order_detail->qty }}" /> <span class="fg-red">{!! '(Max qty : ' . $max_revise_qty . ')'!!}</span>
                                        </td>
                                        <td>{!! 'Rp. ' . number_format($order_detail->price, 2, ',', '.') !!}</td>
                                        <td>{!! 'Rp. ' . number_format($order_detail->price * $order_detail->qty, 2, ',', '.') !!}</td>
                                    </tr>
                                    @endforeach


                                
                                
                                    @if($order->freesample_qty)
                                    <tr class="color-red">
                                        <td colspan="5" class="text-right"><b>Free Sampel</b></td>
                                        <td><b>{!! number_format($order->freesample_qty, 0, ',', '.') !!}</b></td>
                                    </tr>
                                    @endif
                                    <tr>
                                        <td colspan="5" class="text-right"><b>Total Belanja</b></td>
                                        <td>{!! 'Rp. ' . number_format($order->grand_total, 2, ',', '.') !!}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="5" class="text-right"><b>Diskon Kupon</b></td>
                                        <td>{!! 'Rp. ' . number_format($order->discount_coupon, 2, ',', '.') !!}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="5" class="text-right"><b>Penggunaan Poin</b></td>
                                        <td>{!! 'Rp. ' . number_format($order->discount_point, 2, ',', '.') !!}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="5" class="text-right"><b>Biaya Pengiriman ({{ $order->shipment_method }})</b></td>
                                        <td>{!! 'Rp. ' . number_format($order->shipment_cost, 2, ',', '.') !!}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="5" class="text-right"><b>Biaya Packing</b></td>
                                        <td>{!! 'Rp. ' . number_format($order->packing_fee, 2, ',', '.') !!}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="5" class="text-right"><b>Asuransi Pengiriman</b></td>
                                        <td>{!! 'Rp. ' . number_format($order->insurance_fee, 2, ',', '.') !!}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="5" class="text-right"><b>Nominal Identifikasi</b></td>
                                        <td>{!! 'Rp. ' . number_format($order->unique_nominal, 2, ',', '.') !!}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="5" class="text-right"><b>Grand Total</b></td>
                                        <td>{!! 'Rp. ' . number_format($order->total_paid, 2, ',', '.') !!}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        

                        <!--ADD PRODUCT-->
                        <div class="row form-group">
                            <div class="col-md-8">
                                <label for="tags">Produk: </label>
                                <select name="product" id="product" class="product" style="width:100%;">
                                    <option value="" disabled selected>-- Please Choose Product --</option>
                                    @foreach($products as $product)
                                    <option value="{{ $product->id }}">{{ $product->product_name . ' - Stok : ' . $product->qty }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label for="qty">Kuantitas: </label>
                                <input type="number" id="qty" type="text" class="form-control" name="qty" value="1" onclick="value=''"/>
                            </div>
                        </div>                        
                        <div class="row form-group">
                            <div class="col-md-8 margin-bottom-20">
                                <input type="button" name="add" value="Tambah produk" class="btn btn-default btn-info col-md-12" onclick="addRow()">    
                            </div>
                            <div class="col-md-2 margin-bottom-20">
                                <input type="button" name="remove" value="Hapus produk terakhir" class="btn btn-default btn-info col-md-12" onclick="removeLastRow()">    
                            </div>
                        </div>    
                        
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group margin-top-20">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered table-hover">
                                            <thead>
                                                <tr class="info">
                                                    <th class="col-sm-2">Barang</th>
                                                    <th class="col-sm-2">Harga</th>
                                                    <th class="col-sm-2 text-center">Kuantitas</th>
                                                    <th class="col-sm-2"></th>
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
                            <div class="col-lg-12">
                                <input type="submit" value="Simpan Revisi" class="btn btn-default btn-success btn-block">
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
    <script type="text/javascript" src="{{ URL::asset('ext/js/custom/manualsales.js') }}"></script>

@stop