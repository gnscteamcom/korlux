@extends('layouts.admin-side.default')


@section('title')
@parent
    Order List
@stop


@section('content')
    
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Daftar Pesanan</h1>
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
    
    <div class="row" id="error-msg" style="display: none;">
        <div class="col-lg-4">
            <div class="form-group text-danger msg-place">
            </div>
        </div>
    </div>
    <div class="row" id="success-msg" style="display: none;">
        <div class="col-lg-4">
            <div class="form-group text-success msg-place">
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
                        <div class="row">
                            <div class="col-md-6 margin-bottom-20">
                                <form method="post" action="{{ URL::to('search/searchorder') }}">
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
                            <div class="col-md-6 margin-bottom-20">
                                @if($is_owner == 0)
                                <form method="post" action="{{ URL::to('filtershiporder') }}">
                                    {!! csrf_field() !!}
                                    <div class="row">
                                        <div class="col-md-1">
                                            <label>Tipe</label>
                                        </div>
                                        <div class="col-md-3">
                                            <select id="type" name="type" class="form-control">
                                                <option value="before">Belum diprint</option>
                                                <option value="after">Sudah diprint</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <input type="submit" value="Ganti tipe" class="btn btn-block btn-primary"/>
                                        </div>
                                    </div>
                                </form>
                                @endif
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 margin-bottom-20">
                                <form method="post" action="{{ url('search/searchshopee') }}">
                                    {!! csrf_field() !!}
                                    <div class="col-lg-12">
                                        <div class="input-group">
                                            <input type="text" name="search" id="search" class="form-control" required="required" autofocus="autofocus" placeholder="Nomor Shopee" />
                                            <span class="input-group-btn">
                                                <button class="btn btn-default" type="submit">Cari</button>
                                            </span>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                            
                        @if($is_owner == 0)
                        <form method="post" class="margin-top-20" action="{{ URL::to('printalldo') }}" target="_blank">
                        {!! csrf_field() !!}

                            <div class="row form-group">
                                <input type="submit" value="Cetak Semua Pesanan" class="btn btn-block btn-primary"/>
                            </div>
                        </form>
                        
                        <form method="post" action="{{ URL::to('printdo') }}" target="_blank">
                        {!! csrf_field() !!}
                        
                            <div class="row form-group">
                                <input type="submit" value="Cetak yang Dipilih" class="btn btn-block btn-success"/>
                            </div>
                        
                            <div class="row form-group">
                                <div class="col-lg-2">
                                    <input type="button" value="Select All" id="select-all" class="btn btn-block btn-info" data-is_select="1"/>
                                </div>
                            </div>
                        @endif
                        
                            <table class="table table-striped table-bordered table-hover margin-top-20">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        @if(!Auth::user()->is_owner)
                                        <th>Cetak</th>
                                        @endif
                                        <th>Tindakan</th>
                                        <th>Username</th>
                                        <th>Status - Tanggal Kirim</th>
                                        <th>Nomor Faktur</th>
                                        <th>Catatan</th>
                                        <th>Total Berat</th>
                                        <th>Harga Barang</th>
                                        <th>Ongkos Kirim</th>
                                        <th>Total Diskon</th>
                                        <th>Total Bayar</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php $i = 1 ?>
                                    @foreach($orders as $order)

                                    @if($order->status_id == 13 && $order->status_id == 14)
                                    <tr class="success">
                                    @elseif($order->status_id == 15)
                                    <tr class="info">
                                    @elseif($order->status_id == 16 || $order->status_id == 17)
                                    <tr class="danger">
                                    @else
                                    <tr>
                                    @endif
                                        <td>{{ $i++ }}</td>
                                        @if(!Auth::user()->is_owner)
                                        <td class="text-center">

                                            @if($order->status_id == 13 || $order->status_id == 14)
                                            <input type="checkbox" name="print[]" value="{{ $order->id }}" style='height: 25px; width: 25px;'/>
                                            @endif

                                        </td>
                                        @endif
                                        <td>
                                            @if(auth()->user()->is_owner == 1 && $order->status_id < 14)
                                            <a href="#" title="Reject Order">
                                                <i class="fa fa-2x fa-fw fa-times text-danger" data-toggle="modal" data-target="#orderreject{{ $order->id }}"></i>
                                            </a>
                                            <div class="modal fade" id="orderreject{{ $order->id }}" tabindex="-1" role="dialog" aria-labelledby="<?php echo 'myModalLabel' . $order->id ?>" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <form method="post" action="{{ url('rejectorder') }}">
                                                            {!! csrf_field(); !!}
                                                            <input type="hidden" name="order_id" value="{{ $order->id }}" />
                                                                  
                                                            <div class="modal-header">
                                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                                <h4 class="modal-title" name="<?php echo 'myModalLabel' . $order->id ?>">Konfirmasi Penolakan Pesanan</h4>
                                                            </div>
                                                            <div class="modal-body">
                                                                Kenapa Anda menolak pesanan ini?

                                                                <input type="text" class="form-control" name="reject_reason" placeholder="Alasan Penolakan Pesanan" />
                                                            </div>
                                                            <div class="modal-footer">
                                                                <input type="button" class="btn btn-default" data-dismiss="modal" value="Batal" />
                                                                <button type="submit" class="btn btn-primary">Tolak</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                            @endif

                                            <a href="{{ URL::to('vieworderdetail/' . $order->id) }}" title="Order Detail"><i class="fa fa-2x fa-fw fa-info-circle"></i></a>
                                            @if($order->is_print == 0 && $order->status_id <= 15 && $order->status_id >= 13)
                                            <a href="{{ URL::to('isprint/' . $order->id) }}" title="Print"><i class="fa fa-2x fa-fw fa-check"></i></a>
                                            @endif
                                            
                                            @if(in_array($order->status_id,[16, 17]))
                                            <a href="#" title="Kembalikan Order"><i class="fa fa-refresh fa-fw fa-2x" data-toggle="modal" data-target="<?php echo '#myModal' . $order->id ?>"></i></a>
                                            <div class="modal fade" id="<?php echo 'myModal' . $order->id ?>" tabindex="-1" role="dialog" aria-labelledby="<?php echo 'myModalLabel' . $order->id ?>" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                            <h4 class="modal-title" name="<?php echo 'myModalLabel' . $order->id ?>">Kembalikan Status ke Baru</h4>
                                                        </div>
                                                        <div class="modal-body">
                                                            Apakah Anda yakin akan mengembalikan status order ini ke "Baru"?
                                                        </div>
                                                        <div class="modal-footer">
                                                            <input type="button" value="Batal" class="btn btn-default" data-dismiss="modal" />
                                                            <a href="{{ URL::to('revertcancelorder/' . $order->id) }}" class="btn btn-primary" >Kembalikan</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ url('userdetail/' . $order->user_id) }}"> {{ $order->user->username }} </a><br>
                                            @if($order->user->usersetting)
                                            {{ $order->user->usersetting->hp }}
                                            @endif
                                        </td>
                                        <td>
                                            {{ $order->status->status }}
                                            @if($order->shipment_date != null)
                                            <br>
                                            {!! date('d F Y', strtotime($order->shipment_date)) . '<br /><b>' . $order->shipment_invoice . '</b>' !!}
                                            @endif
                                        </td>
                                        <td>
                                            {{ $order->invoicenumber }}
                                        </td>
                                        <td>
                                            @if($order->shopeesales)
                                            <b>{{ $order->shopeesales->shopee_invoice_number }}</b><br>
                                            @endif
                                            {{ $order->note }}
                                                <div class="col-lg-12">
                                                    <div class="input-group">
                                                        <input type="text" name="admin_notes" class="form-control" id="admin_notes_{{ $order->id }}" autofocus="autofocus" placeholder="Catatan Admin" value="{{ $order->admin_notes }}"/>
                                                        <span class="input-group-btn">
                                                            <button type="button" class="btn btn-default btnAdminNotes" data-orderid="{{ $order->id }}">Simpan</button>
                                                        </span>
                                                    </div>
                                                </div>
                                        </td>
                                        <td>{!! number_format($order->total_weight, 0, ',', '.') . ' gram(s)' !!}</td>
                                        <td>{!! 'Rp. ' . number_format($order->grand_total, 2, ',', '.') !!}</td>
                                        <td>{!! 'Rp. ' . number_format($order->shipment_cost, 2, ',', '.') !!}</td>
                                        <td>{!! 'Rp. ' . number_format($order->discount_coupon + $order->discount_point, 2, ',', '.') !!}</td>
                                        <td>{!! 'Rp. ' . number_format($order->total_paid, 2, ',', '.') !!}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        
                        @if($is_owner == 0)
                        </form>
                        @endif
                        
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
    {!! $orders->links() !!}

</div>
@stop

@section('script')
<script>
    $("#select-all").click(function(){
        var is_checked = $(this).attr('data-is_select');
        if(is_checked == 1){
            $('input:checkbox').prop('checked', true);
            $(this).attr('data-is_select', 0);
            $(this).val('Unselect All');
        }else{
            $('input:checkbox').prop('checked', false);
            $(this).attr('data-is_select', 1);
            $(this).val('Select All');
        }
    });
    
    function saveAdminNotes(order_id, admin_notes){
        $.post(
            "/order/adminnotes",
            {
                _token: $('input[name="_token"]').val(),
                order_id: order_id,
                admin_notes: admin_notes
            },
            function(data){
                data = JSON.parse(data);
                if(data.result == 1){
                    $('#error-msg').attr('style', 'display:block;');
                    $('#success-msg').attr('style', 'display:none;');
                    $('.msg-place').text(data.msg);
                }else{
                    $('#error-msg').attr('style', 'display:none;');
                    $('#success-msg').attr('style', 'display:block;');
                    $('.msg-place').text(data.msg);
                }
        });
    }
    
    $('.btnAdminNotes').click(function(){
        saveAdminNotes($(this).attr('data-orderid'), $('#admin_notes_' + $(this).attr('data-orderid')).val());
    });
</script>
@stop