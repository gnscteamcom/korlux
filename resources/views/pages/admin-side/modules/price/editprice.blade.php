@extends('layouts.admin-side.default')


@section('title')
@parent
    Ubah Data Harga
@stop


@section('content')
    
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Ubah Data Harga</h1>
        </div>
    </div>
        
        
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-info">
                <div class="panel-heading">
                    Silahkan Ubah Data
                </div>
                <div class="panel-body">
                    
                    <form method="post" action="{{ URL::to('updateprice') }}">
                        {!! csrf_field() !!}
                        
                        <input type="hidden" name="price_id" id="price_id" value="{{ $price->id }}" />
                        
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label for="product_name">Nama Produk</label>
                                            <input type="text" name="product_name" id="product_name" class="form-control" readonly="readonly" value="{{ $price->product->product_name }}" />
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label for="tanggal_saat_ini">Tanggal Berlaku Saat Ini</label>
                                            <input type="text" name="tanggal_saat_ini" id="tanggal_saat_ini" class="form-control" readonly="readonly" value="{{ date('d F Y', strtotime($price->valid_date)) }}" />
                                            <input type="hidden" class="form-control"  id="valid_date" name="valid_date" size="30" value="{{ $price->valid_date }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label for="regular_price">Harga Regular</label>
                                            <input type="number" name="regular_price" id="regular_price" class="form-control" placeholder="Harga Regular" value="{{ $price->regular_price }}" min="1" />
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label for="reseller_1">Harga Silver</label>
                                            <input type="number" name="reseller_1" id="reseller_1" placeholder="Harga Reseller 1" class="form-control" value="{{ $price->reseller_1 }}" min="1"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label for="reseller_2">Harga Gold</label>
                                            <input type="number" name="reseller_2" id="reseller_2" placeholder="Harga VIP" class="form-control" value="{{ $price->reseller_2 }}" min="1"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label for="vvip">Harga Platinum</label>
                                            <input type="number" name="vvip" id="vvip" placeholder="Harga VVIP" class="form-control" value="{{ $price->vvip }}" min="1"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label for="sale_price">Harga Sale</label>
                                            <input type="number" name="sale_price" id="sale_price" placeholder="Harga Sale" class="form-control" value="{{ $price->sale_price }}" min="0"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label for="valid_date">Mulai Berlaku</label>
                                            <input type="text" name="datepicker" id="datepicker" class="form-control" placeholder="Mulai Berlaku">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                @if(Session::has('err'))
                                    <div class="form-group text-danger">
                                        {{ Session::get('err') }}
                                    </div>
                                @endif
                                @if(!$errors->isEmpty())
                                    <div class="form-group text-danger">
                                        @if($errors->has('regular_price'))
                                            {{ $errors->first('regular_price') }}
                                        @endif
                                        @if($errors->has('reseller_1'))
                                            {{ $errors->first('reseller_1') }}
                                        @endif
                                        @if($errors->has('reseller_2'))
                                            {{ $errors->first('reseller_2') }}
                                        @endif
                                        @if($errors->has('sale_price'))
                                            {{ $errors->first('sale_price') }}
                                        @endif
                                        @if($errors->has('valid_date'))
                                            {{ $errors->first('valid_date') }}
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <input type="submit" value="Ubah" class="btn btn-default btn-success btn-block" />
                            </div>
                        </div>
                        
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('script')
  <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
  <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<script type="text/javascript">
  $(function() {
    // $( "#datepicker" ).datepicker();
    $( "#datepicker" ).datepicker({
      altField: "#valid_date",
      altFormat: "yy-mm-dd"
    });
    $( "#datepicker" ).datepicker( "option", "dateFormat", "d MM yy" );
  });
</script>

@stop