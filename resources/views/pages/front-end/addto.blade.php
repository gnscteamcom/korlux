@extends('layouts.front-end.layouts')


@section('content')

<div class="container-fluid cart-list">
    
    <div class="row">
        <div class="col-sm-3"></div>
        <div class="col-sm-6">
            <form action="{{ URL::to('addto') }}" method="post" class="myform">
                {!! csrf_field() !!}

                <h2 class="text-center">Tambah Alamat Pengiriman Baru</h2>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label class="control-label" for="nama_alamat">Nama Alamat *</label>
                            <input name="nama_alamat" id="nama_alamat" placeholder="Nama Alamat" class="form-control" type="text" value="{{ old('nama_alamat') }}" autofocus required="required">
                            @if($errors->first('nama_alamat'))
                            <span class="text-danger">
                                {{ $errors->first('nama_alamat') }}
                            </span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label class="control-label" for="nama_depan">Nama Depan *</label>
                            <input name="nama_depan" id="nama_depan" placeholder="Nama Depan" class="form-control" type="text" value="{{ old('nama_depan') }}" required="required">
                            @if($errors->first('nama_depan'))
                            <span class="text-danger">
                                {{ $errors->first('nama_depan') }}
                            </span>
                            @endif
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label class="control-label" for="nama_belakang">Nama Belakang *</label>
                            <input name="nama_belakang" id="nama_belakang" placeholder="Nama Belakang" class="form-control" type="text" value="{{ old('nama_belakang') }}" required="required">
                            @if($errors->first('nama_belakang'))
                            <span class="text-danger">
                                {{ $errors->first('nama_belakang') }}
                            </span>
                            @endif
                            <span id="helpBlock" class="control-label text-danger">masukan kembali nama depan jika tidak memiliki nama belakang.</span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label" for="alamat">Alamat *</label>
                    <input name="alamat" id="alamat" placeholder="Alamat" class="form-control" type="text" value="{{ old('alamat') }}" required="required">
                    @if($errors->first('alamat'))
                    <span class="text-danger">
                        {{ $errors->first('alamat') }}
                    </span>
                    @endif
                </div>
                <div class="form-group">
                    <label class="control-label" for="kecamatan">Kecamatan *</label>
                    @if($errors->has('kecamatan'))
                    <span class="text-danger">
                        {{ $errors->first('kecamatan') }}
                    </span>
                    @endif
                    <select name="kecamatan" id="kecamatan" class="form-control kecamatan" required>
                        @if($kecamatan_count > 0)
                            <option value="" disabled selected> -- Please Choose --</option>
                            @foreach($kecamatans as $kecamatan)
                            <option value="{{ $kecamatan->id }}"> {{ $kecamatan->kecamatan }} </option>
                            @endforeach
                        @else
                            <option value="" disabled selected> -- Lost connection, please refresh --</option>
                        @endif
                    </select>
                    <input type="hidden" name="kecamatan_text" id="kecamatan_text" value="" />
                </div>
                <div class="form-group">
                    <label class="control-label" for="kodepos">Kodepos *</label>
                    <input name="kodepos" id="kodepos" placeholder="Kodepos" class="form-control" type="text" value="{{ old('kodepos') }}" required="required">
                    @if($errors->first('kodepos'))
                    <span class="text-danger">
                        {{ $errors->first('kodepos') }}
                    </span>
                    @endif
                </div>
                <div class="form-group">
                    <label class="control-label" for="hp">HP *</label>
                    <input name="hp" id="hp" placeholder="HP" class="form-control" type="text" value="{{ old('hp') }}" required="required">
                    @if($errors->first('hp'))
                    <span class="text-danger">
                        {{ $errors->first('hp') }}
                    </span>
                    @endif
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <a href="{{ URL::to('checkout') }}" class="btn btn-default col-md-5 margin-top-10 pull-left">Kembali</a>
                        <button name="submit" type="submit" class="btn btn-primary col-md-5 margin-top-10 pull-right">Simpan Alamat Pengiriman</button>
                    </div>
                </div>
                <div class="row margin-top-20">
                </div>
            </form>
        </div>
    </div>
</div>


@stop

@section('script')
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
<script type="text/javascript">
    $('.kecamatan').select2();
    
    $('#kecamatan').change(function(){
        $('#kecamatan_text').val($('#kecamatan option:selected').text());
    });
</script>
@stop