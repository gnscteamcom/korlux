@extends('layouts.admin-side.default')


@section('title')
@parent
    Filter Report
@stop


@section('content')
    
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Saring Laporan</h1>
        </div>
    </div>
        
        
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-info">
                <div class="panel-heading">
                    Saring Laporan
                </div>
                <div class="panel-body">
                    
                    <form method="post" action="{{ URL::to('exportlist') }}">
                        {!! csrf_field() !!}
                        
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="filter_by">Saring berdasarkan</label>
                                    <select class="form-control" id="filter_by" name="filter_by">
                                        <option value="">-- Silahkan Pilih --</option>
                                        <!--<option value="1">Master Modal</option>-->
                                        <option value="2">Master Poin Loyalty</option>
                                        <option value="3">Master Order</option>
                                        <option value="4">Master Rincian Order</option>
                                        <option value="5">Master Riwayat Poin</option>
                                        <option value="6">Master Harga</option>
                                        <option value="7">Master Produk</option>
                                        <option value="8">Master Stok Masuk</option>
                                        <option value="9">Master Pengguna</option>
                                        <option value="10">Laporan Order Accepted</option>
                                        <option value="11">Price List Admin</option>
                                        <option value="12">History Booking</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row" id="product_list">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="filter_by">Produk</label>
                                    <select class="form-control" name="product" id="product">
                                        <option value="" selected disabled>-- Produk --</option>
                                        @foreach($products as $product)
                                        <option value="{{ $product->id }}"> {{ $product->product_name }} </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row" id="date_row">
                            <div class="col-lg-6">
                                <div class="form-group" id="date_range">
                                    <label for="filter_by">Jarak Tanggal</label>
                                    <div class="input-group input-daterange" id="datepicker">
                                        <span class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </span>
                                        <input type="text" id="date_start" name="date_start" class="input-sm form-control" readonly value="{{ date('m/d/Y') }}" />
                                        <span class="input-group-addon">ke</span>
                                        <input type="text" id="date_end" name="date_end" class="input-sm form-control" readonly value="{{ date('m/d/Y') }}" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row" id="onedate_row">
                            <div class="col-lg-6">
                                <div class="form-group" id="date_one">
                                    <label for="filter_by">Tanggal</label>
                                    <div class="input-group date" id="datepicker">
                                        <span class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </span>
                                        <input type="text" id="date_one" name="date_one" class="input-sm form-control" readonly value="{{ date('m/d/Y') }}" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        
                        <div class="row">
                            <div class="col-lg-6">
                                <input type="submit" value="Ekspor Data" class="btn btn-default btn-success btn-block"/>
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
    <link rel="stylesheet" href="{{ URL::asset('ext/css/plugins/datepicker.css') }}">

    <!--Datepicker-->
    <script type="text/javascript" src="{{ URL::asset('ext/js/plugins/datepicker/bootstrap-datepicker.js') }}"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
    
    <script>
        $('#product').select2();
        hideAll();
        
        //Bagian datepicker untuk range
        $('#date_range .input-daterange').datepicker({
            keyboardNavigation: false,
            forceParse: false,
            autoclose: true
        });
        $('#date_one .date').datepicker({
            keyboardNavigation: false,
            forceParse: false,
            autoclose: true
        });
        
        
        $('#filter_by').change(function(){
            
            var value = parseInt($('#filter_by').val());
            hideAll();
            
            switch(value){
                case 1: 
                    break;
                case 2: 
                    break;
                case 3: 
                    showDate();
                    break;
                case 4: 
                    showDate();
                    break;
                case 5: 
                    showDate();
                    break;
                case 6: 
                    showDate();
                    break;
                case 7: 
                    break;
                case 8: 
                    showDate();
                    break;
                case 9: 
                    break;
                case 10:
                    showOneDate();
                    break;
                case 11:
                    break;
                case 12:
                    showProduct();
                    break;
            }
            
        });
        
        function hideAll(){
            $('#onedate_row').hide();
            $('#date_row').hide();
            $('#product_list').hide();
        }
        
        function showDate(){
            $('#onedate_row').hide();
            $('#date_row').show();
        }
        
        function showOneDate(){
            $('#date_row').hide();
            $('#onedate_row').show();
        }
        
        function showProduct(){
            $('#product_list').show();
        }
        
        
    </script>

@stop