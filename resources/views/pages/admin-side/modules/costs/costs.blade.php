@extends('layouts.admin-side.default')


@section('title')
@parent
    Master Ongkos Kirim
@stop


@section('content')

    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Ongkos Kirim</h1>
        </div>
    </div>


    <div class="row">
        <div class="col-lg-4">
            <div class="panel panel-green">
                <div class="panel-heading">
                    View & Update Ongkos Kirim
                </div>
                <div class="panel-body">


                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label for="kecamatan">Kecamatan</label>
                                <select class="form-control kecamatan" name="kecamatan" id="kecamatan" required>
                                    <option value="0" selected> Semua Kecamatan </option>
                                    @foreach($kecamatans as $kecamatan)
                                    <option value="{{ $kecamatan->id }}">{{ $kecamatan->kota->kota . ' - ' . $kecamatan->kecamatan }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" value="{{ url('shipcost/download') }}" id="download_link" />
                    <a href="{{ url('shipcost/download/0') }}" id="download_btn">
                        <input type="button" value="Download Excel Format" class="form-control btn btn-primary" />
                    </a>

                    <hr>
                    <h3>Upload Excel</h3>

                    <form method="post" action="{{ url('shipcost/import') }}" enctype="multipart/form-data">
                        {!! csrf_field() !!}

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="file">Choose File</label>
                                    <input type="file" name="file" required/>
                                </div>
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-lg-6">
                                <input type="submit" value="Upload" class="btn btn-default btn-success btn-block" />
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>


    @if(Session::has('msg'))
    <div class="row">
        <div class="col-lg-12">
            <div class="form-group text-success">
                <div class="alert alert-success alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        {!! '<b>' . Session::get('msg') . '</b>' !!}
                </div>
            </div>
        </div>
    </div>
    @endif

    @if(Session::has('err'))
    <div class="row">
        <div class="col-lg-12">
            <div class="form-group text-danger">
                <div class="alert alert-danger alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        {!! '<b>' . Session::get('err') . '</b>' !!}
                </div>
            </div>
        </div>
    </div>
    @endif



</div>
@stop

@section('script')

<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>

<script>
    $('#kecamatan').change(function(){
        var download_link = $('#download_link').val();
        $('#download_btn').attr('href', download_link + '/' + $('option:selected', this).val());
    });

    $('.kecamatan').select2();
</script>

@stop
