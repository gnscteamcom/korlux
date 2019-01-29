@extends('layouts.front-end.layouts')


@section('content')

<div class="container-fluid cart-list">
    
    <div class="row">
        <div class="col-sm-3"></div>
        <div class="col-sm-6">
            <form action="{{ URL::to('addfrom') }}" method="post" class="myform">
                {!! csrf_field() !!}

                <h2 class="text-center">Tambah Asal Pengiriman Baru</h2>
                <div class="form-group">
                    <label class="control-label" for="nama_pengiriman">Nama Pengiriman</label>
                    <input name="nama_pengiriman" id="nama_pengiriman" placeholder="Nama Pengiriman" class="form-control" type="text" required="required" maxlength="64">
                    @if($errors->first('nama_pengiriman'))
                    <span class="text-danger">
                        {{ $errors->first('nama_pengiriman') }}
                    </span>
                    @endif
                </div>
                <div class="form-group">
                    <label class="control-label" for="dikirim_oleh">Dikirim oleh</label>
                    <input name="dikirim_oleh" id="dikirim_oleh" placeholder="Dikirim oleh" class="form-control" type="text" required="required" maxlength="32">
                    @if($errors->first('dikirim_oleh'))
                    <span class="text-danger">
                        {{ $errors->first('dikirim_oleh') }}
                    </span>
                    @endif
                </div>
                <div class="form-group">
                    <label class="control-label" for="hp_pengirim">Nomor HP Pengirim</label>
                    <input name="hp_pengirim" id="hp_pengirim" placeholder="HP Pengirim" class="form-control" type="text" required="required" maxlength="16">
                    @if($errors->first('hp_pengirim'))
                    <span class="text-danger">
                        {{ $errors->first('hp_pengirim') }}
                    </span>
                    @endif
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <a href="{{ URL::to('checkout') }}" class="btn btn-default col-md-5 margin-top-10 pull-left">Kembali</a>
                        <button name="submit" type="submit" class="btn btn-primary col-md-5 margin-top-10 pull-right">Simpan Asal Pengiriman</button>
                    </div>
                </div>
                <div class="row margin-top-20">
                </div>
            </form>
        </div>
    </div>

</div>


@stop