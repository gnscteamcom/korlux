@extends('layouts.admin-side.default')


@section('title')
@parent
Konfirmasi Pembayaran Customer
@stop


@section('content')

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Konfirmasi Pembayaran Customer</h1>
    </div>
</div>


@if(Session::has('msg'))
<div class="row">
    <div class="col-lg-8">
        <div class="form-group text-success">
            {!! '<b>' . Session::get('msg') . '</b>' !!}
        </div>
    </div>
</div>
@endif


@if(Session::has('err'))
<div class="row">
    <div class="col-lg-8">
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
                Nomor Invoice
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover margin-top-20">
                        <thead>
                            <tr>
                                <th>Tindakan</th>
                                <th>Nama</th>
                                <th>Tanggal Pesan</th>
                                <th>Nomor Invoice</th>
                                <th>Status</th>
                                <th>Catatan</th>
                                <th>Total Berat</th>
                                <th>Harga Barang</th>
                                <th>Ongkos Kirim</th>
                                <th>Total Diskon</th>
                                <th>Total Bayar</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($orders as $order)

                            <tr>
                                <td>
                                    <a href="{{ URL::to('paymentconfirmationadmin/' . $order->id) }}" title="Konfirmasi Pembayaran"><i class="fa fa-2x fa-fw fa-money"></i></a>
                                </td>
                                <td>
                                    @if($order->user->usersetting != null)
                                    {{ $order->user->usersetting->first_name . ' ' . $order->user->usersetting->last_name }}
                                    @endif
                                </td>
                                <td>{{ date('d F Y', strtotime($order->created_at)) }}</td>
                                <td>{{ $order->invoicenumber }}</td>
                                <td>{{ $order->status->status }}</td>
                                <td>{{ $order->note }}</td>
                                <td>{!! number_format($order->total_weight, 0, ',', '.') . ' gram(s)' !!}</td>
                                <td>{!! 'Rp. ' . number_format($order->grand_total, 2, ',', '.') !!}</td>
                                <td>{!! 'Rp. ' . number_format($order->shipment_cost, 2, ',', '.') !!}</td>
                                <td>{!! 'Rp. ' . number_format($order->discount_coupon + $order->discount_point, 2, ',', '.') !!}</td>
                                <td>{!! 'Rp. ' . number_format($order->grand_total + $order->shipment_cost + $order->insurance_fee
                                    + $order->unique_nominal - $order->discount_coupon - $order->discount_point, 2, ',', '.') !!}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
</div>

</div>
@stop