@extends('layouts.front-end.layouts')


@section('content')

<div class="container-fluid cart-list">
    
    <div class="container">
        <div class="row"> 
            <div class="col-md-12">
                <div class="row">
                    <div class="col-sm-6 col-sm-offset-3">
                        <h2 class="text-center heading" >Stok Produk</h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-2"></div>
            <div class="col-md-6 col-md-offset-1 text-left">
                <table class="table col-sm-12 col-md-12">
                    <thead>
                        <tr>
                            <th>Nama Produk</th>
                            <th>Stok Tersedia</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($products as $product)
                        <tr>
                            <td class="vert-align">{{ $product->product_name }}</td>
                            <td class="vert-align">{{ $product->qty }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                
            </div>
        </div>
    </div>
</div>
@stop