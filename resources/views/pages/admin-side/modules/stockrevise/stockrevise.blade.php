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


    
    @if(Session::has('msg'))
    <div class="col-md-12 text-center" id="msg">
        <div class="alert alert-success" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            {!! Session::get('msg') !!}
        </div>
    </div>
    @endif



    <div class="row">
        <div class="col-lg-4">
            <div class="panel-body">
                <a href="{{ url('stockrevise/revise') }}">
                    <input type="button" value="Ubah Stok" class="form-control btn btn-primary" />
                </a>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="panel panel-info">
                <div class="panel-heading">
                    Revisi Stok Opname
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        <a href="{{ url('stockrevise/download') }}">
                            <input type="button" value="Unduh Format Excel" class="form-control btn btn-primary" />
                        </a>    
                    </div>

                    <form method="post" action="{{ url('stockrevise/import') }}" enctype="multipart/form-data" class="form-horizontal">
                        {!! csrf_field() !!}

                        <div class="form-group">
                            <div class="col-sm-2 control-label">
                                <label for="file">File</label>
                            </div>
                            <div class="col-sm-10">
                                <input type="file" name="file" id="file" required="required"/>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6">
                                <input type="submit" class="btn btn-default btn-success btn-block" value="Import" />
                            </div>
                        </div>

                    </form>
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

                                <tr>
                                    <td>{{ $i++ }}</td>
                                    <td>
                                        {!! $stock->notes !!}
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
                                        Telah diperiksa
                                        @elseif($stock->is_rejected)
                                        Ditolak
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
</div>
@stop