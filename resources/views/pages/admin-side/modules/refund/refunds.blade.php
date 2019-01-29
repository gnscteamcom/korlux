@extends('layouts.admin-side.default')


@section('title')
@parent
    Order List
@stop


@section('content')
    
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Daftar Pengajuan Refund</h1>
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
                    Pengajuan Pesanan
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <div class="col-md-6 margin-bottom-20">
                            <form method="post" action="{{ URL::to('search/searchrefund') }}">
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
                        
                        <table class="table table-striped table-bordered table-hover margin-top-20">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Tindakan</th>
                                    <th>Username</th>
                                    <th>Nomor Faktur</th>
                                    <th>Tanggal Pesan</th>
                                    <th>Status Refund</th>
                                    <th>Alasan Refund</th>
                                    <th>Total Refund</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php $i = 1 ?>
                                @foreach($orders as $order)

                                @if($order->status_id == 2)
                                <tr class="warning">
                                @elseif($order->status_id == 3)
                                <tr class="danger">
                                @elseif($order->status_id == 4)
                                <tr class="info">
                                @else
                                <tr>
                                @endif
                                    <td>{{ $i++ }}</td>
                                    <td>
                                        @if(auth()->user()->is_owner)
                                        
                                        @if($order->status_id == 1)
                                            <a href="#" title="Reject Order"><i class="fa fa-2x fa-fw fa-times text-danger" data-toggle="modal" data-target="#rejectRefund{{ $order->id }}"></i></a>
                                            <div class="modal fade" id="rejectRefund{{ $order->id }}" tabindex="-1" role="dialog" aria-labelledby="rejectRefundLabel{{ $order->id }}" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                            <h4 class="modal-title" name="rejectRefundLabel{{ $order->id }}">Tolak Refund</h4>
                                                        </div>
                                                        <form method="post" action="{{ url('rejectrefund') }}">
                                                            {!! csrf_field(); !!}
                                                            <input type="hidden" name="refund_id" value="{{ $order->id }}" />
                                                            <div class="modal-body">
                                                                Apakah Anda yakin menolak Refund ini? Berikan catatan Anda.
                                                                <textarea class="form-control" placeholder="Alasan penolakan Refund." rows="3" style="resize: none;" name="notes"></textarea>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <input type="button" class="btn btn-default" data-dismiss="modal" value="Batal" />
                                                                <input type="submit" class="btn btn-primary" value="Tolak Refund" />
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>

                                            <a href="#" title="Accept Order"><i class="fa fa-2x fa-fw fa-check text-info" data-toggle="modal" data-target="#acceptRefund{{ $order->id }}"></i></a>
                                            <div class="modal fade" id="acceptRefund{{ $order->id }}" tabindex="-1" role="dialog" aria-labelledby="acceptRefundLabel{{ $order->id }}" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                            <h4 class="modal-title" name="acceptRefundLabel{{ $order->id }}">Proses Refund</h4>
                                                        </div>
                                                        <form method="post" action="{{ url('acceptrefund') }}">
                                                            {!! csrf_field(); !!}
                                                            <input type="hidden" name="refund_id" value="{{ $order->id }}" />
                                                            <div class="modal-body">
                                                                Apakah Anda yakin melakukan Refund ini? Berikan catatan Anda.
                                                                <textarea class="form-control" placeholder="Alasan melakukan Refund." rows="3" style="resize: none;" name="notes"></textarea>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <input type="button" class="btn btn-default" data-dismiss="modal" value="Batal" />
                                                                <input type="submit" class="btn btn-primary" value="Refund" />
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                            @endif
                                            
                                        @endif
                                        
                                        @if($order->status_id == 2)
                                            <a href="{{ url('refund/finish/' . $order->id) }}" title="Finish Refund"><i class="fa fa-2x fa-fw fa-check text-info"></i></a>
                                        @endif

                                        <a href="{{ URL::to('vieworderdetail/' . $order->order_id) }}" title="Order Detail"><i class="fa fa-2x fa-fw fa-info-circle"></i></a>
                                    </td>
                                    <td>
                                        <a href="{{ url('userdetail/' . $order->order->user_id) }}"> {{ $order->order->user->username }} </a>
                                    </td>
                                    <td>{{ $order->order->invoicenumber }}</td>
                                    <td>{{ date('d F Y', strtotime($order->order->created_at)) }}</td>
                                    <td>
                                        {{ $order->status->status }}
                                        @if(strlen($order->notes) > 0)
                                        <br>
                                        <b>{{ $order->notes }}</b>
                                        @endif
                                    </td>
                                    <td>{{ $order->refund_reason }}</td>
                                    <td>
                                        {!! 'Rp. ' . number_format($order->total_refund, 2, ',', '.') !!}
                                        @if($order->is_refund_voucher)
                                        <br>
                                        <b>{!! 'Refund ke Voucher' !!}</b>
                                        @if(strlen($order->voucher_code) > 0)
                                        <br>
                                        <b>{!! $order->voucher_code !!}</b>
                                        @endif
                                        @else
                                        <b>{!! 'Refund Transfer' !!}</b>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {!! $orders->links(); !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@stop