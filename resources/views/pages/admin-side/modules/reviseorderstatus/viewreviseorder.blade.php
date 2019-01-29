@extends('layouts.admin-side.default')


@section('title')
@parent
    Order List
@stop


@section('content')
    
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Order List</h1>
        </div>
    </div>


    @if(Session::has('msg'))
    <div class="col-md-12 text-center" id="msg">
        <div class="alert alert-success" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            {{ Session::get('msg') }}
        </div>
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

    @if(false)
    <form method="post" action="{{ URL::to('reviseorderstatus') }}">
        {!! csrf_field() !!}
        <div class="row">
            <div class="col-lg-4">
                <div class="panel panel-info">
                    <div class="panel-heading">
                        Filter by
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <select name="status" class="form-control">
                                        <option value="">-- Please Choose --</option>
                                        @foreach($statuses as $status)
                                        <option value="{{ $status->id }}">{{ $status->status }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <input type="submit" class="form-control btn-success" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    @endif
        
        
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-info">
                <div class="panel-heading">
                    Order
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        
                        <form method="post" action="{{ URL::to('search/searchreviseorder') }}" class="margin-bottom-20">
                            {!! csrf_field() !!}
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="input-group">
                                        <input type="text" name="search" id="search" class="form-control" required="required" autofocus="autofocus" placeholder="Invoice Number" />
                                        <span class="input-group-btn">
                                            <button class="btn btn-default" type="submit">Cari</button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <form method="post" action="{{ URL::to('search/searchshopee/revise') }}" class="margin-bottom-20">
                            {!! csrf_field() !!}
                            <div class="row">
                                {!! csrf_field() !!}
                                <div class="col-lg-12">
                                    <div class="input-group">
                                        <input type="text" name="search" id="search" class="form-control" required="required" autofocus="autofocus" placeholder="Nomor Shopee" />
                                        <span class="input-group-btn">
                                            <button class="btn btn-default" type="submit">Cari</button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </form>
                        
                        
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Action</th>
                                    <th>Name</th>
                                    <th>Order Date</th>
                                    <th>Invoice Number</th>
                                    <th>Note</th>
                                    <th>Item Total</th>
                                    <th>Shipment Cost</th>
                                    <th>Total Bayar</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 1 ?>
                                @foreach($orders as $order)

                                {!! csrf_field(); !!}

                                <input type="hidden" value="{{ $order->id }}" name="orderheader_id"/>

                                <tr>
                                    <td>{{ $i++ }}</td>
                                    <td>
                                        <a href="{{ URL::to('viewreviseorderdetail/' . $order->id) }}" title="Order Detail">
                                            <i class="fa fa-2x fa-fw fa-info-circle"></i>
                                        </a>
                                    </td>
                                    <td>
                                        @if($order->user->usersetting != null)
                                            {{ $order->user->usersetting->first_name . ' ' . $order->user->usersetting->last_name }}
                                        @endif
                                    </td>
                                    <td>{{ date('d F Y', strtotime($order->created_at)) }}</td>
                                    <td>
                                        {{ $order->invoicenumber }}
                                        @if($order->shopeesales)
                                        <br><b>{{ $order->shopeesales->shopee_invoice_number }}</b>
                                        @endif
                                    </td>
                                    <td>{{ $order->note }}</td>
                                    <td>{!! 'Rp. ' . number_format($order->grand_total, 0, ',', '.') !!}</td>
                                    <td>{!! 'Rp. ' . number_format($order->shipment_cost, 0, ',', '.') !!}</td>
                                    <td>{!! 'Rp. ' . number_format($order->total_paid, 0, ',', '.') !!}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(isset($search))
    {!! $orders->appends(['search' => $search])->links() !!}
    @else
    {!! $orders->links() !!}
    @endif
    
</div>
@stop