@extends('layouts.admin-side.default')


@section('title')
@parent
    Stok Masuk
@stop


@section('content')
    
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Stok Masuk</h1>
        </div>
    </div>


    <div class="row">
        <div class="col-lg-4">
            <div class="panel-body">
                <a href="{{ URL::to('viewimportstockin') }}">
                    <input type="button" class="form-control btn btn-primary" value="Import Stok Masuk"/>
                </a>
            </div>
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

        
        
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-info">
                <div class="panel-heading">
                    Stok Masuk
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        
                        <div class="col-md-6 margin-bottom-20">
                            <form method="post" action="{{ URL::to('search/searchstockin') }}">
                                {!! csrf_field() !!}
                                <div class="col-lg-12">
                                    <div class="input-group">
                                        <input type="text" name="search" id="search" class="form-control" required="required" autofocus="autofocus" placeholder="Nama Produk" />
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
                                    <th class="col-sm-2">Tindakan</th>
                                    <th>Produk</th>
                                    <th>Stok Masuk</th>
                                    <th>Sisa Stok</th>
                                    <th>Stok Cadangan</th>
                                    <th>Tanggal Stok Masuk</th>
                                </tr>
                            </thead>
                            
                            <tbody>
                                <?php $i = 1; ?>
                                @foreach($stock_ins as $stock_in)

                                @if($stock_in->product)
                                <tr>
                                    <td>{{ $i++ }}</td>
                                    <td>
                                        @if($stock_in->qty == $stock_in->remaining_qty)
                                        <a href="{{ URL::to('editstockin/' . $stock_in->id) }}" title="Update Stock In"><i class="fa fa-pencil fa-fw fa-2x"></i></a>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $stock_in->product->product_name }}
                                    </td>
                                    <td>{{ $stock_in->qty }}</td>
                                    <td>{{ $stock_in->remaining_qty }}</td>
                                    <td>{{ $stock_in->reserved_qty }}</td>
                                    <td>{{ date('l, d F Y H:i:s' , strtotime($stock_in->created_at)) }}</td>
                                </tr>
                                @endif

                                @endforeach
                            </tbody>
                        </table>
                        
                        {!! $stock_ins->links() !!}
                        
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop