@extends('layouts.front-end.layouts')


@section('content')

@if($term)

<div class="container">
    <div class="row"> 
        <div class="col-sm-10 col-sm-offset-1">
            <div class="box">
                <ul id = "myTab" class = "nav nav-tabs">
                   <li class = "active col-md-4 text-center">
                        <a href = "#buy" data-toggle = "tab">
                            <i class="fa fa-home"></i><br> Cara Membeli
                        </a>
                   </li>
                   <li class = "col-md-4 text-center">
                        <a href = "#snk" data-toggle = "tab">
                            <i class="fa fa-newspaper-o"></i><br> Syarat & Ketentuan
                        </a>
                    </li>
                   <li class = "col-md-4 text-center">
                        <a href = "#faq" data-toggle = "tab">
                            <i class="fa fa-question"></i><br> FAQ
                        </a>
                    </li>
                </ul>
                <div id = "myTabContent" class = "tab-content">
                    <div class = "tab-pane fade in active" id = "buy">
                        <div class="content">
                            <div class="row margin-top-20"> 
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-sm-6 col-sm-offset-3">
                                            <h2 class="text-center heading">Cara Membeli</h2>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @if(strlen(strip_tags($term->howtobuy)) > 0)
                            <div class="row">
                                <div class="col-md-10 col-md-offset-1">
                                    <blockquote>    
                                        <p>
                                            {!! $term->howtobuy !!}
                                        </p>
                                    </blockquote>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                    <div class = "tab-pane fade" id = "snk">
                        <div class="content">
                            <div class="row margin-top-20"> 
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <h2 class="text-center heading" >Syarat dan Ketentuan</h2>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @if($term)
                            <div class="row">
                                <div class="col-md-10 col-md-offset-1">
                                    @if(strlen(strip_tags($term->pricing_policy)) > 0)
                                    <h3 class="text-center">Kebijakan Harga</h3>
                                    <blockquote>    
                                        <p>
                                           {!! $term->pricing_policy !!} 
                                        </p>
                                    </blockquote>    
                                    @endif
                                </div>
                                <div class="col-md-10 col-md-offset-1">
                                    @if(strlen(strip_tags($term->payment)) > 0)
                                    <h3 class="text-center">Pembayaran</h3>
                                    <blockquote>    
                                        <p>
                                           {!! $term->payment !!} 
                                        </p>
                                    </blockquote>    
                                    @endif
                                </div>
                                    
                                <div class="col-md-10 col-md-offset-1">
                                    @if(strlen(strip_tags($term->order)) > 0)
                                    <h3 class="text-center">Pemesanan</h3>
                                    <blockquote>    
                                        <p>
                                           {!! $term->order !!} 
                                        </p>
                                    </blockquote>    
                                    @endif
                                </div>
                                <div class="col-md-10 col-md-offset-1">
                                    @if(strlen(strip_tags($term->payment_confirmation)) > 0)
                                    <h3 class="text-center">Konfirmasi Pembayaran</h3>
                                    <blockquote>    
                                        <p>
                                           {!! $term->payment_confirmation !!} 
                                        </p>
                                    </blockquote>    
                                    @endif
                                </div>
                                    
                                    
                                <div class="col-md-10 col-md-offset-1">
                                    @if(strlen(strip_tags($term->shipment)) > 0)
                                    <h3 class="text-center">Pengiriman</h3>
                                    <blockquote>    
                                        <p>
                                           {!! $term->shipment !!} 
                                        </p>
                                    </blockquote>    
                                    @endif
                                </div>
                                    
                                    
                                <div class="col-md-10 col-md-offset-1">
                                    @if(strlen(strip_tags($term->return)) > 0)
                                    <h3 class="text-center">Pengembalian</h3>
                                    <blockquote>    
                                        <p>
                                           {!! $term->return !!} 
                                        </p>
                                    </blockquote>    
                                    @endif
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                    <div class = "tab-pane fade" id = "faq">
                        <div class="content">
                            <div class="row margin-top-20"> 
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-sm-6 col-sm-offset-3">
                                            <h2 class="text-center heading">FAQ</h2>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @if(strlen(strip_tags($term->faq)) > 0)
                            <div class="row">
                                <div class="col-md-10 col-md-offset-1">
                                    <blockquote>    
                                        <p>
                                            {!! $term->faq !!}
                                        </p>
                                    </blockquote>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
    
@endif


@stop