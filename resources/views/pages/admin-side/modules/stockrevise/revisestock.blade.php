@extends('layouts.admin-side.default')


@section('title')
@parent
    Revisi Stok
@stop


@section('content')
    
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Revisi Stok</h1>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


    
    @if(Session::has('err'))
    <div class="col-md-12 text-center" id="msg">
        <div class="alert alert-danger" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            {{ Session::get('err') }}
        </div>
    </div>
    @endif
        
        
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-info">
                <div class="panel-heading">
                    Input Data Anda
                </div>
                <div class="panel-body">
                    
                    <form method="post" action="{{ url('stockrevise/revise') }}">
                        
                        {!! csrf_field() !!}

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="product">Produk</label>
                                    <select name="product" id="product" style="width:100%;">
                                        <option value="" disabled selected>-- Please Choose Product --</option>
                                        @foreach($products as $product)
                                        <option value="{{ $product->id }}">{{ $product->product_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="deskripsi">Deskripsi Stok</label>
                                    <input type="text" name="deskripsi" id="deskripsi" class="form-control" readonly/>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="stok">Jumlah Perubahan Stok Utama</label>
                                    <input type="number" name="stok" id="stok" class="form-control" autofocus placeholder="Jumlah Perubahan Stok Utama" required/>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="catatan">Catatan</label>
                                    <input type="text" name="catatan" id="catatan" class="form-control" required/>
                                </div>
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
@include('includes.admin-side.validation')
@stop

@section('script')

    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>

    <script type="text/javascript">
        
        var token = $('input[name=_token]').val();
        
        $('#product').select2();
        
        $('#product').change(function(){
            console.log($(this).val());
            if($(this).val() > 0){
                $.post(
                        "/api/getstockdesc",
                {
                    token: token,
                    product_id: $(this).val()
                },
                function(data){
                    data = JSON.parse(data);
                    $('#deskripsi').val('Tidak ada Data');
                    if(data.result == 1){
                        $('#deskripsi').val(data.data);
                    }
                });
            }
        });
    </script>

@stop