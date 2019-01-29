@extends('layouts.admin-side.default')

@section('title')
@parent
Shopee Sales
@stop

@section('content')

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Shopee Sales</h1>
    </div>
</div>

@if(Session::has('msg'))
<div class="row">
    <div class="col-lg-12">
        <div class="form-group text-success">
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
    <div class="col-lg-4">
        <div class="panel panel-info">
            <div class="panel-heading">
                Pilih file untuk diimport
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel-body">
                            <a href="{{ url('shopeesales/download') }}">
                                <input type="button" value="Download Format Excel" class="form-control btn btn-primary" />
                            </a>
                        </div>
                    </div>
                </div>
                
                <form method="post" action="{{ url('shopeesales/import') }}" enctype="multipart/form-data" class="form-horizontal">
                    {!! csrf_field() !!}

                    @if($errors->has('file'))
                    <div class="form-group">
                        <div class="col-sm-0 col-sm-offset-2">
                            <div class="form-group text-danger">
                                {{ $errors->first('file') }}
                            </div>
                        </div>
                    </div>
                    @endif


                    @if($errors->has('file'))
                    <div class="form-group has-error">
                    @else
                    <div class="form-group">
                    @endif
                        <div class="col-sm-2 control-label">
                            <label for="file">File</label>
                        </div>
                        <div class="col-sm-10">
                            <input type="file" name="file" id="file"/>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12">
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
                Master Shopee Sales
            </div>
            <div class="panel-body">
                <div class="table-responsive">

                    <div class="col-md-6 margin-bottom-20">
                        <form method="post" action="{{ url('shopeesales/search') }}">
                            {!! csrf_field() !!}
                            <div class="col-lg-12">
                                <div class="input-group">
                                    <input type="text" name="search" id="search" class="form-control" required="required" autofocus="autofocus" placeholder="Nomor Order Shopee" />
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
                                <th style="width:100px;">Action</th>
                                <th>Nomor Shopee</th>
                                <th>Nomor Invoice</th>
                                <th>Username</th>
                                <th>Nama Penerima</th>
                                <th>Kirim Sebelum</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($sales as $sale)

                            <tr>
                                <td>
                                    @if($sale->orderheader->grand_total <= 0)
                                    <a href="{{ url('shopeesales/add/' . $sale->id) }}"><i class="fa fa-pencil fa-fw fa-2x"></i></a>
                                    @endif
                                    <a href="{{ url('vieworderdetail/' . $sale->orderheader_id) }}"><i class="fa fa-info-circle fa-fw fa-2x"></i></a>
                                </td>
                                <td>
                                    {{ $sale->shopee_invoice_number }}
                                    <br> {{ $sale->shopee_sales }}
                                </td>
                                <td>
                                    {{ $sale->orderheader->invoicenumber }}
                                    <br> {{ $sale->orderheader->status->status }}
                                </td>
                                <td>{{ $sale->username }}</td>
                                <td>{{ $sale->customeraddress->first_name }}</td>
                                <td>{{ $sale->send_before }}</td>
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