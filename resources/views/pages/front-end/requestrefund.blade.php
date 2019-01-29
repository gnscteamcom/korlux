@extends('layouts.front-end.layouts')

@section('css')
<link rel="stylesheet" href="{{ asset('ext/css/toastr.min.css') }}">
@stop

@section('content')

<div class="container-fluid cart-list">
    
    @if(Session::has('msg'))
    <div class="col-md-offset-3 col-md-6 text-center">
        <div class="alert alert-success" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            {!! Session::get('msg') !!}
        </div>
    </div>
    @endif
    
    @if(Session::has('err'))
    <div class="col-md-offset-3 col-md-6 text-center">
        <div class="alert alert-danger" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            {!! Session::get('err') !!}
        </div>
    </div>
    @endif
    
    <div class="container">
        <div class="row"> 
            <div class="col-md-12">
                <div class="row">
                    <div class="col-sm-6 col-sm-offset-3">
                        <h2 class="text-center heading">Form Refund</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <form action="{{ url('requestrefund') }}" class="myform" method="post">
        {!! csrf_field() !!}
        
        <div class="container-fluid" id="order-table" style="display:none;">
            <div class="row">		
                <div class="col-xs-12 col-sm-8 col-sm-offset-2 col-md-8 col-md-offset-2">
                    <table class="table col-sm-12 col-md-12">
                        <thead>
                            <tr>
                                <th>Produk</th>
                                <th>Jumlah Pembelian</th>
                                <th>Harga</th>
                                <th>Total</th>
                                <th>Qty Refund (pcs)</th>
                            </tr>
                        </thead>
                        <tbody id="order-list">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="container-fluid contact">
            @if ($errors->any())
            <div class="row">
                <div class="col-xs-12 col-sm-2">
                </div>
                <div class="col-xs-12 col-sm-8">
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            @endif
            
            <div class="row">		
                <div class="col-xs-12 col-sm-2">
                </div>
                <div class="col-xs-12 col-sm-8">
                    <div class="row clearfix">
                        <div class="col-xs-12 col-md-12">
                            <div class="form-group">
                                <label class="control-label">Nomor Order</label>
                                <div class=" controls">
                                    <select name="nomor_order" id="nomor_order" class="form-control" required="required">
                                        @if(sizeof($orders) > 0)
                                            <option value="" disabled selected>-- Silahkan Pilih --</option>
                                            @foreach($orders as $order)
                                            <option value="{{ $order['id'] }}">{!! $order['invoicenumber'] !!}</option>
                                            @endforeach
                                        @else
                                            <option value="" disabled selected>-- Tidak ada Order yang dapat di-refund --</option>
                                        @endif
                                    </select>
                                </div>
                            </div>
                        </div>	
                    </div>
                    <div class="row clearfix">
                        <div class="col-xs-12 col-md-12">
                            <div class="form-group">
                                <div class="controls">
                                    <input name="refund_semua" id="refund_semua" type="checkbox" value="1"> <label for="refund_semua"><b>Refund Semua Pesanan </b></label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix form-group">
                        <div class="col-xs-12 col-md-12">
                            <label class="control-label">Alasan Refund</label>
                            <div class="controls">
                                <textarea name="alasan" id="alasan" placeholder="Alasan Refund" class="form-control" rows="3" style="resize:none" required>{{ old('alasan') }}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-xs-12 col-md-12">
                            <div class="form-group">
                                <label class="control-label">Total Refund</label>
                                <div class="controls">
                                    <input type="hidden" name="total_paid" id="total_paid" value="0" />
                                    <input type="hidden" name="total_refund" id="total_refund" value="0" />
                                    <input name="total_refund_text" id="total_refund_text" placeholder="Total Refund" class="form-control" type="text" value="Rp. 0.00" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-xs-12 col-md-12">
                            <div class="form-group">
                                <div class="controls">
                                    <input name="refund_voucher" id="refund_voucher" type="checkbox" value="1"> <label for="refund_voucher"><b>Refund ke Voucher? </b></label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix bank_desc">
                        <div class="col-xs-12 col-md-6">
                            <div class="form-group">
                                <label class="control-label">Nama Bank Anda</label>
                                <div class="controls">
                                    <input name="nama_bank" id="nama_bank" placeholder="Nama Bank Anda" class="form-control" type="text" value="{{ old('nama_bank') }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-6">
                            <div class="form-group">
                                <label class="control-label">Nama Rekening Anda</label>
                                <div class="controls">
                                    <input name="nama_rekening" id="nama_rekening" placeholder="Nama Rekening Anda" class="form-control" type="text" value="{{ old('nama_rekening') }}">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix bank_desc">
                        <div class="col-xs-12 col-md-12">
                            <div class="form-group">
                                <label class="control-label">Nomor Rekening Anda</label>
                                <div class="controls">
                                    <input name="nomor_rekening" id="nomor_rekening" placeholder="Nomor Rekening Anda" class="form-control" type="text" value="{{ old('nomor_rekening') }}">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-xs-12 col-md-6 col-md-offset-1 margin-bottom-20">
                            <p><button type="submit" class="btn btn-primary pull-right"><i class="fa fa-envelope-o"></i>Request</button></p>
                        </div>
                    </div>
                </div>	
            </div>	
        </div>
    </form>	
