@extends('layouts.front-end.layouts')


@section('content')

<div class="container">
    <div class="row"> 
        <div class="col-md-12">
            <div class="row">
                <div class="col-sm-6 col-sm-offset-3">
                    <h2 class="text-center heading">Profil</h2>
                </div>
            </div>
        </div>
    </div>
    @if(session('msg'))
    <div class="row">
        <div class="col-sm-3"></div>
        <div class="col-sm-6">
            <div class="alert alert-success alert-dismissible" role="alert" id="msg">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                {{ session('msg') }}
            </div>
        </div>
    </div>
    @endif
    @if(session('err'))
    <div class="row">
        <div class="col-sm-3"></div>
        <div class="col-sm-6">
            <div class="alert alert-danger alert-dismissible" role="alert" id="msg">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                {{ session('err') }}
            </div>
        </div>
    </div>
    @endif
    
    @if(strlen(auth()->user()->usersetting->kecamatan) <= 0)
    <div class="row">
        <div class="col-sm-3"></div>
        <div class="col-sm-6">
            <div class="alert alert-danger alert-dismissible" role="alert" id="msg">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                Silahkan isi data profil Anda dengan lengkap sebelum melanjutkan belanja.
            </div>
        </div>
    </div>
    @endif
    <div class="row">
        <div class="col-sm-2">
        </div>
        <div class="col-sm-8">
            <div id="checkout" class="col-md-12">
                <div class="box">
                    <ul id = "myTab" class = "nav nav-tabs">
                        @if(strlen(auth()->user()->usersetting->kecamatan) <= 0)
                        <li class="col-md-3 text-center">
                        @else
                        <li class="active col-md-3 text-center">
                        @endif
                            <a href = "#step1" data-toggle = "tab">
                                <i class="fa fa-home"></i><br>Nama
                            </a>
                       </li>
                       <li class = "col-md-3 text-center">
                            <a href = "#step2" data-toggle = "tab">
                                <i class="fa fa-user-secret"></i><br> Password
                            </a>
                        </li>
                        @if(strlen(auth()->user()->usersetting->kecamatan) <= 0)
                        <li class="active col-md-3 text-center">
                        @else
                        <li class="col-md-3 text-center">
                        @endif
                            <a href = "#step3" data-toggle = "tab">
                                <i class="fa fa-user"></i><br>Profil
                            </a>
                        </li>
                       <li class = "col-md-3 text-center">
                            <a href = "#step4" data-toggle = "tab">
                                <i class="fa fa-shopping-basket"></i><br>Dropship
                            </a>
                        </li>
                    </ul>
                    <div id = "myTabContent" class = "tab-content">
                        @if(strlen(auth()->user()->usersetting->kecamatan) <= 0)
                        <div class="tab-pane fade" id="step1">
                        @else
                        <div class="tab-pane fade in active" id="step1">
                        @endif
                            <div class="content">
                                <form action="{{ URL::to('updatename') }}" method="post" class="myform">
                                    {!! csrf_field() !!}
                                    <div class="form-group">
                                        <label class="control-label" for="username">Username</label>
                                        <input name="username" id="username" placeholder="Username" class="form-control" type="text" value="{{ $user->username }}" readonly>
                                        @if($errors->first('username'))
                                        <span class="text-danger">
                                            {{ $errors->first('username') }}
                                        </span>
                                        @endif
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label" for="username">Status</label>
                                        <input name="username" id="username" placeholder="Status" class="form-control" type="text" value="{{ $user->usersetting->status->status }}" readonly>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label class="control-label" for="first_name">Nama Depan *</label>
                                                @if($user->usersetting)
                                                <input name="first_name" id="first_name" placeholder="Nama Depan" class="form-control" type="text" value="{{ $user->usersetting->first_name }}" required="required">
                                                @else
                                                <input name="first_name" id="first_name" placeholder="Nama Depan" class="form-control" type="text" value="{{ old('first_name') }}" required="required">
                                                @endif
                                                
                                                @if($errors->first('first_name'))
                                                <span class="text-danger">
                                                    {{ $errors->first('first_name') }}
                                                </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label class="control-label" for="last_name">Nama Belakang *</label>
                                                @if($user->usersetting)
                                                <input name="last_name" id="last_name" placeholder="Nama Belakang" class="form-control" type="text" value="{{ $user->usersetting->last_name }}" >
                                                @else
                                                <input name="last_name" id="last_name" placeholder="Nama Belakang" class="form-control" type="text" value="{{ old('last_name') }}" >
                                                @endif
                                                
                                                @if($errors->first('last_name'))
                                                <span class="text-danger">
                                                    {{ $errors->first('last_name') }}
                                                </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <button name="submit" type="submit" class="btn btn-primary col-md-12 margin-bottom-20">Ubah Nama</button>
                                </form>
                            </div>
                        </div>   
                        <div class = "tab-pane fade" id = "step2">
                            <div class="content">
                                <form action="{{ URL::to('changepassword') }}" method="post" class="myform">
                                    {!! csrf_field() !!}
                                    <div class="form-group">
                                        <label class="control-label" for="oldpassword">Password Lama *</label>
                                        <input name="oldpassword" id="oldpassword" placeholder="Password Lama" class="form-control" type="password" value="{{ old('oldpassword') }}" required="required">
                                        @if($errors->first('oldpassword'))
                                        <span class="text-danger">
                                            {{ $errors->first('oldpassword') }}
                                        </span>
                                        @endif
                                    </div>  
                                    <div class="form-group">
                                        <label class="control-label" for="newpassword"> Password Baru *</label>
                                        <input name="newpassword" id="newpassword" placeholder="Password Baru" class="form-control" type="password" value="{{ old('newpassword') }}" required="required">
                                        @if($errors->first('newpassword'))
                                        <span class="text-danger">
                                            {{ $errors->first('newpassword') }}
                                        </span>
                                        @endif
                                    </div>  
                                    <div class="form-group">
                                        <label class="control-label" for="confpassword">Konfirmasi Password Baru *</label>
                                        <input name="confpassword" id="confpassword" placeholder="Masukkan Password Baru Lagi" class="form-control" type="password" value="{{ old('confpassword') }}" required="required">
                                        @if($errors->first('confpassword'))
                                        <span class="text-danger">
                                            {{ $errors->first('confpassword') }}
                                        </span>
                                        @endif
                                    </div>
                                    <button name="submit" type="submit" class="btn btn-primary col-md-12 margin-bottom-20">Ubah Password</button>
                                </form>
                            </div>
                        </div>
                        @if(strlen(auth()->user()->usersetting->kecamatan) <= 0)
                        <div class="in active tab-pane fade" id="step3">
                        @else
                        <div class="tab-pane fade" id="step3">
                        @endif
                            <div class="content">
                                <form action="{{ URL::to('updateprofile') }}" method="post" class="myform">
                                    {!! csrf_field() !!}
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label class="control-label" for="cur_jenis_kelamin">Jenis Kelamin</label>
                                                @if($user->usersetting)
                                                <input name="cur_jenis_kelamin" id="cur_jenis_kelamin" placeholder="Jenis Kelamin Sekarang" class="form-control" type="text" value="{{ $user->usersetting->jenis_kelamin }}" disabled>
                                                @else
                                                <input name="cur_jenis_kelamin" id="cur_jenis_kelamin" placeholder="Jenis Kelamin Sekarang" class="form-control" type="text" value="{{ old('cur_jenis_kelamin') }}" disabled>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-sm-8">
                                            <div class="form-group">
                                                <label class="control-label" for="jenis_kelamin">Jenis Kelamin *</label>
                                                <select id="jenis_kelamin" name="jenis_kelamin" class="form-control">
                                                    <option value="Pria">Pria</option>
                                                    <option value="Wanita">Wanita</option>
                                                </select>
                                                @if($errors->first('jenis_kelamin'))
                                                <span class="text-danger">
                                                    {{ $errors->first('jenis_kelamin') }}
                                                </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label" for="alamat">Alamat *</label>
                                        @if($user->usersetting)
                                        <input name="alamat" id="alamat" placeholder="Alamat" class="form-control" type="text" value="{{ $user->usersetting->alamat }}">
                                        @else
                                        <input name="alamat" id="alamat" placeholder="Alamat" class="form-control" type="text" value="{{ old('alamat') }}" >
                                        @endif

                                        @if($errors->first('alamat'))
                                        <span class="text-danger">
                                            {{ $errors->first('alamat') }}
                                        </span>
                                        @endif
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label class="control-label" for="cur_kecamatan">Kecamatan Sekarang</label>
                                                @if($user->usersetting)
                                                <input name="cur_kecamatan" id="cur_kecamatan" placeholder="Kecamatan Sekarang" class="form-control" type="text" value="{{ $user->usersetting->kecamatan }}" disabled>
                                                @else
                                                <input name="cur_kecamatan" id="cur_kecamatan" placeholder="Kecamatan Sekarang" class="form-control" type="text" value="{{ old('cur_kecamatan') }}" disabled>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label class="control-label" for="kecamatan">Kecamatan *</label>
                                                <select name="kecamatan" id="kecamatan" class="form-control kecamatan" required style="width: 100%;">
                                                    <option value="{{ $user->usersetting->kecamatan_id }}" disabled selected> -- Please Choose --</option>
                                                    @foreach($kecamatans as $kecamatan)
                                                    <option value="{{ $kecamatan['id'] }}"> {{ $kecamatan['kecamatan'] }} </option>
                                                    @endforeach
                                                </select>
                                                <input type="hidden" name="kecamatan_text" id="kecamatan_text" value="" />
                                                @if($errors->has('kecamatan'))
                                                <span class="text-danger">
                                                    {{ $errors->first('kecamatan') }}
                                                </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label" for="kodepos">Kodepos *</label>
                                        @if($user->usersetting)
                                        <input name="kodepos" id="kodepos" placeholder="Kodepos" class="form-control" type="text" value="{{ $user->usersetting->kodepos }}">
                                        @else
                                        <input name="kodepos" id="kodepos" placeholder="Kodepos" class="form-control" type="text" value="{{ old('kodepos') }}">
                                        @endif
                                        @if($errors->first('kodepos'))
                                        <span class="text-danger">
                                            {{ $errors->first('kodepos') }}
                                        </span>
                                        @endif
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label" for="hp">HP *</label>
                                        @if($user->usersetting)
                                        <input name="hp" id="hp" placeholder="HP" class="form-control" type="text" value="{{ $user->usersetting->hp }}">
                                        @else
                                        <input name="hp" id="hp" placeholder="HP" class="form-control" type="text" value="{{ old('hp') }}">
                                        @endif
                                        
                                        @if($errors->first('hp'))
                                        <span class="text-danger">
                                            {{ $errors->first('hp') }}
                                        </span>
                                        @endif
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label" for="email">Email</label>
                                        @if($user->usersetting)
                                        <input name="email" id="email" placeholder="Email" class="form-control" type="text" value="{{ $user->usersetting->email }}">
                                        @else
                                        <input name="email" id="email" placeholder="Email" class="form-control" type="text" value="{{ old('email') }}">
                                        @endif

                                        
                                        @if($errors->first('email'))
                                        <span class="text-danger">
                                            {{ $errors->first('email') }}
                                        </span>
                                        @endif
                                    </div>
                                    <button name="submit" type="submit" class="btn btn-primary col-md-12 margin-bottom-20">Ubah Profil</button>
                                </form>
                            </div>
                        </div>  
                        <div class = "tab-pane fade" id = "step4">
                            <div class="content">
                                <div>
                                    <div class="form-group">
                                        {!! csrf_field() !!}
                                        <label class="control-label" for="alamat">Tipe</label>
                                        <select class="form-control" id="type" name="type">
                                            <option value="">-- Silahkan pilih --</option>
                                            <option value="addTo">Alamat Tujuan Pengiriman</option>
                                            <option value="addFrom">Alamat Dropship</option>
                                        </select>
                                    </div>  
                                    <div id="addTo">
                                        <form action="{{ URL::to('updateto') }}" method="post" class="myform">
                                            {!! csrf_field() !!}
                                            <h4 class="text-center">Edit Alamat Tujuan Pengiriman</h4>
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <div class="form-group">
                                                        <label class="control-label" for="nama_alamat">Nama Alamat Tujuan Pengiriman *</label>
                                                        <select class="form-control" id="nama_alamat" name="nama_alamat">
                                                            <option value="">-- Silahkan pilih --</option>
                                                        </select>
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
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <div class="form-group">
                                                        <label class="control-label" for="addto_kecamatan_curr">Kecamatan *</label>
                                                        <input name="addto_kecamatan_curr" id="addto_kecamatan_curr" placeholder="Curr Kecamatan" class="form-control" type="text">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <div class="form-group">
                                                        <label class="control-label" for="addto_kecamatan">Kecamatan *</label>
                                                        <select name="addto_kecamatan" id="addto_kecamatan" class="form-control kecamatan" required style="width: 100%;">
                                                            <option value="{{ $user->usersetting->kecamatan_id }}" disabled selected> -- Please Choose --</option>
                                                            @foreach($kecamatans as $kecamatan)
                                                            <option value="{{ $kecamatan['id'] }}"> {{ $kecamatan['kecamatan'] }} </option>
                                                            @endforeach
                                                        </select>
                                                        <input type="hidden" name="addto_kecamatan_text" id="addto_kecamatan_text" value="" />
                                                        @if($errors->has('addto_kecamatan'))
                                                        <span class="text-danger">
                                                            {{ $errors->first('addto_kecamatan') }}
                                                        </span>
                                                        @endif
                                                    </div>
                                                </div>
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
                                                    <button name="submit" type="submit" class="btn btn-primary col-md-12 margin-top-10">Simpan</button>
                                                </div>
                                            </div>
                                            <div class="row margin-top-20">
                                            </div>
                                        </form>
                                    </div>
                                    <div id="addFrom">
                                        <form action="{{ URL::to('updatefrom') }}" method="post" class="myform">
                                            {!! csrf_field() !!}
                                            <h4 class="text-center">Edit Alamat Dropship</h4>

                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <div class="form-group">
                                                        <label class="control-label" for="nama_pengiriman">Nama Alamat Dropship *</label>
                                                        <select class="form-control" id="nama_pengiriman" name="nama_pengiriman">
                                                            <option value="">-- Silahkan pilih --</option>
                                                        </select>
                                                        @if($errors->first('nama_pengiriman'))
                                                        <span class="text-danger">
                                                            {{ $errors->first('nama_pengiriman') }}
                                                        </span>
                                                        @endif
                                                    </div>
                                                </div>
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
                                                    <button name="submit" type="submit" class="btn btn-primary col-md-12 margin-top-10">Simpan</button>
                                                </div>
                                            </div>
                                            <div class="row margin-top-20">
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@stop


@section('script')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
    <script type="text/javascript" src="{{ asset('ext/js/custom/profile.js') }}"></script>
    
    <script>
        $(document).ready( function() {
          $('#msg').delay(3000).fadeOut();
        });
          
        var token = $('input[name=_token]').val();
        
        $('.kecamatan').select2();

        $('#kecamatan').change(function(){
            $('#kecamatan_text').val($('#kecamatan option:selected').text());
        });
        
        $('#addto_kecamatan').change(function(){
            $('#addto_kecamatan_text').val($('#addto_kecamatan option:selected').text());
        });
        
        $("#addTo").hide();
        $("#addFrom").hide();
        disabledAddFrom();
        disabledAddTo();
    </script>
@stop


@section('script')
@stop