@extends('layouts.admin-side.default')


@section('title')
@parent
    Reseller Configs
@stop


@section('content')

    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Ubah Konfigurasi Reseller</h1>
        </div>
    </div>

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


    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-info">
                <div class="panel-heading">
                    Ubah data anda
                </div>
                <div class="panel-body">

                    <form method="post" action="{{ URL::to('resellerconfig') }}">
                        {!! csrf_field() !!}

                        @if($errors->any())
                        <div class="row">
                            <div class="col-lg-6">
                              {!! implode('', $errors->all('<div>:message</div>')) !!}
                            </div>
                        </div>
                        @endif


                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="nama_bank">Hari Upgrade Silver</label>
                                    <input type="number" name="silver_upgrade_days" class="form-control" autofocus="autofocus" value="{{ $resellerconfig->silver_upgrade_days }}" placeholder="Hari Upgrade Silver" required="required"/>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="rekening_bank">Hari Downgrade Silver</label>
                                    <input type="number" name="silver_downgrade_days" class="form-control" value="{{ $resellerconfig->silver_downgrade_days }}" placeholder="Hari Downgrade Silver" required="required"/>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="rekening_bank">Silver Minimum Nominal Upgrade</label>
                                    <input type="number" name="silver_min_upgrade" class="form-control" value="{{ $resellerconfig->silver_min_upgrade }}" placeholder="Silver Minimum Nominal Upgrade" required="required"/>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="rekening_bank">Silver Minimum Nominal Downgrade</label>
                                    <input type="number" name="silver_min_downgrade" class="form-control" value="{{ $resellerconfig->silver_min_downgrade }}" placeholder="Silver Minimum Nominal Downgrade" required="required"/>
                                </div>
                            </div>
                        </div>

                        <hr />

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="nama_bank">Hari Upgrade Gold</label>
                                    <input type="number" name="gold_upgrade_days" class="form-control" value="{{ $resellerconfig->gold_upgrade_days }}" placeholder="Hari Upgrade Gold" required="required"/>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="rekening_bank">Hari Downgrade Gold</label>
                                    <input type="number" name="gold_downgrade_days" class="form-control" value="{{ $resellerconfig->gold_downgrade_days }}" placeholder="Hari Downgrade Gold" required="required"/>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="rekening_bank">Gold Minimum Nominal Upgrade</label>
                                    <input type="number" name="gold_min_upgrade" class="form-control" value="{{ $resellerconfig->gold_min_upgrade }}" placeholder="Gold Minimum Nominal Upgrade" required="required"/>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="rekening_bank">Gold Minimum Nominal Downgrade</label>
                                    <input type="number" name="gold_min_downgrade" class="form-control" value="{{ $resellerconfig->gold_min_downgrade }}" placeholder="Gold Minimum Nominal Downgrade" required="required"/>
                                </div>
                            </div>
                        </div>

                        <hr />

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="nama_bank">Hari Upgrade Platinum</label>
                                    <input type="number" name="platinum_upgrade_days" class="form-control" value="{{ $resellerconfig->platinum_upgrade_days }}" placeholder="Hari Upgrade Platinum" required="required"/>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="rekening_bank">Hari Downgrade Platinum</label>
                                    <input type="number" name="platinum_downgrade_days" class="form-control" value="{{ $resellerconfig->platinum_downgrade_days }}" placeholder="Hari Downgrade Platinum" required="required"/>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="rekening_bank">Platinum Minimum Nominal Upgrade</label>
                                    <input type="number" name="platinum_min_upgrade" class="form-control" value="{{ $resellerconfig->platinum_min_upgrade }}" placeholder="Platinum Minimum Nominal Upgrade" required="required"/>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="rekening_bank">Platinum Minimum Nominal Downgrade</label>
                                    <input type="number" name="platinum_min_downgrade" class="form-control" value="{{ $resellerconfig->platinum_min_downgrade }}" placeholder="Platinum Minimum Nominal Downgrade" required="required"/>
                                </div>
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-lg-6">
                                <input type="submit" class="btn btn-default btn-success btn-block" value="Update"/>
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
