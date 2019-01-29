@extends('layouts.admin-side.default')


@section('title')
@parent
    Transfer Stok
@stop


@section('content')
    
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Transfer Stok</h1>
        </div>
    </div>
        
        
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-info">
                <div class="panel-heading">
                    Input Transfer Stok Anda
                </div>
                <div class="panel-body">
                    
                    <form method="post" action="{{ url('stocktransfer') }}">
                        {!! csrf_field() !!}
                        
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
                                    <div class="alert alert-danger alert-dismissible" role="alert">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                            {!! '<b>' . Session::get('err') . '</b>' !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                        
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="produk">Produk</label>
                                    <select name="produk" id="produk" class="form-control select2">
                                        <option value="" disabled selected> -- Select Product -- </option>
                                        @foreach($products as $product)
                                        <option value="{{ $product->id }}" data-qty="{{ $product->qty }}" data-reserved-qty="{{ $product->reserved_qty }}"> {{ $product->product_name }} </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="stok_utama">Stok Utama</label>
                                    <input type="number" name="stok_utama" class="form-control" readonly id="stok_utama"/>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="stok_cadangan">Stok Cadangan</label>
                                    <input type="number" name="stok_cadangan" class="form-control" readonly id="stok_cadangan"/>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="jumlah">Jumlah Transfer ke Stok Utama</label>
                                    <input type="number" name="jumlah" id="jumlah" class="form-control" required min="0"/>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-lg-6">
                                @if(!$errors->isEmpty())
                                <div class="form-group text-danger">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="form-group text-danger">
                                                <div class="alert alert-danger alert-dismissible" role="alert">
                                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                @if($errors->has('produk'))
                                                    {{ $errors->first('produk') }}
                                                @endif
                                                @if($errors->has('jumlah'))
                                                    {{ $errors->first('jumlah') }}
                                                @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <input type="submit" value="Transfer" class="btn btn-default btn-success btn-block" />
                            </div>
                        </div>
                        
                    </form>
                    
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@include('includes.admin-side.validation')


@section('script')

    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>

    <script type="text/javascript">
        $('.select2').select2();
        
        $('#produk').change(function(){
            var qty = $('#produk option:selected').attr('data-qty');
            var reserved_qty = $('#produk option:selected').attr('data-reserved-qty');
            
            $('#stok_utama').val(qty);
            $('#stok_cadangan').val(reserved_qty);
            $('#jumlah').attr('max', reserved_qty);
        });
    </script>
@stop