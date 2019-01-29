@extends('layouts.admin-side.default')


@section('title')
@parent
Ubah Point
@stop


@section('content')

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Ubah Point Baru</h1>
    </div>
</div>


<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                Silahkan masukkan produk baru
            </div>
            <div class="panel-body">

                <form method="post" action="{{ URL::to('discountpoint/update') }}">
                    {!! csrf_field() !!}
                    
                    <input type="hidden" value="{{ $point->id }}" name="point_id" />
                    
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
                            <div class="form-group">
                                <label for="nominal_minimal">Nominal Minimal</label>
                                <input type="number" name="nominal_minimal" class="form-control" placeholder="Nominal Minimal" value="{{ $point->minimal_amount }}" required/>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="nominal_maksimal">Nominal Maksimal</label>
                                <input type="number" name="nominal_maksimal" class="form-control" placeholder="Nominal Maksimal" value="{{ $point->maximal_amount }}" required/>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="persentase_poin">Persentase Poin (%)</label>
                                <input type="number" name="persentase_poin" class="form-control" placeholder="Persentase Poin" value="{{ $point->point_percentage }}" required/>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <input type="submit" value="Ubah" class="btn btn-default btn-success btn-block"/>
                        </div>
                    </div>

                </form>


            </div>
        </div>
    </div>
</div>

@include('includes.admin-side.validation')
@stop


@section('script')

<script>

    var token = $('input[name=_token]').val();

</script>


<script type="text/javascript" src="{{ URL::asset('ext/js/custom/listsubkategori.js') }}"></script>
@stop