@extends('layouts.admin-side.default')


@section('title')
@parent
Free Sample
@stop


@section('content')

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Pengaturan Sample</h1>
    </div>
</div>

@if(Session::has('msg'))
<div class="row">
    <div class="col-lg-12">
        <h3 class="text-success">
            {{ Session::get('msg') }}
        </h3>
    </div>
</div>
@endif


<!--Bagian data kontak-->    
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <h2>Free Sample</h2>
            </div>
            <div class="panel-body">


                <form method="post" action="{{ URL::to('updatefreesample') }}">
                    {!! csrf_field() !!}
                    @if (count($errors) > 0)
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                    @endif

                    <div class="row">
                        <div class="col-lg-8">
                            <div class="form-group">
                                <label for="regular_minimum_nominal">Nominal Harga Minimum Reguler</label>
                                <div class="row">
                                    <div class="col-lg-1">
                                        @if(!$freesample)
                                        <input type="checkbox" id="active_regular" name="active_regular" />
                                        @else
                                            @if($freesample->active_regular)
                                            <input type="checkbox" id="active_regular" name="active_regular" checked/>
                                            @else
                                            <input type="checkbox" id="active_regular" name="active_regular" />
                                            @endif
                                        @endif
                                    </div>
                                    <div class="col-lg-6">
                                        @if(!$freesample)
                                            <input type="number" name="regular_minimum_nominal" id="regular_minimum_nominal" class="form-control" placeholder="Nominal Minimum Reguler" required min="0"/>
                                        @else
                                        <input type="number" name="regular_minimum_nominal" id="regular_minimum_nominal" class="form-control" placeholder="Nominal Minimum Reguler" required min="0" value="{{ $freesample->regular_minimum_nominal }}"/>
                                        @endif
                                    </div>
                                    <div class="col-lg-5">
                                        @if(!$freesample)
                                        <input type="checkbox" id="tick_regular" name="tick_regular" /> Akumulatif
                                        @else
                                            @if($freesample->regular_accumulative)
                                            <input type="checkbox" id="tick_regular" name="tick_regular" checked/> Akumulatif
                                            @else
                                            <input type="checkbox" id="tick_regular" name="tick_regular" /> Akumulatif
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-8">
                            <div class="form-group">
                                <label for="silver_minimum_nominal">Nominal Harga Minimum Silver</label>
                                <div class="row">
                                    <div class="col-lg-1">
                                        @if(!$freesample)
                                        <input type="checkbox" id="active_silver" name="active_silver" />
                                        @else
                                            @if($freesample->active_silver)
                                            <input type="checkbox" id="active_silver" name="active_silver" checked/>
                                            @else
                                            <input type="checkbox" id="active_silver" name="active_silver" />
                                            @endif
                                        @endif
                                    </div>
                                    <div class="col-lg-6">
                                        @if(!$freesample)
                                        <input type="number" class="form-control" name="silver_minimum_nominal" id="silver_minimum_nominal" placeholder="Nominal Minimum Silver" required min="0"/>
                                        @else
                                        <input type="number" class="form-control" name="silver_minimum_nominal" id="silver_minimum_nominal" placeholder="Nominal Minimum Silver" required min="0" value="{{ $freesample->silver_minimum_nominal }}"/>
                                        @endif
                                    </div>
                                    <div class="col-lg-5">
                                        @if(!$freesample)
                                        <input type="checkbox" id="tick_silver" name="tick_silver" /> Akumulatif
                                        @else
                                            @if($freesample->silver_accumulative)
                                            <input type="checkbox" id="tick_silver" name="tick_silver" checked/> Akumulatif
                                            @else
                                            <input type="checkbox" id="tick_silver" name="tick_silver" /> Akumulatif
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-8">
                            <div class="form-group">
                                <label for="gold_minimum_nominal">Nominal Harga Minimum Gold</label>
                                <div class="row">
                                    <div class="col-lg-1">
                                        @if(!$freesample)
                                        <input type="checkbox" id="active_gold" name="active_gold" />
                                        @else
                                            @if($freesample->active_gold)
                                            <input type="checkbox" id="active_gold" name="active_gold" checked/>
                                            @else
                                            <input type="checkbox" id="active_gold" name="active_gold" />
                                            @endif
                                        @endif
                                    </div>
                                    <div class="col-lg-6">
                                        @if(!$freesample)
                                        <input type="number" class="form-control" name="gold_minimum_nominal" id="gold_minimum_nominal" placeholder="Nominal Minimum Gold" required min="0"/>
                                        @else
                                        <input type="number" class="form-control" name="gold_minimum_nominal" id="gold_minimum_nominal" placeholder="Nominal Minimum Gold" required min="0" value="{{ $freesample->gold_minimum_nominal }}"/>
                                        @endif
                                    </div>
                                    <div class="col-lg-5">
                                        @if(!$freesample)
                                        <input type="checkbox" id="tick_gold" name="tick_gold" /> Akumulatif
                                        @else
                                            @if($freesample->gold_accumulative)
                                            <input type="checkbox" id="tick_gold" name="tick_gold" checked/> Akumulatif
                                            @else
                                            <input type="checkbox" id="tick_gold" name="tick_gold" /> Akumulatif
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-8">
                            <div class="form-group">
                                <label for="platinum_minimum_nominal">Nominal Harga Minimum Platinum</label>
                                <div class="row">
                                    <div class="col-lg-1">
                                        @if(!$freesample)
                                        <input type="checkbox" id="active_platinum" name="active_platinum" />
                                        @else
                                            @if($freesample->active_platinum)
                                            <input type="checkbox" id="active_platinum" name="active_platinum" checked/>
                                            @else
                                            <input type="checkbox" id="active_platinum" name="active_platinum" />
                                            @endif
                                        @endif
                                    </div>
                                    <div class="col-lg-6">
                                        @if(!$freesample)
                                        <input type="number" class="form-control" name="platinum_minimum_nominal" id="platinum_minimum_nominal" placeholder="Nominal Minimum Platinum" required min="0"/>
                                        @else
                                        <input type="number" class="form-control" name="platinum_minimum_nominal" id="platinum_minimum_nominal" placeholder="Nominal Minimum Platinum" required min="0" value="{{ $freesample->platinum_minimum_nominal }}"/>
                                        @endif
                                    </div>
                                    <div class="col-lg-5">
                                        @if(!$freesample)
                                        <input type="checkbox" id="tick_platinum" name="tick_platinum" /> Akumulatif
                                        @else
                                            @if($freesample->platinum_accumulative)
                                            <input type="checkbox" id="tick_platinum" name="tick_platinum" checked/> Akumulatif
                                            @else
                                            <input type="checkbox" id="tick_platinum" name="tick_platinum" /> Akumulatif
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <input type="submit" value="Perbarui" class="btn btn-default btn-success btn-block"/>
                        </div>
                    </div>

                </form>


            </div>
        </div>
    </div>

</div>
<hr>

</div>
@stop