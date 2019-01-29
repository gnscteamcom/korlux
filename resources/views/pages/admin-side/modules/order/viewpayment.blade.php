@extends('layouts.admin-side.default')


@section('title')
@parent
Verifikasi Pembayaran
@stop


@section('content')

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Verifikasi Pembayaran</h1>
    </div>
</div>

@if(Session::has('msg'))
<div class="row">
    <div class="col-lg-4">
        <div class="form-group text-success">
            {!! '<b>' . Session::get('msg') . '</b>' !!}
        </div>
    </div>
</div>
@endif


@if(Session::has('err'))
<div class="row">
    <div class="col-lg-4">
        <div class="form-group text-danger">
            {!! '<b>' . Session::get('err') . '</b>' !!}
        </div>
    </div>
</div>
@endif



<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                Pesanan
            </div>
            <div class="panel-body">
                <div class="table-responsive">



                    <form method="post" action="{{ URL::to('bulkacceptpayment') }}">
                        {!! csrf_field() !!}

                        <div class="row form-group">
                            <input type="submit" value="Terima semua pembayaran yang dicentang" class="btn btn-block btn-success"/>
                        </div>

                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Bulk</th>
                                    <th>Tindakan</th>
                                    <th>Tanggal Bayar</th>
                                    <th>Nomor Faktur</th>
                                    <th>Total Bayar</th>
                                    <th>Total Seluruh</th>
                                    <th>Nama Pengirim</th>
                                    <th>Nama Tujuan Pengiriman</th>
                                    <th>Bank Tujuan</th>
                                    <th>Catatan</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php $i = 1 ?>
                                @foreach($payments as $payment)
                                
                                @if($payment->paymentconfirmation_id == $payment->id || $payment->paymentconfirmation_id == 0)

                                    @if($payment->status_id == 12)
                                    <tr class="info">
                                    @else
                                    <tr>
                                    @endif
                                        <td>{{ $i++ }}</td>
                                        <td>
                                            @if($payment->status_id == 12 && $payment->paymentconfirmation_id <= 0)
                                            <input type="checkbox" name="bulk[]" value="{{ $payment->orderheader_id }}" style="height: 20px; width: 20px;" />
                                            @endif
                                        </td>
                                        <td>
                                            @if($payment->paymentconfirmation_id <= 0)
                                                <a href="{{ URL::to('vieworderdetail/' . $payment->orderheader_id) }}" title="Order Detail"><i class="fa fa-2x fa-fw fa-info-circle"></i></a>

                                                @if($payment->status_id == 12)
                                                <a href="{{ URL::to('acceptpayment/' . $payment->orderheader_id) }}" title="Accept Payment"><i class="fa fa-fw fa-2x fa-check"></i></a>
                                                <a href="{{ URL::to('rejectpayment/' . $payment->id) }}" title="Reject Payment"><i class="fa fa-fw fa-2x fa-times"></i></a>
                                                @endif
                                            @else
                                                @if($payment->status_id == 12)
                                                <a href="{{ URL::to('multiplepayment/accept/' . $payment->id) }}" title="Accept Payment"><i class="fa fa-fw fa-2x fa-check"></i></a>
                                                <a href="{{ URL::to('multiplepayment/reject/' . $payment->id) }}" title="Reject Payment"><i class="fa fa-fw fa-2x fa-times"></i></a>
                                                @endif
                                            @endif
                                        </td>
                                        <td>
                                            @if($payment->payment_date == null)
                                            {{ '-' }}
                                            @else
                                            {{ date('d F Y', strtotime($payment->payment_date)) }}
                                            @endif
                                        </td>
                                        <td>
                                            @if($payment->paymentconfirmation_id > 0)
                                                @foreach($payment->paymentconfirmations as $confirmation)
                                                {{ $confirmation->orderheader->invoicenumber }}
                                                <a href="{{ URL::to('vieworderdetail/' . $confirmation->orderheader_id) }}" title="Order Detail"><i class="fa fa-2x fa-fw fa-info-circle"></i></a>
                                                <br>
                                                @endforeach
                                            @else
                                            {{ $payment->invoicenumber }}
                                            @endif
                                        </td>
                                        <td>
                                            <?php $total_payment = $payment->total_paid; ?>
                                            @if($payment->paymentconfirmation_id > 0)
                                                <?php $total_payment = 0; ?>
                                                @foreach($payment->paymentconfirmations as $confirmation)
                                                <?php 
                                                $calculate = $confirmation->orderheader->total_paid;
                                                $total_payment += $calculate;
                                                ?>
                                                {!! 'Rp. ' . number_format($calculate, 0, ',', '.') !!}<br>
                                                @endforeach
                                            @else
                                            {!! 'Rp. ' . number_format($total_payment, 0, ',', '.') !!}
                                            @endif
                                        </td>
                                        <td>
                                            <b>{!! 'Rp. ' . number_format($total_payment, 0, ',', '.')!!}</b>
                                        </td>
                                        <td>
                                            {{ $payment->account_name }}<br>
                                            @if($payment->user->usersetting)
                                            {{ $payment->user->usersetting->hp }}
                                            @endif
                                        </td>
                                        <td>
                                            @if($payment->customeraddress_id == 0)
                                            <?php
                                            $user_setting = \App\Usersetting::whereUser_id($payment->user_id)->first();
                                            $to = $user_setting->first_name . ' ' . $user_setting->last_name;
                                            ?>
                                            @else
                                            <?php
                                            $customer_address = App\Customeraddress::where('id', '=', $payment->customeraddress_id)
                                                    ->withTrashed()->first();
                                            $to = $customer_address->first_name . ' ' . $customer_address->last_name;
                                            ?>
                                            @endif
                                            {{ $to }}
                                        </td>
                                        <td>
                                            @if($payment->bank_id == 0)
                                            {!! 'Manual Sales' !!}
                                            @else
                                            {{ $payment->bank->bank_name . ' - ' . $payment->bank->bank_account . ' a.n ' . $payment->bank->bank_account_name }}
                                            @endif
                                        </td>
                                        <td>{{ $payment->note }}</td>
                                    </tr>
                                @endif
                                @endforeach
                            </tbody>
                        </table>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
</div>
@stop