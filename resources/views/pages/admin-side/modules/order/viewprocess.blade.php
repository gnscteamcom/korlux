@extends('layouts.admin-side.default')


@section('title')
@parent
    Daftar Pesanan
@stop


@section('content')
    
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Scan barcode atau masukan nomor invoice hanya angka untuk memproses pesanan</h1>
        </div>
    </div>

    @if(Session::has('msg'))
    <div class="row">
        <div class="col-lg-12">
            <div class="form-group text-danger">
                <div class="alert alert-success alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        {!! '<b>' . Session::get('msg') . '</b>' !!}
                </div>
            </div>
        </div>
    </div>
    @endif

    @if(Session::has('err'))
    <div class="row">
        <div class="col-lg-12">
            <div class="form-group text-danger">
                <div class="alert alert-danger alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        {!! '<b>' . Session::get('err') . '</b>' !!}
                </div>
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
                        <div class="col-md-6 margin-bottom-20">
                            <form method="post" action="{{ URL::to('order/process') }}">
                                {!! csrf_field() !!}
                                <div class="col-lg-12">
                                    <div class="input-group">
                                        <input type="text" name="barcode" class="form-control" required="required" autofocus="autofocus" placeholder="Barcode" />
                                        <span class="input-group-btn">
                                            <button class="btn btn-default" type="submit">Proses</button>
                                        </span>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-info">
                <div class="panel-heading">
                    Daftar Pesanan yang belum discan barcode.
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover margin-top-20">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Detail</th>
                                    <th>Username</th>
                                    <th>Tanggal dan Jam Pembayaran</th>
                                    <th>Nomor Faktur</th>
                                    <th>Total Bayar</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php $i = 1 ?>
                                @foreach($orders as $order)

                                <tr>
                                    <td>{{ $i++ }}</td>
                                    <td>
                                        <a href="{{ URL::to('vieworderdetail/' . $order->id) }}" title="Order Detail"><i class="fa fa-2x fa-fw fa-info-circle"></i></a>
                                    </td>
                                    <td>
                                        <a href="{{ url('userdetail/' . $order->user_id) }}"> {{ $order->user->username }} </a>
                                    </td>
                                    <td>
                                        @if($order->accept_time)
                                        {{ date('d F Y H:i:s', strtotime($order->accept_time)) }}
                                        @endif
                                    </td>
                                    <td>
                                        {{ $order->invoicenumber }}
                                    </td>
                                    <td>{!! 'Rp. ' . number_format($order->total_paid, 2, ',', '.') !!}</td>
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