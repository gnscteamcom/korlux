@extends('layouts.admin-side.default')


@section('title')
@parent
    Daftar Kirim Indonesia
@stop


@section('content')

    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Daftar Produk yang Harus Dikirim ke Indonesia</h1>
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
                <div class="alert alert-warning alert-dismissible" role="alert">
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
                    Daftar Kirim Indonesia
                </div>
                <div class="panel-body">
                    <div class="table-responsive">


                        <table class="table table-striped table-bordered table-hover" id="dataTables">
                            <thead>
                                <tr>
                                  <th>Nama Produk</th>
                                  <th>Jumlah</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($details as $detail)
                                <tr>
                                    <td>{{ $detail->product_name }}</td>
                                    <td>{{ number_format($detail->jumlah) }}</td>
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
