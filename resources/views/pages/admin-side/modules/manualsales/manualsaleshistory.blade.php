@extends('layouts.admin-side.default')


@section('title')
@parent
    Histori Manual Sales
@stop


@section('content')
    
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Daftar Manual Sales</h1>
        </div>
    </div>


        
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-info">
                <div class="panel-heading">
                    Daftar Manual Sales
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <div class="row">
                            <div class="col-md-6 margin-bottom-20">
                                <form method="post" action="{{ url('search/searchmanualsales') }}">
                                    {!! csrf_field() !!}
                                    <div class="col-lg-12">
                                        <div class="input-group">
                                            <input type="text" name="search" id="search" class="form-control" required="required" autofocus="autofocus" placeholder="Nomor Order / Marketplace" />
                                            <span class="input-group-btn">
                                                <button class="btn btn-default" type="submit">Cari</button>
                                            </span>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Tanggal</th>
                                    <th>Nomor Invoice</th>
                                    <th>Total Bayar</th>
                                    <th>Ongkos Kirim</th>
                                    <th>Catatan</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            
                            <tbody>
                                
                                <?php
                                    $total_order = 0;
                                ?>
                                @foreach($orderheaders as $orderheader)
                                <?php 
                                    $grandtotal = $orderheader->grand_total + $orderheader->shipment_cost + $orderheader->unique_nominal
                                            +$orderheader->insurance_fee - $orderheader->discount_coupon - $orderheader->discount_point;
                                    
                                    $status_resi = $orderheader->status->status;
                                    if($orderheader->shipment_invoice != null){
                                        $status_resi .= '<br />' . $orderheader->shipment_invoice;
                                    }
                                ?>
                                <tr>
                                    <td>
                                        <a href="{{ URL::to('vieworderdetail/' . $orderheader->id) }}" title="Order Detail"><i class="fa fa-2x fa-fw fa-info-circle"></i></a>                                        
                                    </td>
                                    <td>
                                        {!! date('d F Y', strtotime($orderheader->created_at)) !!}
                                    </td>
                                    <td>
                                        {!! $orderheader->invoicenumber !!}
                                        @if($orderheader->ordermarketplace)
                                        <br>{!! $orderheader->ordermarketplace->marketplace_invoice !!}
                                        @endif
                                    </td>
                                    <td>
                                        {!! 'Rp. ' . number_format($grandtotal, 2, ',', '.') !!}
                                    </td>
                                    <td>
                                        {!! 'Rp. ' . number_format($orderheader->shipment_cost, 2, ',', '.') !!}
                                    </td>
                                    <td>
                                        {!! $orderheader->note !!}
                                    </td>
                                    <td>
                                        {!! $status_resi !!}
                                    </td>
                                </tr>
                                
                                <?php 
                                    $total_order += $grandtotal;
                                ?>
                                @endforeach
                                
                            </tbody>
                        </table>
                        {!! $orderheaders->links(); !!}
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
    
</div>
@stop