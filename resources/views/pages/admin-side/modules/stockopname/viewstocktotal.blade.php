@extends('layouts.admin-side.default')


@section('title')
@parent
    Stok Total
@stop


@section('content')
    
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Stok Total</h1>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-4">
            <div class="panel panel-info">
                <div class="panel-heading">
                    Download Master Data Stok 30 hari
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="panel-body">
                                <a href="{{ url('stocktotal/download') }}">
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
        <div class="col-lg-12">
            <div class="panel panel-info">
                <div class="panel-heading">
                    Stok Total
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <div class="col-md-6 margin-bottom-20">
                            <form method="post" action="{{ URL::to('search/searchstockbyproductname') }}">
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
                                    <th>Barcode</th>
                                    <th>Nama Produk</th>
                                    <th>Stok Sistem</th>
                                    <th>Stok Booked</th>
                                    <th>Stok Total</th>
                                    <th>Stok 30 Hari</th>
                                    <th>Stok Cadangan</th>
                                </tr>
                            </thead>
                            
                            <tbody>
                                <?php $i = 1; ?>
                                @foreach($products as $product)
                                <tr>
                                    <td>{{ $i++ }}</td>
                                    <td>
                                        {!! $product->barcode !!}
                                    </td>
                                    <td>
                                        {!! $product->product_name !!}
                                    </td>
                                    <td>
                                        {!! number_format($product->qty, 0, ',', '.') !!}
                                    </td>
                                    <td>
                                        {!! number_format($product->stock_booked, 0, ',', '.') !!}
                                    </td>
                                    <td>
                                        {!! number_format($product->qty + $product->stock_booked + $product->reserved_qty, 0, ',', '.') !!}
                                    </td>
                                    <td>
                                        {!! number_format($product->stock_sold_30_days, 0, ',', '.') !!}
                                    </td>
                                    <td>
                                        {!! number_format($product->reserved_qty, 0, ',', '.') !!}
                                    </td>
                                </tr>

                                @endforeach
                            </tbody>
                        </table>
                        
                        @if(isset($search))
                        {!! $products->appends(['search' => $search])->links() !!}
                        @else
                        {!! $products->links() !!}
                        @endif
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop