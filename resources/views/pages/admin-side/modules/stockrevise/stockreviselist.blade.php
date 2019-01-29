@extends('layouts.admin-side.default')


@section('title')
@parent
    Revisi Stok
@stop


@section('content')
    
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Revisi Stok</h1>
        </div>
    </div>


    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
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
    
    @if(Session::has('msg'))
    <div class="col-md-12 text-center" id="msg">
        <div class="alert alert-success" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            {!! Session::get('msg') !!}
        </div>
    </div>
    @endif


    <div class="row">
        <div class="col-md-6 margin-bottom-20">
            <form method="post" action="{{ url('search/searchstockrevise') }}">
                {!! csrf_field() !!}
                <div class="col-lg-12">
                    <div class="input-group">
                        <input type="text" name="search" id="search" class="form-control" required="required" autofocus="autofocus" placeholder="Cari Catatan" />
                        <span class="input-group-btn">
                            <button class="btn btn-default" type="submit">Cari</button>
                        </span>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <form method="post" action="#" id="form-revise">
        {!! csrf_field(); !!}
        
        <div class="row">
            <div class="col-lg-4">
                <div class="panel-body">
                    <a href="#" data-toggle="modal" data-target="#approve-modal">
                        <input type="button" value="Setujui Semua Revisi" class="form-control btn btn-primary" />
                    </a>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4">
                <div class="panel-body">
                    <a href="#" data-toggle="modal" data-target="#reject-modal">
                        <input type="button" value="Tolak Semua Revisi" class="form-control btn btn-danger" />
                    </a>
                </div>
            </div>
        </div>
        
        <div class="modal fade" id="approve-modal" tabindex="-1" role="dialog" aria-labelledby="approve-modal" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title" name="approve-modal">Konfirmasi Selesaikan Semua Revisi</h4>
                    </div>
                    <div class="modal-body">
                        Apakah Anda yakin telah menyetujui seluruh revisi yang Anda centang?
                        <br>
                        <br>
                        <input type="text" value="" name="alasan_terima" class="form-control" placeholder="Alasan" />
                    </div>
                    <div class="modal-footer">
                        <input type="button" class="btn btn-default" data-dismiss="modal" value="Batal" />
                        <a href="#" class="btn btn-primary" id="approve-btn" data-url="{{ url('stockrevise/approve') }}" >Setuju</a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="modal fade" id="reject-modal" tabindex="-1" role="dialog" aria-labelledby="reject-modal" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title" name="reject-modal">Konfirmasi Penolakan Semua Revisi</h4>
                    </div>
                    <div class="modal-body">
                        Apakah Anda yakin menolak seluruh revisi yang Anda centang?<br>
                        Seluruh stok akan kembali seperti semula sesuai sebelum dilakukan revisi.
                        <br>
                        <br>
                        <input type="text" value="" name="alasan_tolak" class="form-control" placeholder="Alasan" />
                    </div>
                    <div class="modal-footer">
                        <input type="button" class="btn btn-default" data-dismiss="modal" value="Batal" />
                        <a href="#" class="btn btn-primary" id="reject-btn" data-url="{{ url('stockrevise/reject') }}" >Setuju</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-info">
                    <div class="panel-heading">
                        Revisi Stok
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">


                            <table class="table table-striped table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Catatan</th>
                                        <th>Oleh</th>
                                        <th>Produk</th>
                                        <th>Stok Awal</th>
                                        <th>Jumlah Berubah</th>
                                        <th>Stok Setelah Berubah</th>
                                        <th>Status</th>
                                        <th>Alasan</th>
                                        <th>Tanggal Perubahan</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php $i = 1; ?>
                                    @foreach($revise_list as $stock)

                                    @if($stock->is_approved == 1)
                                    <tr class="info">
                                    @elseif($stock->is_rejected == 1)
                                    <tr class="danger">
                                    @else
                                    <tr>
                                    @endif
                                        <td>
                                            @if($stock->is_approved == 0 && $stock->is_rejected == 0)
                                            <input type="checkbox" style="transform: scale(1.5);" value="{{ $stock->id }}" name="stock_id[]" />
                                            @endif
                                        </td>
                                        <td>
                                            {!! $stock->notes !!}
                                        </td>
                                        <td>
                                            {!! $stock->user->username !!}
                                        </td>
                                        <td>
                                            {!! $stock->product->product_name !!}
                                        </td>
                                        <td>
                                            {!! number_format($stock->initial_qty, 0, ',', '.') !!}
                                        </td>
                                        <td>
                                            {!! number_format($stock->change_qty, 0, ',', '.') !!}
                                        </td>
                                        <td>
                                            {!! number_format($stock->current_qty, 0, ',', '.') !!}
                                        </td>
                                        <td>
                                            @if($stock->is_approved)
                                            Telah diperiksa:
                                            <br>
                                            <b>{{ $stock->approveby->username }}</b>
                                            @elseif($stock->is_rejected)
                                            Ditolak:
                                            <br>
                                            <b>{{ $stock->rejectby->username }}</b>
                                            @endif
                                        </td>
                                        <td>
                                            {!! $stock->reason !!}
                                        </td>
                                        <td>
                                            {!! date('d F Y, H:i:s', strtotime($stock->created_at)) !!}
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
    
    </form>
</div>
@stop


@section('script')
    <script type="text/javascript" src="{{ asset('ext/js/custom/revisestocklist.js?1') }}"></script>
@stop