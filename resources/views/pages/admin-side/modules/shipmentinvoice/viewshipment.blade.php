@extends('layouts.admin-side.default')


@section('title')
@parent
    Daftar Pesanan
@stop


@section('content')
    
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Daftar Pesanan yang harus Dikirim</h1>
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
                <div class="alert alert-success alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        {!! '<b>' . Session::get('err') . '</b>' !!}
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="row">
        <div class="col-lg-4">
            <div class="panel panel-info">
                <div class="panel-heading">
                    Download Pesanan yang harus dikirim hari ini
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="panel-body">
                                <a href="{{ url('shipment/todaydownload') }}">
                                    <input type="button" value="Download" class="form-control btn btn-primary" />
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-4">
            <div class="panel-body">
                <a href="{{ URL::to('viewimportshipmentinvoice') }}">
                    <input type="button" value="Import Resi" class="form-control btn btn-primary" />
                </a>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-info">
                <div class="panel-heading">
                    Pesanan
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                            <div class="col-md-6 margin-bottom-20">
                                <form method="post" action="{{ URL::to('search/searchshipment') }}">
                                    {!! csrf_field() !!}
                                    <div class="col-lg-12">
                                        <div class="input-group">
                                            <input type="text" name="search" id="search" class="form-control" required="required" autofocus="autofocus" placeholder="Nomor Order" />
                                            <span class="input-group-btn">
                                                <button class="btn btn-default" type="submit">Cari</button>
                                            </span>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <table class="table table-striped table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Rincian</th>
                                        <th>Tanggal Pembayaran</th>
                                        <th>Nama Penerima</th>
                                        <th>Nomor Faktur</th>
                                        <th>Total Berat</th>
                                        <th>Ongkos Kirim</th>
                                        <th width="300px">Nomor Resi</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php $i = 1 ?>
                                    @foreach($orders as $order)

                                        <tr>
                                            <td>{{ $i++ }}</td>
                                            <td>
                                                <a href="{{ URL::to('vieworderdetail/' . $order->id) }}" title="Rincian Order"><i class="fa fa-2x fa-fw fa-info-circle"></i></a>
                                            </td>
                                            <td>{{ date('d F Y', strtotime($order->paymentconfirmation->created_at)) }}</td>
                                            <td>
                                                @if($order->customeraddress_id == 0)
                                                    {!! $order->user->usersetting->first_name . ' ' . $order->user->usersetting->last_name !!}
                                                @else
                                                    {!! $order->customeraddress->first_name . ' ' . $order->customeraddress->last_name !!}
                                                @endif
                                            </td>
                                            <td>{{ $order->invoicenumber }}</td>
                                            <td>{!! number_format($order->total_weight, 0, ',', '.') . ' gram(s)' !!}</td>
                                            <td>{!! 'Rp. ' . number_format($order->shipment_cost, 2, ',', '.') !!}</td>
                                            <td>
                                                <form method="post" action="{{ URL::to('shipment') }}">
                                                    {!! csrf_field() !!}
                                                    <div class="col-lg-12">
                                                        <div class="input-group">
                                                            <input type="hidden" name="order_id" value="{{ $order->id }}" />
                                                            <input type="text" name="resi" class="form-control" required="required" autofocus="autofocus" placeholder="Nomor Resi" />
                                                            <span class="input-group-btn">
                                                                <button class="btn btn-default" type="submit">OK</button>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
    {!! $orders->links() !!}

</div>
@stop