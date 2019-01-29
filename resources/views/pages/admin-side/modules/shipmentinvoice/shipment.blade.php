@extends('layouts.admin-side.default')


@section('title')
@parent
    Shipment
@stop


@section('content')
    
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Pengiriman</h1>
        </div>
    </div>
        
        
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-info">
                <div class="panel-heading">
                    Masukan Faktur Pengiriman
                </div>
                <div class="panel-body">
                    
                    <form method="post" action="{{ URL::to('shipment') }}">
                        
                        {!! csrf_field() !!}
                        
                        <input type="hidden" value="{{ $order->id }}" name="order_id" />

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="shipment_invoice">Faktur Pengiriman</label>
                                    <input type="text" name="shipment_invoice" id="shipment_invoice" class="form-control" autofocus="autofocus" placeholder="Shipment Invoice" maxlength="32" required="required" value="{{ $order->shipment_invoice }}"/>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group text-danger">
                                    @if(!$errors->isEmpty())
                                        {{ $errors->first('shipment_invoice') }}
                                    @endif
                                    @if(Session::has('err'))
                                        {{ Session::get('err') }}
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <input type="submit" value="Tambah" class="btn btn-default btn-success btn-block" />
                            </div>
                        </div>
                        
                    </form>
                    
                </div>
            </div>
        </div>
    </div>
</div>
@stop