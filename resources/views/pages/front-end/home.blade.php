@extends('layouts.front-end.layouts')

@section('css')
<link rel="stylesheet" href="{{ asset('ext/css/toastr.min.css') }}">
@stop

<?php
    $banners = \App\Banner::orderBy('created_at', 'desc')->get();
?>

@section('content')

    @if(session('msg'))
    <div class="col-md-offset-3 col-md-6 text-center">
        <div class="alert alert-success" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            {!! session('msg') !!}
        </div>
    </div>
    @endif

    {!! csrf_field() !!}
    @if($banners->count() > 0)
    <div id="jssor_1" style="position: relative; margin: 0 auto; top: 0px; left: 0px; width: 1300px; height: 440px; overflow: hidden; visibility: hidden;">
        <!-- Loading Screen -->
        <div data-u="loading" style="position: absolute; top: 0px; left: 0px;">
            <div style="filter: alpha(opacity=70); opacity: 0.7; position: absolute; display: block; top: 0px; left: 0px; width: 100%; height: 100%;"></div>
            <div style="position:absolute;display:block;background:url('ext/img/banner/loading.gif') no-repeat center center;top:0px;left:0px;width:100%;height:100%;"></div>
        </div>
        <div data-u="slides" style="cursor: default; position: relative; top: 0px; left: 0px; width: 1300px; height: 440px; overflow: hidden;">
            @foreach($banners as $banner)
            <div data-p="225.00" style="display: none;">
                <a href="{{ $banner->redirect_link }}">
                    <img data-u="image" src="{{ URL::asset($banner->image_path) }}"  width="450px" height="450px"/>
                </a>
            </div>
            @endforeach
        </div>
        <!-- Bullet Navigator -->
        <div data-u="navigator" class="jssorb05" style="bottom:16px;right:16px;" data-autocenter="1">
            <!-- bullet navigator item prototype -->
            <div data-u="prototype" style="width:16px;height:16px;"></div>
        </div>
        <!-- Arrow Navigator -->
        <span data-u="arrowleft" class="jssora22l" style="top:0px;left:12px;width:40px;height:58px;" data-autocenter="2"></span>
        <span data-u="arrowright" class="jssora22r" style="top:0px;right:12px;width:40px;height:58px;" data-autocenter="2"></span>
    </div>
    @endif

    @if($most_buy_products->count() > 0)
    <section style="overflow:hidden; margin:0px 50px 0px 50px;">
        <div class="row">
            <div class="col-md-12">
                <div class="heading">
                    <h4>Best Seller</h4>
                </div>
            </div>
        </div>
        @foreach($most_buy_products->chunk(4) as $product_chunks)
        <div class="row products">
            @foreach($product_chunks as $most_buy_product)
            <?php
                $price = \App\Http\Controllers\Custom\PriceFunction::getCurrentPrice($most_buy_product->currentprice_id);
                $wholesale = false;
                if($most_buy_product->productclasses->count() > 0) $wholesale = true;
            ?>
            <div class="col-md-3 col-xs-6">
                <div class="product">
                    <div class="image">

                        @if($most_buy_product->qty <= 0)
                            <div class="ribbon ribbon-quick-view sale">
                              <div class="soldoutribbon">&nbsp;&nbsp;&nbsp;Sold Out</div>
                              <div class="ribbon-background"></div>
                            </div>
                        @else
                            @if($wholesale)
                            <div class="ribbon ribbon-quick-view sale">
                              <div class="theribbon">&nbsp;&nbsp;&nbsp;Grosir</div>
                              <div class="ribbon-background"></div>
                            </div>
                            @endif
                        @endif

                        @if($most_buy_product->currentprice != null && $most_buy_product->currentprice->sale_price > 0)
                        <div class="ribbon ribbon-quick-view sale margin-top-35">
                          <div class="theribbon bg-teal">&nbsp;&nbsp;&nbsp;Sale</div>
                          <div class="ribbon-background"></div>
                        </div>
                        @endif

                        @if($most_buy_product->productimages->count() > 0)
                        <a href="#" data-toggle="modal" data-target="#most_buy_product{{ $most_buy_product->id }}">
                            <img src="{{ asset($most_buy_product->productimages->first()->image_path) }}" alt="" class="img-responsive" style="height: 150px;">
                        </a>
                        @else
                        <a href="#" data-toggle="modal" data-target="#most_buy_product{{ $most_buy_product->id }}">
                            <img src="{{ asset('/storage/default.jpg') }}" alt="" class="img-responsive" style="height: 150px;">
                        </a>
                        @endif
                        <div class="quick-view-button">
                            <a href="#" data-toggle="modal" data-target="#most_buy_product{{ $most_buy_product->id}}" class="btn btn-default btn-sm">Quick view</a>
                        </div>
                    </div>
                    <div class="text">
                        <p class="brand margin-0">
                            <a href="#" data-toggle="modal" data-target="{{ '#most_buy_product' . $most_buy_product->id }}">{!! $most_buy_product->brand->brand !!}</a>
                        </p>
                        <p>
                            <strong>
                                <a style="font-size:12px;" href="#" data-toggle="modal" data-target="{{ '#most_buy_product' . $most_buy_product->id }}" class="product-name">{!! substr($most_buy_product->product_name, 0, 75) !!}</a>
                            </strong>
                        </p>
                        @if($most_buy_product->currentprice != null && $most_buy_product->currentprice->sale_price > 0)
                            <strike>{!! 'Rp. ' . number_format($most_buy_product->currentprice->regular_price, 0, ',', '.') !!}</strike><br>
                          <strong class="fg-red">{!! 'Rp. ' . number_format($most_buy_product->currentprice->sale_price, 0, ',', '.') !!}</strong>
                        @else
                        <p class="price">{!! 'Rp. ' . number_format($price, 0, ',', '.') !!}</p>
                        @endif
                    </div>
                </div>
            </div>
            <div id="most_buy_product{{ $most_buy_product->id }}" tabindex="-1" role="dialog" aria-hidden="false" class="modal fade">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-body">
                            <div class="row quick-view product-main">
                                <div class="col-sm-6">
                                    <div class="quick-view-main-image">
                                        @if($most_buy_product->productimages->count() > 0)
                                        <img src="{{ URL::asset($most_buy_product->productimages->first()->image_path) }}" alt="{{ $most_buy_product->product_name }}" class="img-responsive">
                                        @endif
                                    </div>
                                    <div class="row thumbs">
                                        @foreach($most_buy_product->productimages as $most_buy_productimage)
                                        <div class="col-xs-4">
                                            <a href="{{ URL::asset($most_buy_productimage->image_path) }}" class="thumb">
                                                <div class="lazyload">
                                                    <!--
                                                    <img src="{{ URL::asset($most_buy_productimage->image_path) }}" alt="{{ $most_buy_product->product_name }}" class="img-responsive lazy" width="450px" height="450px">
                                                    -->
                                                </div>
                                            </a>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <h4 class="product__heading text-center">{!! $most_buy_product->product_name !!}</h4>
                                    @if($most_buy_product->is_set)
                                    @if(sizeof($most_buy_product->sets($most_buy_product->id)) > 0)
                                    <strong>
                                        <span class="text-center col-md-12">
                                            Set Produk
                                        </span>
                                    </strong>
                                    <strong>
                                        <p class="text-center">
                                            @foreach($most_buy_product->sets($most_buy_product->id) as $set)
                                            {!! '- ' . $set->product->product_name . '<br>' !!}
                                            @endforeach
                                        </p>
                                    </strong>
                                    @endif
                                    @endif
                                    <p class="text-muted text-small text-center">
                                        {!! $most_buy_product->product_desc !!}
                                    </p>
                                    <div class="box">
                                        @if($price > 0 && $most_buy_product->qty > 0)
                                        <form method="post" action="{{ URL::to('addtocart') }}" class="addToCart" data-product-id="most_buy_product{{ $most_buy_product->id }}">
                                        @endif

                                            <input type="hidden" value="{{ $most_buy_product->id }}" name="product_id"/>
                                            <input type="hidden" value="{{ $price }}" name="price"/>
                                            @if($most_buy_product->currentprice != null && $most_buy_product->currentprice->sale_price > 0)
                                            <strike><h4 class="text-center">{!! 'Rp. ' . number_format($most_buy_product->currentprice->regular_price, 0, ',', '.') !!}</h4></strike>
                                            <strong><p class="price text-center">{!! 'Rp. ' . number_format($most_buy_product->currentprice->sale_price, 0, ',', '.') !!}</p></strong>
                                            @else
                                            <p class="price text-center">{!! 'Rp. ' . number_format($price, 0, ',', '.') !!}</p>
                                            @endif
                                            @if($wholesale)
                                            <div class="row">
                                                <div class="col-md-7 col-md-offset-3">
                                                    <div class="form-group">
                                                        <label for="qty">Harga Grosir</label>
                                                        @foreach($most_buy_product->productclasses as $most_buy_productclass)
                                                        <label for="qty">Beli {!! $most_buy_productclass->discountqty->min_qty !!} : {!! 'Rp. ' . number_format($most_buy_productclass->discountqty->price, 2, ',', '.') !!} / item</label>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                            @endif
                                            <div class="row margin-top-20">
                                                <div class="col-md-7 col-md-offset-3">
                                                    <div class="form-group">
                                                        <label for="qty">Quantity (Stok : {{ $most_buy_product->qty }} barang)</label>
                                                        <input type="number" value="1" min="1" name="qty" max="{{ $most_buy_product->qty }}" name="qty" class="form-control" required="required">
                                                    </div>
                                                </div>
                                            </div>
                                            <p class="text-center margin-bottom-80">
                                                @if($price > 0 && $most_buy_product->qty > 0)
                                                <button type="submit" class="btn btn-primary margin-top-10 col-xs-12 col-md-5 pull-right"><i class="fa fa-shopping-cart"></i>&nbsp;Tambahkan</button>
                                                @endif
                                                <button type="button" class="btn btn-default margin-top-10 col-xs-12 col-md-5 pull-left" data-dismiss="modal"><i class="fa fa-times"></i>&nbsp;Tutup</button>
                                            </p>
                                        @if($price > 0 && $most_buy_product->qty > 0)
                                        </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endforeach
    </section>
    @endif

    <!--NEW ARRIVAL-->
    @if($new_arrivals->count() > 0)
    <section style="overflow:hidden; margin:0px 50px 0px 50px;">
        <div class="row">
            <div class="col-md-12">
                <div class="heading">
                    <h4>New Arrival</h4>
                </div>
            </div>
        </div>
        @foreach($new_arrivals->chunk(4) as $arrivals_chunks)
        <div class="row products">
            @foreach($arrivals_chunks as $new_product)
            <?php
                $price = \App\Http\Controllers\Custom\PriceFunction::getCurrentPrice($new_product->currentprice_id);
                $wholesale = false;
                if($new_product->productclasses->count() > 0) $wholesale = true;
            ?>
            <div class="col-md-3 col-xs-6">
                <div class="product">
                    <div class="image">

                        @if($new_product->qty <= 0)
                            <div class="ribbon ribbon-quick-view sale">
                              <div class="soldoutribbon">&nbsp;&nbsp;&nbsp;Sold Out</div>
                              <div class="ribbon-background"></div>
                            </div>
                        @else
                            @if($wholesale)
                            <div class="ribbon ribbon-quick-view sale">
                              <div class="theribbon">&nbsp;&nbsp;&nbsp;Grosir</div>
                              <div class="ribbon-background"></div>
                            </div>
                            @endif
                        @endif

                        @if($new_product->currentprice != null && $new_product->currentprice->sale_price > 0)
                        <div class="ribbon ribbon-quick-view sale margin-top-35">
                          <div class="theribbon bg-teal">&nbsp;&nbsp;&nbsp;Sale</div>
                          <div class="ribbon-background"></div>
                        </div>
                        @endif

                        @if($new_product->productimages->count() > 0)
                        <a href="#" data-toggle="modal" data-target="#new_product{{ $new_product->id }}">
                            <img src="{{ asset($new_product->productimages->first()->image_path) }}" alt="" class="img-responsive" style="height: 150px;">
                        </a>
                        @else
                        <a href="#" data-toggle="modal" data-target="#new_product{{ $new_product->id }}">
                            <img src="{{ asset('/storage/default.jpg') }}" alt="" class="img-responsive" style="height: 150px;">
                        </a>
                        @endif

                        <div class="quick-view-button">
                            <a href="#" data-toggle="modal" data-target="#new_product{{ $new_product->id}}" class="btn btn-default btn-sm">Quick view</a>
                        </div>
                    </div>
                    <div class="text">
                        <p class="brand margin-0">
                            <a href="#" data-toggle="modal" data-target="{{ '#new_product' . $new_product->id }}">{!! $new_product->brand->brand !!}</a>
                        </p>
                        <p>
                            <strong>
                                <a style="font-size:12px;" href="#" data-toggle="modal" data-target="{{ '#new_product' . $new_product->id }}" class="product-name">{!! substr($new_product->product_name, 0, 75) !!}</a>
                            </strong>
                        </p>
                        @if($new_product->currentprice != null && $new_product->currentprice->sale_price > 0)
                            <strike>{!! 'Rp. ' . number_format($new_product->currentprice->regular_price, 0, ',', '.') !!}</strike><br>
                          <strong class="fg-red">{!! 'Rp. ' . number_format($new_product->currentprice->sale_price, 0, ',', '.') !!}</strong>
                        @else
                        <p class="price">{!! 'Rp. ' . number_format($price, 0, ',', '.') !!}</p>
                        @endif
                    </div>
                </div>
            </div>
            <div id="new_product{{ $new_product->id }}" tabindex="-1" role="dialog" aria-hidden="false" class="modal fade">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-body">
                            <div class="row quick-view product-main">
                                <div class="col-sm-6">
                                    <div class="quick-view-main-image">
                                        @if($new_product->productimages->count() > 0)
                                        <img src="{{ URL::asset($new_product->productimages->first()->image_path) }}" alt="{{ $new_product->product_name }}" class="img-responsive">
                                        @endif
                                    </div>
                                    <div class="row thumbs">
                                        @foreach($new_product->productimages as $new_productimage)
                                        <div class="col-xs-4">
                                            <a href="{{ URL::asset($new_productimage->image_path) }}" class="thumb">
                                                <div class="lazyload">
                                                    <!--
                                                    <img src="{{ URL::asset($new_productimage->image_path) }}" alt="{{ $new_product->product_name }}" class="img-responsive lazy" width="450px" height="450px">
                                                    -->
                                                </div>
                                            </a>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <h4 class="product__heading text-center">{!! $new_product->product_name !!}</h4>
                                    @if($new_product->is_set)
                                    @if(sizeof($new_product->sets($new_product->id)) > 0)
                                    <strong>
                                        <span class="text-center col-md-12">
                                            Set Produk
                                        </span>
                                    </strong>
                                    <strong>
                                        <p class="text-center">
                                            @foreach($new_product->sets($new_product->id) as $set)
                                            {!! '- ' . $set->product->product_name . '<br>' !!}
                                            @endforeach
                                        </p>
                                    </strong>
                                    @endif
                                    @endif
                                    <p class="text-muted text-small text-center">
                                        {!! $new_product->product_desc !!}
                                    </p>
                                    <div class="box">
                                        @if($price > 0 && $new_product->qty > 0)
                                        <form method="post" action="{{ URL::to('addtocart') }}" class="addToCart" data-product-id="new_product{{ $new_product->id }}">
                                        @endif
                                            <input type="hidden" value="{{ $new_product->id }}" name="product_id"/>
                                            <input type="hidden" value="{{ $price }}" name="price"/>
                                            @if($new_product->currentprice != null && $new_product->currentprice->sale_price > 0)
                                            <strike><h4 class="text-center">{!! 'Rp. ' . number_format($new_product->currentprice->regular_price, 0, ',', '.') !!}</h4></strike>
                                            <strong><p class="price text-center">{!! 'Rp. ' . number_format($new_product->currentprice->sale_price, 0, ',', '.') !!}</p></strong>
                                            @else
                                            <p class="price text-center">{!! 'Rp. ' . number_format($price, 0, ',', '.') !!}</p>
                                            @endif
                                            @if($wholesale)
                                            <div class="row">
                                                <div class="col-md-7 col-md-offset-3">
                                                    <div class="form-group">
                                                        <label for="qty">Harga Grosir</label>
                                                        @foreach($new_product->productclasses as $new_productclass)
                                                        <label for="qty">Beli {!! $new_productclass->discountqty->min_qty !!} : {!! 'Rp. ' . number_format($new_productclass->discountqty->price, 2, ',', '.') !!} / item</label>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                            @endif
                                            <div class="row margin-top-20">
                                                <div class="col-md-7 col-md-offset-3">
                                                    <div class="form-group">
                                                        <label for="qty">Quantity (Stok : {{ $new_product->qty }} barang)</label>
                                                        <input type="number" value="1" min="1" name="qty" max="{{ $new_product->qty }}" name="qty" class="form-control" required="required">
                                                    </div>
                                                </div>
                                            </div>
                                            <p class="text-center margin-bottom-80">
                                                @if($price > 0 && $new_product->qty > 0)
                                                <button type="submit" class="btn btn-primary margin-top-10 col-xs-12 col-md-5 pull-right"><i class="fa fa-shopping-cart"></i>&nbsp;Tambahkan</button>
                                                @endif
                                                <button type="button" class="btn btn-default margin-top-10 col-xs-12 col-md-5 pull-left" data-dismiss="modal"><i class="fa fa-remove"></i>&nbsp;Tutup</button>
                                            </p>
                                        @if($price > 0 && $new_product->qty > 0)
                                        </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endforeach
    </section>
    @endif


    @if($new_stocks->count() > 0)
    <section style="overflow:hidden; margin:0px 50px 0px 50px;">
        <div class="row">
            <div class="col-md-12">
                <div class="heading">
                    <h4>New Stock</h4>
                </div>
            </div>
        </div>
        @foreach($new_stocks->chunk(4) as $product_chunks)
        <div class="row products">
            @foreach($product_chunks as $new_stock)
            <?php
                $price = \App\Http\Controllers\Custom\PriceFunction::getCurrentPrice($new_stock->currentprice_id);
                $wholesale = false;
                if($new_stock->productclasses->count() > 0) $wholesale = true;
            ?>
            <div class="col-md-3 col-xs-6">
                <div class="product">
                    <div class="image">

                        @if($new_stock->qty <= 0)
                            <div class="ribbon ribbon-quick-view sale">
                              <div class="soldoutribbon">&nbsp;&nbsp;&nbsp;Sold Out</div>
                              <div class="ribbon-background"></div>
                            </div>
                        @else
                            @if($wholesale)
                            <div class="ribbon ribbon-quick-view sale">
                              <div class="theribbon">&nbsp;&nbsp;&nbsp;Grosir</div>
                              <div class="ribbon-background"></div>
                            </div>
                            @endif
                        @endif

                        @if($new_stock->currentprice != null && $new_stock->currentprice->sale_price > 0)
                        <div class="ribbon ribbon-quick-view sale margin-top-35">
                          <div class="theribbon bg-teal">&nbsp;&nbsp;&nbsp;Sale</div>
                          <div class="ribbon-background"></div>
                        </div>
                        @endif

                        @if($new_stock->productimages->count() > 0)
                        <a href="#" data-toggle="modal" data-target="#new_product{{ $new_stock->id }}">
                            <img src="{{ asset($new_stock->productimages->first()->image_path) }}" alt="" class="img-responsive" style="height: 150px;">
                        </a>
                        @else
                        <a href="#" data-toggle="modal" data-target="#new_product{{ $new_stock->id }}">
                            <img src="{{ asset('/storage/default.jpg') }}" alt="" class="img-responsive" style="height: 150px;">
                        </a>
                        @endif

                        <div class="quick-view-button">
                            <a href="#" data-toggle="modal" data-target="#new_product{{ $new_stock->id}}" class="btn btn-default btn-sm">Quick view</a>
                        </div>
                    </div>
                    <div class="text">
                        <p class="brand margin-0">
                            <a href="#" data-toggle="modal" data-target="{{ '#new_product' . $new_stock->id }}">{!! $new_stock->brand->brand !!}</a>
                        </p>
                        <p>
                            <strong>
                                <a style="font-size:12px;" href="#" data-toggle="modal" data-target="{{ '#new_product' . $new_stock->id }}" class="product-name">{!! substr($new_stock->product_name, 0, 75) !!}</a>
                            </strong>
                        </p>
                        @if($new_stock->currentprice != null && $new_stock->currentprice->sale_price > 0)
                            <strike>{!! 'Rp. ' . number_format($new_stock->currentprice->regular_price, 0, ',', '.') !!}</strike><br>
                          <strong class="fg-red">{!! 'Rp. ' . number_format($new_stock->currentprice->sale_price, 0, ',', '.') !!}</strong>
                        @else
                        <p class="price">{!! 'Rp. ' . number_format($price, 0, ',', '.') !!}</p>
                        @endif
                    </div>
                </div>
            </div>
            <div id="new_product{{ $new_stock->id }}" tabindex="-1" role="dialog" aria-hidden="false" class="modal fade">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-body">
                            <div class="row quick-view product-main">
                                <div class="col-sm-6">
                                    <div class="quick-view-main-image">
                                        @if($new_stock->productimages->count() > 0)
                                        <img src="{{ URL::asset($new_stock->productimages->first()->image_path) }}" alt="{{ $new_stock->product_name }}" class="img-responsive">
                                        @endif
                                    </div>
                                    <div class="row thumbs">
                                        @foreach($new_stock->productimages as $new_stockimage)
                                        <div class="col-xs-4">
                                            <a href="{{ URL::asset($new_stockimage->image_path) }}" class="thumb">
                                                <div class="lazyload">
                                                    <!--
                                                    <img src="{{ URL::asset($new_stockimage->image_path) }}" alt="{{ $new_stock->product_name }}" class="img-responsive lazy" width="450px" height="450px">
                                                    -->
                                                </div>
                                            </a>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <h4 class="product__heading text-center">{!! $new_stock->product_name !!}</h4>
                                    @if($new_stock->is_set)
                                    @if(sizeof($new_stock->sets($new_stock->id)) > 0)
                                    <strong>
                                        <span class="text-center col-md-12">
                                            Set Produk
                                        </span>
                                    </strong>
                                    <strong>
                                        <p class="text-center">
                                            @foreach($new_stock->sets($new_stock->id) as $set)
                                            {!! '- ' . $set->product->product_name . '<br>' !!}
                                            @endforeach
                                        </p>
                                    </strong>
                                    @endif
                                    @endif
                                    <p class="text-muted text-small text-center">
                                        {!! $new_stock->product_desc !!}
                                    </p>
                                    <div class="box">
                                        @if($price > 0 && $new_stock->qty > 0)
                                        <form method="post" action="{{ URL::to('addtocart') }}" class="addToCart" data-product-id="new_product{{ $new_stock->id }}">
                                        @endif
                                            <input type="hidden" value="{{ $new_stock->id }}" name="product_id"/>
                                            <input type="hidden" value="{{ $price }}" name="price"/>
                                            @if($new_stock->currentprice != null && $new_stock->currentprice->sale_price > 0)
                                            <strike><h4 class="text-center">{!! 'Rp. ' . number_format($new_stock->currentprice->regular_price, 0, ',', '.') !!}</h4></strike>
                                            <strong><p class="price text-center">{!! 'Rp. ' . number_format($new_stock->currentprice->sale_price, 0, ',', '.') !!}</p></strong>
                                            @else
                                            <p class="price text-center">{!! 'Rp. ' . number_format($price, 0, ',', '.') !!}</p>
                                            @endif
                                            @if($wholesale)
                                            <div class="row">
                                                <div class="col-md-7 col-md-offset-3">
                                                    <div class="form-group">
                                                        <label for="qty">Harga Grosir</label>
                                                        @foreach($new_stock->productclasses as $new_stockclass)
                                                        <label for="qty">Beli {!! $new_stockclass->discountqty->min_qty !!} : {!! 'Rp. ' . number_format($new_stockclass->discountqty->price, 2, ',', '.') !!} / item</label>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                            @endif
                                            <div class="row margin-top-20">
                                                <div class="col-md-7 col-md-offset-3">
                                                    <div class="form-group">
                                                        <label for="qty">Quantity (Stok : {{ $new_stock->qty }} barang)</label>
                                                        <input type="number" value="1" min="1" name="qty" max="{{ $new_stock->qty }}" name="qty" class="form-control" required="required">
                                                    </div>
                                                </div>
                                            </div>
                                            <p class="text-center margin-bottom-80">
                                                @if($price > 0 && $new_stock->qty > 0)
                                                <button type="submit" class="btn btn-primary margin-top-10 col-xs-12 col-md-5 pull-right"><i class="fa fa-shopping-cart"></i>&nbsp;Tambahkan</button>
                                                @endif
                                                <button type="button" class="btn btn-default margin-top-10 col-xs-12 col-md-5 pull-left" data-dismiss="modal"><i class="fa fa-remove"></i>&nbsp;Tutup</button>
                                            </p>
                                        @if($price > 0 && $new_stock->qty > 0)
                                        </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endforeach
    </section>
    @endif

    <div id="continue-shop-modal" tabindex="-1" role="dialog" aria-hidden="false" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="row quick-view product-main">
                        <div class="col-sm-12">
                            <p class="product__heading text-center" id="product_cart" style="color: #353535;"></p>
                            <div class="box">
                                <div class="row margin-top-20">
                                    <div class="col-md-7 col-md-offset-3">
                                        <div class="form-group">
                                            <label for="qty">Produk ini berhasil dimasukkan ke Keranjang Belanja.</label>
                                        </div>
                                    </div>
                                </div>
                                <p class="text-center margin-bottom-80">
                                    <a href="{{ url('cart') }}" class="btn btn-primary margin-top-10 col-xs-12 col-md-5 pull-right"><i class="fa fa-money-bill-alt"></i>&nbsp;Bayar</a>
                                    <a href="#" class="btn btn-default margin-top-10 col-xs-12 col-md-5 pull-left" data-dismiss="modal"><i class="fa fa-shopping-cart"></i>&nbsp;Lanjut Belanja</a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(false)
    <!-- LightWidget WIDGET -->
    <div class="container">
        <div class="row">
            <div id="text-page" class="col-md-12">
                <div class="row">
                    <div class="col-md-12">
                        <div class="heading">
                            <h4>Instagram Feed</h4>
                            <h5>@koreanluxuryshop</h5>
                            <p><a href="https://instagram.com/koreanluxuryshop" target="_blank" class="btn btn-default"><i class="fa fa-fw fa-instagram"></i> Follow</a></p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <script src="//lightwidget.com/widgets/lightwidget.js"></script><iframe src="//lightwidget.com/widgets/6e499f3cbf775a6694d828792ce78ccf.html" scrolling="no" allowtransparency="true" class="lightwidget-widget" style="width: 100%; border: 0; overflow: hidden;"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
@stop




@section('script')
<script type="text/javascript" src="{{ URL::asset('ext/js/custom/jssor.slider.mini.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('ext/js/custom/rjs.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('ext/js/custom/autoprocess.js') }}"></script>
<script type="text/javascript" src="{{ asset('ext/js/toastr.min.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('ext/js/custom/products.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('ext/js/plugins/lazy-load/jquery.lazyload-any.js') }}"></script>
<script>
  function load(img)
  {
    img.fadeOut(0, function() {
      img.fadeIn(1000);
    });
  }
  $('.lazyload').lazyload({load: load});
</script>>
<script type="text/javascript" src="{{ URL::asset('ext/js/front-end/front.js') }}"></script>
@stop
