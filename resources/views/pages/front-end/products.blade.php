@extends('layouts.front-end.layouts')

@section('css')
<link rel="stylesheet" href="{{ asset('ext/css/toastr.min.css') }}">
@stop

@section('content')

    <div class="container margin-top-20">
      @if(Session::has('msg'))
      <div class="col-md-12 text-center" id="msg">
          <div class="alert alert-success" role="alert">
              <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              {{ Session::get('msg') }}
          </div>
      </div>
      @endif
      <div class="row margin-top-20">
        <div class="col-md-10 col-md-offset-1">
          <div class="info-bar">
            <div class="row col-md-6">
                <div class="col-sm-12 col-md-12 products-showing">Menampilkan <strong>{!! $products->count() !!}</strong> dari <strong>{!! $total_product !!}</strong> product(s)</div>
            </div>
            <div class="row col-md-6">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="col-md-8 col-sm-12 col-xs-12 margin-top-10 pull-right">
                        <select class="col-md-12 col-sm-12 col-xs-12 form-control" id="sort_list" name="sort_list">
                            <option value="">-- Urut berdasarkan --</option>
                            <option value="new_product">Produk terbaru</option>
                            <option value="new_price">Perubahan Harga terbaru</option>
                            <option value="new_stock">Perubahan Stok terbaru</option>
                            <option value="most_stock">Stok terbanyak</option>
                            <option value="name">Nama produk</option>
                            <option value="low_price">Harga terendah</option>
                            <option value="high_price">Harga tertinggi</option>
                            <option value="most_buy">Terlaris</option>
                        </select>
                    </div>
                </div>
            </div>
          </div>
          <div class="row products col-md-12" id="product_list">
              <?php
                $i = 0;
                $loop = 0;
              ?>
              @foreach($products as $product)
                <?php
                    $price = \App\Http\Controllers\Custom\PriceFunction::getCurrentPrice($product->currentprice_id);
                    $wholesale = false;
                    if($product->productclasses->count() > 0) $wholesale = true;
                ?>
                <!-- product-->
                @if($i == 0)
                <div class="row">
                    <div class="col-lg-1 col-md-1 col-sm-1"></div>
                @endif
                <div class="col-md-2 col-sm-6 col-xs-6 ">
                  <div class="product">
                    <div class="image">
                        @if($product->qty <= 0)
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
                        @if($product->currentprice != null && $product->currentprice->sale_price > 0)
                        <div class="ribbon ribbon-quick-view sale margin-top-35">
                          <div class="theribbon bg-teal">&nbsp;&nbsp;&nbsp;Sale</div>
                          <div class="ribbon-background"></div>
                        </div>
                        @endif
                      <a href="#" data-toggle="modal" data-target="{{ '#' . $product->id }}">
                        @if($product->productimages->count() > 0)
                        <div class="lazyload">
                          <!--
                          <img src="{{ URL::asset($product->productimages->first()->image_path) }}" alt="{{ $product->product_name }}" class="img-responsive lazy" width="450px" height="450px">
                          -->
                        </div>
                        @else
                        <div class="lazyload">
                          <!--
                          <img src="{{ URL::asset('/storage/default.jpg') }}" alt="{{ $product->product_name }}" class="img-responsive" width="450px" height="450px">
                          -->
                        </div>
                        @endif
                      </a>
                    </div>
                    <div class="text">
                      <p class="brand margin-0">
                          <a href="#" data-toggle="modal" data-target="{{ '#' . $product->id }}">{!! $product->brand->brand !!}</a>
                      </p>
                      <p  class="margin-0">
                        <strong>
                          <!-- <a href="#" data-toggle="modal" data-target="{{ '#' . $product->id }}" class="product-name">{!! $product->product_name !!}</a> -->
                          <a style="font-size:12px;" href="#" data-toggle="modal" data-target="{{ '#' . $product->id }}" class="product-name">{!! substr($product->product_name, 0, 75) !!}</a>
                        </strong>
                      </p>
                        @if($product->currentprice != null && $product->currentprice->sale_price > 0)
                          <strike><p class="price">{!! 'Rp. ' . number_format($product->currentprice->regular_price, 0, ',', '.') !!}</p></strike>
                          <strong><p class="price fg-red">{!! 'Rp. ' . number_format($product->currentprice->sale_price, 0, ',', '.') !!}</p></strong>
                        @else
                        <p class="price">{!! 'Rp. ' . number_format($price, 0, ',', '.') !!}</p>
                        @endif
                    </div>
                  </div>
                </div>
                <?php
                  $i++;
                  $loop++;
                ?>

                @if($i == 5 || $loop == $products->count())
                  <?php $i = 0;?>
                  </div>
                @endif
                <!-- /product-->
                <!-- quick view modal box-->
                <div id="{{ $product->id }}" tabindex="-1" role="dialog" aria-hidden="false" class="modal fade">
                  <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                      <div class="modal-body">
                        <div class="row quick-view product-main">
                          <div class="col-sm-6">
                            <div class="quick-view-main-image">
                                @if($product->productimages->count() > 0)
                                <img src="{{ URL::asset($product->productimages->first()->image_path) }}" alt="{{ $product->product_name }}" class="img-responsive">
                                @endif
                            </div>
                            <div class="row thumbs">
                                @foreach($product->productimages as $productimage)
                                    <div class="col-xs-4">
                                        <a href="{{ URL::asset($productimage->image_path) }}" class="thumb">
                                          <div class="lazyload">
                                          <!--
                                          <img src="{{ URL::asset($productimage->image_path) }}" alt="{{ $product->product_name }}" class="img-responsive lazy" width="450px" height="450px">
                                          -->
                                          </div>
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                          </div>
                          <div class="col-sm-6">
                            <h4 class="product__heading text-center">{!! $product->product_name !!}</h4>
                            @if($product->is_set)
                                @if(sizeof($product->sets($product->id)) > 0)
                                <strong>
                                  <span class="text-center col-md-12">
                                    Set Produk
                                  </span>
                                </strong>
                                <strong>
                                    <p class="text-center">
                                        @foreach($product->sets($product->id) as $set)
                                        {!! '- ' . $set->product->product_name . '<br>' !!}
                                        @endforeach
                                    </p>
                                </strong>
                                @endif
                            @endif
                            <p class="text-muted text-small text-center">
                                {!! $product->product_desc !!}
                            </p>
                            <div class="box">
                                {!! csrf_field() !!}
                                @if($price > 0 && $product->qty > 0)
                                <form method="post" action="#" class="addToCart" data-product-id="{{ $product->id }}">
                                @endif

                                    <input type="hidden" value="{{ $product->id }}" name="product_id"/>
                                    <input type="hidden" value="{{ $price }}" name="price"/>
                                    @if($product->currentprice != null && $product->currentprice->sale_price > 0)
                                      <strike><h4 class="text-center">{!! 'Rp. ' . number_format($product->currentprice->regular_price, 0, ',', '.') !!}</h4></strike>
                                      <strong><p class="price text-center">{!! 'Rp. ' . number_format($product->currentprice->sale_price, 0, ',', '.') !!}</p></strong>
                                    @else
                                      <p class="price text-center">{!! 'Rp. ' . number_format($price, 0, ',', '.') !!}</p>
                                    @endif
                                    @if($wholesale)
                                    <div class="row">
                                        <div class="col-md-7 col-md-offset-3">
                                            <div class="form-group">
                                                <label for="qty">Harga Grosir</label>
                                                @foreach($product->productclasses as $productclass)
                                                <label for="qty">Beli {!! $productclass->discountqty->min_qty !!} : {!! 'Rp. ' . number_format($productclass->discountqty->price, 2, ',', '.') !!} / item</label>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                    <div class="row margin-top-20">
                                        <div class="col-md-7 col-md-offset-3">
                                            <div class="form-group">
                                                <label for="qty">Quantity (Stok : {{ $product->qty }} barang)</label>
                                                <input type="number" value="1" min="1" name="qty" max="{{ $product->qty }}" name="qty" class="form-control" required="required">
                                            </div>
                                        </div>
                                    </div>
                                    <p class="text-center margin-bottom-80">
                                        @if($price > 0 && $product->qty > 0)
                                        <button type="submit" class="btn btn-primary margin-top-10 col-xs-12 col-md-5 pull-right"><i class="fa fa-shopping-cart"></i>&nbsp;Tambahkan</button>
                                        @endif
                                        <button type="button" class="btn btn-default margin-top-10 col-xs-12 col-md-5 pull-left" data-dismiss="modal"><i class="fa fa-times"></i>&nbsp;Tutup</button>
                                    </p>
                                @if($price > 0 && $product->qty > 0)
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


            <div class="pages">
                <input type="hidden" name="brand_id" id="brand_id" value="{{ $brand }}" />
                <input type="hidden" name="category_id" id="category_id" value="{{ $category }}" />
                <input type="hidden" name="subcategory_id" id="subcategory_id" value="{{ $subcategory }}" />
                <input type="hidden" name="sort" id="sort" value="{{ $sort }}" />
                <input type="hidden" name="search" id="search" value="{{ $search }}" />
                <input type="hidden" name="page_number" id="page_number" value="1" />
                <p class="loadMore"><button type="button" class="btn btn-primary" id="loadMore" data-load-item="123"><i class="fa fa-chevron-down"></i> Tampilkan Lebih Banyak</button></p>
            </div>

        </div>
      </div>
    </div>

@stop




@section('script')


<script type="text/javascript" src="{{ URL::asset('ext/js/plugins/lazy-load/jquery.lazyload-any.js') }}"></script>
<script type="text/javascript" src="{{ asset('ext/js/toastr.min.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('ext/js/custom/products.js') }}"></script>


<script>
  function load(img)
  {
    img.fadeOut(0, function() {
      img.fadeIn(1000);
    });
  }
  $('.lazyload').lazyload({load: load});
</script>


<script>
    $(document).ready( function() {
        $('#msg').delay(3000).fadeOut();
    });

    $('#sort_list').change(function(){
        if($(this).val() != ""){
            var url = "{{ URL::to('/') }}" + "/show/" + {{ $brand }} + "/" + {{ $category }} + "/" + {{ $subcategory }} + "/" + $(this).val();
            var search = '{{ $search }}';
            if(search.length != 0){
                url += "/" + search;
            }
            window.location.replace(url);
        }
    });
</script>
<script type="text/javascript" src="{{ URL::asset('ext/js/front-end/front.js') }}"></script>

@stop