</div>
@stop

@section('script')
<script type="text/javascript" src="{{ asset('ext/js/toastr.min.js') }}"></script>
<script>
    $('#nomor_order').change(function(){
        $.post(
            "/order/getorderlist",
            {
                _token: $('input[name=_token]').val(),
                order_id: $(this).val()
            },
            function(data){
                data = JSON.parse(data);
                
                if(data.count == 0){
                    toastr.error(data.msg);
                    $('#order-table').attr('style', 'display:none');
                }
                else{
                    toastr.success(data.msg);
                    
                    var html = "";
                    
                    $('#order-list').empty();
                    $.each(data.data, function(key, element){
                        html = html 
                                + "<tr>"
                                + "<td class='vert-align'>" + element.product_name + "</td>"
                                + "<td class='vert-align'>" + element.qty_text + "</td>"
                                + "<td class='vert-align'>" + element.price_text + "</td>"
                                + "<td class='vert-align'>" + element.total + "</td>"
                                + "<td class='vert-align'>"
                                + "<input type='hidden' name='orderdetail_id[]' value='" + element.id + "'/>"
                                + "<input type='hidden' name='price[]' value='" + element.price + "'/>"
                                + "<input type='hidden' name='initialqty[]' value='" + element.qty + "'/>"
                                + "<input type='number' max='" + element.qty + "' value='0' name='new_qty[]' required class='form-control new_qty' data-price='" + element.price + "'/>"
                                +"</td>"
                                + "</tr>";
                    });
                        
                    $('#total_paid').val(data.total_paid);
                    $('#order-list').append(html);

                    $('.new_qty').click(function(){
                        $(this).val('');
                    });
                    $('.new_qty').change(function(){
                        updateTotalRefund(0);
                    });
                    
                    $('#order-table').attr('style', 'display:block');
                }
            
        });
    });
    
    $('#refund_semua').change(function(){
        var is_check = this.checked;
        if(is_check){
            updateTotalRefund(1);
            $('.new_qty').attr('readonly', 'readonly');
        }
        else{
            $('.new_qty').removeAttr('readonly');
        }
    });
    
    $('#refund_voucher').change(function(){
        var is_check = this.checked;
        if(is_check){
            $('.bank_desc').attr('style', 'display:none');
        }
        else{
            $('.bank_desc').removeAttr('style');
        }
    });
    
    function updateTotalRefund(refundAll){
        var total = 0;
        $('.new_qty').each(function(i, obj){
            if(refundAll){
                total = $('#total_paid').val();
            }
            else{
                total = total + $(obj).val() * $(obj).attr('data-price');
            }
        });
        $('#total_refund').val(total);
        $('#total_refund_text').val('Rp. ' + parseFloat(total).toLocaleString());
    }
    
</script>
@stop