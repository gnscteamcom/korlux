@extends('layouts.admin-side.default')


@section('title')
@parent
    Insert Bank
@stop


@section('content')
    
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Tambah Bank</h1>
        </div>
    </div>
        
        
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-info">
                <div class="panel-heading">
                    Input data anda
                </div>
                <div class="panel-body">
                    
                    <form method="post" action="{{ URL::to('addbank') }}">
                        {!! csrf_field() !!}
                        
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="nama_bank">Nama Bank</label>
                                    <input type="text" name="nama_bank" id="nama_bank" class="form-control" autofocus="autofocus" placeholder="Bank Name" required="required"/>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="rekening_bank">Nomor Rekening</label>
                                    <input type="text" name="rekening_bank" id="rekening_bank" class="form-control" placeholder="Account Number"  required="required"/>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="nama_rekening">Nama Rekening</label>
                                    <input type="text" name="nama_rekening" id="nama_rekening" class="form-control" placeholder="Account Name"  required="required"/>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group text-danger">
                                    @if(!$errors->isEmpty())
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="form-group text-danger">
                                                <div class="alert alert-danger alert-dismissible" role="alert">
                                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                    @if($errors->has('nama_bank'))
                                                        {{ $errors->first('nama_bank') }}
                                                    @endif
                                                    @if($errors->has('rekening_bank'))
                                                        {{ $errors->first('rekening_bank') }}
                                                    @endif
                                                    @if($errors->has('nama_rekening'))
                                                        {{ $errors->first('nama_rekening') }}
                                                    @endif
                                                    {{ Session::get('err') }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <input type="submit" class="btn btn-default btn-success btn-block" value="Tambah"/>
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