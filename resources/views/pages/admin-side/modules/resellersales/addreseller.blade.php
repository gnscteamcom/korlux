@extends('layouts.admin-side.default')


@section('title')
@parent
    Insert Bank
@stop


@section('content')

    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Tambah Reseller</h1>
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
                    Input data reseller anda
                </div>
                <div class="panel-body">

                    <form method="post" action="{{ URL::to('resellersales/addreseller') }}">
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
                                    <label for="nama_reseller">Nama Reseller</label>
                                    <input type="text" name="nama_reseller" class="form-control" autofocus="autofocus" placeholder="Nama Reseller" required="required"/>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="username">Username</label>
                                    <input type="text" name="username" class="form-control" placeholder="Username"  required="required"/>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="password">Password</label>
                                    <input type="text" name="password" class="form-control" placeholder="Username"  required="required" value="password" readonly="readonly"/>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="hp">HP</label>
                                    <input type="text" name="hp" id="hp" class="form-control" placeholder="HP"  required="required"/>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" name="email" id="email" class="form-control" placeholder="Email"  required="required"/>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="email">Tipe Reseller</label>
                                    <select name="tipe_reseller" class="form-control" required style="width: 100%;">
                                        <option value="" disabled selected> -- Pilih Tipe Reseller --</option>
                                        @foreach($statuses as $status)
                                        <option value="{{ $status->id }}"> {{ $status->status }} </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-lg-6">
                                <input type="submit" class="btn btn-default btn-success btn-block" value="Tambah Reseller"/>
                            </div>
                        </div>

                        <br />

                        <div class="row">
                          <div class="col-lg-6">
                            <a href="{{ url('resellersales') }}"><input type="button" class="btn btn-default btn-primary btn-block" value="Buat Penjualan Reseller"/></a>
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
