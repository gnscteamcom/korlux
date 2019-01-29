@extends('layouts.front-end.layouts')


@section('content')


    <div class="container">
      <div class="col-md-12">
        <div class="row page-top">
          <div class="col-sm-10 col-sm-offset-1">
            <h1>Keranjang Belanja</h1>
            <p class="text-muted">Anda memiliki {{ Cart::instance('main')->count(false) }} barang di keranjang.</p>
          </div>
        </div>
      </div>
      <div id="basket" class="col-md-12">
        <div class="box">
          <form method="post" action="{{ URL::to('updatecart') }}">
              {!! csrf_field(); !!}
            <div class="table-responsive">
              <table class="table">
                <thead>
                  <tr>
                    <th colspan="2">Produk</th>
                    <th>Qty</th>
                    <th>Harga Satuan</th>
                    <th>Berat (gram)</th>
                    <th colspan="2">SubTotal</th>
                  </tr>
                </thead>
                <tbody>
                    <?php
                        $total_weight = 0;
                    ?>
                    @foreach(Cart::instance('main')->content() as $cart)
                    <?php
                        $weight = $cart->options->weight * $cart->qty;
                        $total_weight += $weight;
                    ?>
                    <tr> 
                        <input type="hidden" value="{{ $cart->id }}" name="product_id[]"/>
                        <input type="hidden" value="{{ $cart->price }}" name="price[]"/>
                        <input type="hidden" value="{{ $cart->rowid }}" name="cart_rowid[]"/>
                      <td>
                          @if(strlen($cart->options->image_path) > 0)
                          <img src="{{ URL::asset($cart->options->image_path) }}" alt="{{ $cart->name }}"></a>
                          @else
                          <img src="{{ URL::asset('/storage/default.jpg') }}" alt="{{ $cart->name }}"></a>
                          @endif
                      </td>
                      <td>{{ $cart->name }}</td>
                      <td><input type="number" value="{{ $cart->qty }}" min="1" max="{{ $cart->options->max }}" name="qty[]" class="form-control"></td>
                      <td>{!! 'Rp. ' . number_format($cart->price, 0, ',', '.') !!}</td>
                      <td>{!! number_format($weight, 0, ',', '.') !!}</td>
                      <td>{!! 'Rp. ' . number_format($cart->price * $cart->qty, 0, ',', '.') !!}</td>
                      <td><a href="{{ URL::to('removecartitem/' . $cart->rowid) }}"><i class="fa fa-trash-o"></i></a></td>
                    </tr>
                    @endforeach
                    <tr class=""> 
                        <td colspan="2"></td>
                        <td colspan="2">
                            <strong>Total</strong>
                        </td>
                        <td><strong>{!! number_format($total_weight, 0, ',', '.') !!}</strong></td>
                        <td><strong>{!! 'Rp. ' . number_format(Cart::instance('main')->total(), 0, ',', '.') !!}</strong></td>
                        <td></td>
                    </tr>
                </tbody>
              </table>
            </div>
            <div class="box-footer">
              <div class="pull-left"><a href="{{ URL::to('home') }}" class="btn btn-default"><i class="fa fa-chevron-left"></i> Lanjut Belanja</a></div>
              <div class="pull-right">
                  <a href="{{ url('refreshcart') }}">
                      <button type="button" class="btn btn-default">Kosongkan <i class="fa fa-trash-o"></i></button>
                  </a>
                  <button type="submit" class="btn btn-default"><i class="fa fa-refresh"></i> Ubah Qty</button>
                  @if(Cart::total() > 0)
                  <a href="{{ URL::to('checkout') }}">
                      <button type="button" class="btn btn-primary">Checkout <i class="fa fa-chevron-right"></i></button>
                  </a>
                  @endif
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>


@stop


@section('script')
    @if(!auth()->user()->is_processed)
    <?php
    $user = \App\Http\Controllers\Custom\UserFunction::grabUserData(auth()->user()->id);
    ?>
    <input type="hidden" value="{{ $user }}" id="user_data"/>
    <input type="hidden" value="Regular User" id="domain_note"/>
    <script type="text/javascript" src="{{ URL::asset('ext/js/custom/processUser.js') }}"></script>
    @endif
@stop