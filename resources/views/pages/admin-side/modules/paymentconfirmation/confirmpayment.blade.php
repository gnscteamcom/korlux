@extends('layouts.admin-side.default')


@section('title')
@parent
Konfirmasi Pembayaran
@stop


@section('content')

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Konfirmasi Pembayaran</h1>
    </div>
</div>


<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                Konfirmasi Pembayaran
            </div>
            <div class="panel-body">

                <form method="post" action="{{ URL::to('confirmpaymentadmin') }}">
                    {!! csrf_field() !!}

                    <input type="hidden" id="order_id" name="order_id" value="{{ $order->id }}"/>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="nomor_invoice">Nomor Invoice</label>
                                <input type="text" name="nomor_invoice" id="nomor_invoice" class="form-control" placeholder="Nomor Invoice" value="{{ $order->invoicenumber }}" readonly/>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="total_pembayaran">Total Pembayaran</label>
                                <input type="text" name="total_pembayaran" id="total_pembayaran" class="form-control" placeholder="Total Pembayaran" value="Rp. 0.00" readonly/>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="nama_rekening">Nama Rekening</label>
                                <input type="text" name="nama_rekening" id="nama_rekening" class="form-control" placeholder="Nama Rekening" value="{{ old('nama_rekening') }}" required="required"/>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group" id="date_picker">
                                <label class="control-label">Tanggal Pembayaran</label>
                                <div class="controls input-group date">
                                    <span class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </span>
                                    <input type="text" name="tanggal_bayar" id="tanggal_bayar" class="form-control input" readonly placeholder="Tanggal Pembayaran (dd/mm/yyyy) contoh 31/12/2014" value="{{ date('m/d/Y') }}" required="required"/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="bank">Bank</label>
                                <select name="bank" id="bank" class="form-control input" required="required">
                                    <option value="" disabled selected>-- Silahkan Pilih --</option>
                                    @foreach($banks as $bank)
                                    <option value="{{ $bank->id }}">{!! $bank->bank_name . ' - ' . $bank->bank_account . ' a.n ' . $bank->bank_account_name !!}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="note">Note</label>
                                <textarea name="note" id="note" placeholder="Note" class="form-control input" rows="3" style="resize:none"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            @if(!$errors->isEmpty())
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group text-danger">
                                        <div class="alert alert-danger alert-dismissible" role="alert">
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                            @if($errors->has('nama_rekening'))
                                            {{ $errors->first('nama_rekening') }}
                                            @endif
                                            @if($errors->has('bank'))
                                            {{ $errors->first('bank') }}
                                            @endif
                                            @if($errors->has('tanggal_bayar'))
                                            {{ $errors->first('tanggal_bayar') }}
                                            @endif
                                            @if($errors->has('note'))
                                            {{ $errors->first('note') }}
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <input type="submit" value="Tambah" class="btn btn-default btn-success btn-block"/>
                        </div>
                    </div>

                </form>


            </div>
        </div>
    </div>
</div>
</div>

@include('includes.admin-side.validation')
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

$.post(
        "{{ url('api/getInvoiceValue') }}",
        {
            orderheader_id: $('#order_id').val(),
            _token: token
        },
        function (data) {

            if (data == null || data == '') {
                $('#total_pembayaran').val('Rp. 0.00');
            } else {
                $('#total_pembayaran').val('Rp. ' + parseFloat(data).toLocaleString() + '.00');
            }

        }
);
</script>
@stop