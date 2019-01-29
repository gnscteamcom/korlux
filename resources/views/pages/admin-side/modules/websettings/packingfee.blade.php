@extends('layouts.admin-side.default')


@section('title')
@parent
Packing Fee
@stop


@section('content')

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Pengaturan Packing Fee</h1>
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

@if(Session::has('err'))
<div class="row">
    <div class="col-lg-12">
        <h3 class="text-danger">
            {{ Session::get('err') }}
        </h3>
    </div>
</div>
@endif

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
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <h2>Packing Fee</h2>
            </div>
            <div class="panel-body">


                <form method="post" action="{{ url('packingfee/update') }}">
                    {!! csrf_field() !!}

                    <div class="row">
                        <div class="col-lg-8">
                            <div class="form-group">
                                <label for="regular_minimum_nominal">Nominal Harga Minimum Reguler</label>
                                <div class="row">
                                    <div class="col-lg-1">
                                        @if(!$packing_fee)
                                        <input type="checkbox" name="is_active" style="width:20px; height: 20px;" value="1"/>
                                        @else
                                        @if($packing_fee->is_active)
                                        <input type="checkbox" name="is_active" checked style="width:20px; height: 20px;" value="1"/>
                                        @else
                                        <input type="checkbox" name="is_active" style="width:20px; height: 20px;" value="1"/>
                                        @endif
                                        @endif
                                    </div>
                                    <div class="col-lg-6">
                                        @if(!$packing_fee)
                                        <input type="number" name="minimal_nominal" class="form-control" placeholder="Minimum Nominal Bebas Packing Fee" required min="0"/>
                                        @else
                                        <input type="number" name="minimal_nominal" class="form-control" placeholder="Minimum Nominal Bebas Packing Fee" required min="0" value="{{ $packing_fee->minimal_nominal }}"/>
                                        @endif
                                    </div>
                                    <div class="col-lg-5">
                                        @if(!$packing_fee)
                                        <input type="number" name="packing_fee" class="form-control" placeholder="Packing Fee" required min="0"/>
                                        @else
                                        <input type="number" name="packing_fee" class="form-control" placeholder="Packing Fee" required min="0" value="{{ $packing_fee->packing_fee }}"/>
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


<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <h2>Packing Fee Kargo</h2>
            </div>
            <div class="panel-body">


                <form method="post" action="{{ url('packingfeecargo/update') }}">
                    {!! csrf_field() !!}

                    <div class="row">
                        <div class="col-lg-8">
                            <div class="form-group">
                                <label for="packing_fee_cargo">Packing Fee Kargo (min. 5 kg)</label>
                                <div class="row">
                                    <div class="col-lg-5">
                                        @if(!$packing_fee_cargo)
                                        <input type="number" name="packing_fee_cargo" class="form-control" placeholder="Packing Fee Kargo" required min="0"/>
                                        @else
                                        <input type="number" name="packing_fee_cargo" class="form-control" placeholder="Packing Fee Kargo" required min="0" value="{{ $packing_fee_cargo->packing_fee }}"/>
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

</div>
@stop