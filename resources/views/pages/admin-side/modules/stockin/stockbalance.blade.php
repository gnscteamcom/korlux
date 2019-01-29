@extends('layouts.admin-side.default')


@section('title')
@parent
    Download Stock Balance
@stop


@section('content')
    
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Download Stock Balance</h1>
        </div>
    </div>


    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-info">
                <div class="panel-heading">
                    Please choose product to get the balance stock card.
                </div>
                <div class="panel-body">
                    
                    <form method="post" action="{{ URL::to('stockbalance') }}" class="form-horizontal">
                        {!! csrf_field() !!}
                                            
                        @if(Session::has('err'))
                        <div class="form-group">
                            <div class="col-sm-4 col-sm-offset-2">
                                <div class="form-group text-danger">
                                    {{ Session::get('err') }}
                                </div>
                            </div>
                        </div>
                        @endif


                        @if($errors->has('file'))
                        <div class="form-group">
                            <div class="col-sm-4 col-sm-offset-2">
                                <div class="form-group text-danger">
                                    {{ $errors->first('file') }}
                                </div>
                            </div>
                        </div>
                        @endif

                        
                        
                        <div class="row">
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
                        
                        <div class="row">
                            <div class="col-lg-6">
                                <label for="product">Product Name</label>
                                <select class="form-control" id="product" name="product" required>
                                    <option value="" disabled selected>Please Choose</option>
                                    @foreach($products as $product)
                                    <option value="{{ $product->id }}">{{ $product->product_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-lg-6">
                                <input type="submit" class="btn btn-default btn-success btn-block" value="Download" />
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
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>

<link rel="stylesheet" href="{{ URL::asset('ext/css/plugins/datepicker.css') }}">

<!--Datepicker-->
<script type="text/javascript" src="{{ URL::asset('ext/js/plugins/datepicker/bootstrap-datepicker.js') }}"></script>

<script type="text/javascript">
    $('#product').select2();
    
    //Bagian datepicker untuk range
    $('#date_range .input-daterange').datepicker({
        keyboardNavigation: false,
        forceParse: false,
        autoclose: true
    });

</script>
@stop