@extends('layouts.front-end.layouts')


@section('content')

<div class="container-fluid cart-list">
    @if(Session::get('msg'))
    <div class="row">
        <div class="col-sm-12">
            <span class="text-success">
                {{ Session::has('msg') }}
            </span>
        </div>
    </div>
    @endif
    <div class="row">
        <div class="col-sm-3"></div>
        <div class="col-sm-6">
            <form action="{{ URL::to('register') }}" method="post" class="myform">
                {!! csrf_field() !!}

                <h2 class="text-center">Registrasi</h2>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label class="control-label" for="nama_depan">Nama Depan *</label>
                            <input name="nama_depan" id="nama_depan" placeholder="Nama Depan" class="form-control" type="text" value="{{ old('nama_depan') }}" autofocus required="required">
                            @if($errors->has('nama_depan'))
                            <span class="text-danger">
                                {{ $errors->first('nama_depan') }}
                            </span>
                            @endif
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label class="control-label" for="nama_belakang">Nama Belakang *</label>
                            <input name="nama_belakang" id="nama_belakang" placeholder="Nama Belakang" class="form-control" type="text" value="{{ old('nama_belakang') }}" >
                            @if($errors->has('nama_belakang'))
                            <span class="text-danger">
                                {{ $errors->first('nama_belakang') }}
                            </span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label" for="username">Username *</label>
                    <input name="username" id="username" placeholder="Username" class="form-control" type="text" value="{{ old('username') }}" required="required">
                    @if($errors->has('username'))
                    <span class="text-danger">
                        {{ $errors->first('username') }}
                    </span>
                    @endif
                </div>
                <div class="form-group">
                    <label class="control-label" for="password">Password *</label>
                    <input name="password" if="password" placeholder="Password" class="form-control" type="password" value="{{ old('password') }}" required="required">
                    @if($errors->has('password'))
                    <span class="text-danger">
                        {{ $errors->first('password') }}
                    </span>
                    @endif
                </div>	
                <div class="form-group">
                    <label class="control-label" for="konfirmasi_password">Konfirmasi Password *</label>
                    <input name="konfirmasi_password" id="konfirmasi_password" placeholder="Password Confirmation" class="form-control" type="password" required="required" value="{{ old('password_confirmation') }}">
                    @if($errors->has('konfirmasi_password'))
                    <span class="text-danger">
                        {{ $errors->first('konfirmasi_password') }}
                    </span>
                    @endif
                </div>	
                
                
            
                <h2 class="text-center">Account Information</h2>
                
                
                <div class="form-group">
                    <label class="control-label" for="jenis_kelamin">Jenis Kelamin *</label>
                    <select id="jenis_kelamin" name="jenis_kelamin" class="form-control" required="required">
                        <option value="Pria">Pria</option>
                        <option value="Wanita">Wanita</option>
                    </select>
                    @if($errors->has('jenis_kelamin'))
                    <span class="text-danger">
                        {{ $errors->first('jenis_kelamin') }}
                    </span>
                    @endif
                </div>
                <div class="form-group">
                    <label class="control-label" for="alamat">Alamat *</label>
                    <input name="alamat" id="alamat" placeholder="Alamat" class="form-control" type="text" value="{{ old('alamat') }}" required="required">
                    @if($errors->has('alamat'))
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
                            <option value="" disabled selected> -- Please Choose --</option>
                            @foreach($kecamatans as $kecamatan)
                            <option value="{{ $kecamatan['id'] }}"> {{ $kecamatan['kecamatan'] }} </option>
                            @endforeach
                    </select>
                    <input type="hidden" name="kecamatan_text" id="kecamatan_text" value="" />
                </div>
                <div class="form-group">
                    <label class="control-label" for="kodepos">Kodepos *</label>
                    <input name="kodepos" id="kodepos" required="required" placeholder="Kodepos" class="form-control" type="text" value="{{ old('kodepos') }}">
                    @if($errors->has('kodepos'))
                    <span class="text-danger">
                        {{ $errors->first('kodepos') }}
                    </span>
                    @endif
                </div>
                <div class="form-group">
                    <label class="control-label" for="hp">HP *</label>
                    <input name="hp" id="hp" placeholder="HP" required="required" class="form-control" type="text" value="{{ old('hp') }}">
                    @if($errors->has('hp'))
                    <span class="text-danger">
                        {{ $errors->first('hp') }}
                    </span>
                    @endif
                </div>
                <div class="form-group">
                    <label class="control-label" for="email">Email</label>
                    <input name="email" id="email" placeholder="Email" class="form-control" type="text" value="{{ old('email') }}">
                    @if($errors->has('email'))
                    <span class="text-danger">
                        {{ $errors->first('email') }}
                    </span>
                    @endif
                </div>
                
                <button type="submit" class="btn btn-primary col-sm-12"><i class="fa fa-envelope-o"></i> Daftar</button>
                <div class="">&nbsp;</div>
            </form>
        </div>
    </div>
</div>

@include('includes.admin-side.validation')

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