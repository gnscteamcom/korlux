@extends('layouts.front-end.layouts')


@section('css')
    <link rel="stylesheet" href="{{ URL::asset('ext/css/plugins/datepicker.css') }}">
@stop

@section('content')

<div class="container">
    <div class="row"> 
        <div class="col-md-12">
            <div class="row">
                <div class="col-sm-6 col-sm-offset-3">
                    <h2 class="text-center heading">Konfirmasi Pembayaran</h2>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid contact">
    <div class="row">		
        <div class="col-xs-12 col-sm-2">
        </div>
        <div class="col-xs-12 col-sm-8">

            
            <form action="{{ URL::to('confirmpayment') }}" class="myform" method="post">
                {!! csrf_field() !!}
                
                @if($orders->count() > 0)
                <div class="row clearfix">
                    <div class="col-xs-12 col-md-6 col-md-offset-3">
                        <div class="form-group">
                            <label class="control-label">Pilih Pesanan</label>
                            <div class=" controls">
                                @foreach($orders as $order)
                                <input type="checkbox" class="order" name="pesanan[]" value="{{ $order->id }}" style="height: 20px; width: 20px;"/> <b>{{ $order->invoicenumber }} </b><br>
                                @endforeach
                                @if($errors->first('pesanan'))
                                <span class="text-danger">
                                    {{ $errors->first('pesanan') }}
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>	
                </div>
                @else
                <div class="row clearfix">
                    <div class="col-xs-12 col-md-6 col-md-offset-3">
                        <div class="form-group">
                            <label class="control-label"><b>Tidak ada pesanan baru.</b></label>
                        </div>
                    </div>	
                </div>
                @endif
                <div class="row clearfix">
                    <div class="col-xs-12 col-md-6 col-md-offset-3">
                        <div class="form-group">
                            <label class="control-label">Nama Rekening Anda</label>
                            <div class="controls">
                                <input name="nama_rekening" id="nama_rekening" placeholder="Nama Rekening Anda" class="form-control input-lg" type="text" required="required">
                                @if($errors->first('nama_rekening'))
                                <span class="text-danger">
                                    {{ $errors->first('nama_rekening') }}
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row clearfix">
                    <div class="col-xs-12 col-md-6 col-md-offset-3">
                        <div class="form-group">
                            <label class="control-label">Total Pembayaran</label>
                            <div class="controls">
                                <input name="total_pembayaran" id="total_pembayaran" placeholder="Total Pembayaran" class="form-control input-lg" type="text" value="Rp. 0.00" readonly>
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
                            @if($errors->first('note'))
                            <span class="text-danger">
                                {{ $errors->first('note') }}
                            </span>
                            @endif
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
</div>

@stop


@section('script')

    <!--Datepicker-->
    <script type="text/javascript" src="{{ URL::asset('ext/js/plugins/datepicker/bootstrap-datepicker.js') }}"></script>

    <script>
        
        var token = $('input[name=_token]').val();
        
        //Date picker tahun
        $('#date_picker .input-group.date').datepicker({
            todayBtn: "linked",
            keyboardNavigation: false,
            forceParse: false,
            autoclose: true,
            endDate: "true",
        });
        
        
        //MULTIPLE PEMBAYARAN
        $('.order').click(function(){
            var checkboxes = $('input:checkbox:checked').map(function(){
                return this.value;
            }).get();
            
            $.post(
                "/api/payment/total",
                {
                    _token:token,
                    orders:checkboxes
                },
                function(data){
                    $('#total_pembayaran').val('Rp. ' + parseFloat(data).toLocaleString() + '.00');
            });
        });
        
    </script>

@stop