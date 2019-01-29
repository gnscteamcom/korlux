@extends('layouts.admin-side.default')


@section('title')
@parent
Add Kecamatan
@stop


@section('content')

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Tambah Kecamatan</h1>
    </div>
</div>


<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                Input Data Anda
            </div>
            <div class="panel-body">

                <form method="post" action="{{ url('kecamatan/add') }}">
                    {!! csrf_field() !!}

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="kota">Kota</label>
                                <select class="form-control kota" name="kota" required>
                                    <option value="" disabled selected> Silahkan Pilih Kota</option>
                                    @foreach($kotas as $kota)
                                    <option value="{{ $kota->id }}">{{ $kota->kota }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="kecamatan">Kecamatan</label>
                                <input type="text" name="kecamatan" class="form-control" placeholder="Kecamatan" required/>
                            </div>
                        </div>
                    </div>
                    @foreach($methods as $method)
                    <input type="hidden" name="metode[]" value="{{ $method->id }}" />
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>{{ $method->shipmethod_name . ' - ' . $method->shipmethod_type }}</label>
                                <input type="number" name="ongkir[]" class="form-control" placeholder="Ongkos Kirim" min="0" value="0" required/>
                            </div>
                        </div>
                    </div>
                    @endforeach

                    @if ($errors->any())
                    <div class="row">
                        <div class="col-lg-6">
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
                    @if (session('err'))
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="alert alert-danger">
                                {!! session('err') !!}
                            </div>
                        </div>
                    </div>
                    @endif
                    <div class="row">
                        <div class="col-lg-6">
                            <input type="submit" value="Tambah" class="btn btn-default btn-success btn-block" />
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


@section('script')

<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
<script type="text/javascript">
$('.kota').select2();
</script>

@stop
