@extends('layouts.admin-side.default')


@section('title')
@parent
    Update Bank
@stop


@section('content')
    
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Perbarui Bank</h1>
        </div>
    </div>
        
        
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-info">
                <div class="panel-heading">
                    Input data anda
                </div>
                <div class="panel-body">
                    
                    <form method="post" action="{{ URL::to('updatebank') }}">
                        {!! csrf_field() !!}
                        
                        <input type="hidden" name="bank_id" id="bank_id" value="{{ $bank->id }}" />
                        
                        
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="nama_bank">Nama Bank</label>
                                    <input type="text" name="nama_bank" id="nama_bank" class="form-control" autofocus="autofocus" placeholder="Bank Name" value="{{ $bank->bank_name }}"/>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="rekening_bank">Nomor Rekening</label>
                                    <input type="text" name="rekening_bank" id="rekening_bank" class="form-control" placeholder="Account Number" value="{{ $bank->bank_account }}"/>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="nama_rekening">Nama Rekening</label>
                                    <input type="text" name="nama_rekening" id="nama_rekening" class="form-control" placeholder="Account Name" value="{{ $bank->bank_account_name }}"/>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                @if(Session::has('msg'))
                                    <div class="form-group text-success">
                                        {{ Session::get('msg') }}
                                    </div>
                                @endif
                           </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <input type="submit" class="btn btn-default btn-success btn-block" value="Perbarui"/>
                            </div>
                        </div>
                        
                        
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@stop