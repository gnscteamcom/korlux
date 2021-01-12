@extends('layouts.front-end.layouts')


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

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-sm-6 col-sm-offset-3">
                        <h2 class="text-center heading" >Histori Pesanan</h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-10 col-md-offset-1 text-left">
                <table class="table col-sm-12 col-md-12">
                    <thead>
                        <tr>
                            <th class="col-sm-2 col-md-2">Invoice Number</th>
                            <th class="col-sm-2 col-md-2">Status</th>
                            <th class="col-sm-2 col-md-2">Date</th>
                            <th class="col-sm-2 col-md-2">Shipment Date</th>
                            <th class="col-sm-2 col-md-2">#</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(!$orders)

                            <tr>
                                <td colspan="5">
                                    <h2 class="text-center">Your history is empty.</h2>
                                </td>
                            </tr>

                        @else

                            @foreach($orders as $order)


                            @if($order->status_id == 11)
                            <tr class="info">
                            @elseif($order->status_id == 14)
                            <tr class="success">
                            @elseif($order->status_id == 16)
                            <tr class="danger">
                            @else
                            <tr>
                            @endif
                                <td class="vert-align">
                                    <input type="hidden" name="orderheader_id" value="{{ $order->id }}"/>
                                    {{ $order->invoicenumber }}
                                </td>
                                <td class="vert-align">
                                    {{ $order->status->status }}
                                    @if($order->refundrequest)
                                    <br><b>{{ $order->refundrequest->status->status }} sebesar Rp. {{ number_format($order->refundrequest->total_refund, 2, ',', '.') }}</b>
                                    @if(strlen($order->refundrequest->voucher_code) > 0)
                                    <br><b>Kode Voucher : {{ $order->refundrequest->voucher_code }} </b>
                                    @endif
                                    @endif
                                </td>
                                <td class="vert-align">
                                    {{ date('d F Y', strtotime($order->created_at)) }}
                                </td>
                                <td class="vert-align">
                                    @if($order->shipment_date != null)
                                    {!! date('d F Y', strtotime($order->created_at)) . '<br /><b>' . $order->shipment_invoice . '</b>' !!}
                                    @endif
                                </td>
                                <td class="vert-align">
                                    @if($order->status_id == 11)
                                    <a href="{{ URL::to('paymentconfirmation') }}" title="Payment Confirmation"><i class="fa fa-2x fa-fw fa-upload"></i></a>
                                    @endif
                                    <a href="{{ URL::to('orderdetail/' . $order->id) }}" title="Detil Pesanan"><i class="fa fa-list"></i></a>
                                </td>
                            </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>

                {!! $orders->links() !!}
            </div>
        </div>
    </div>
</div>
@stop
