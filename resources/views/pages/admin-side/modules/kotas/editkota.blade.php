@extends('layouts.admin-side.default')


@section('title')
@parent
    Edit Kota
@stop


@section('content')
    
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Ubah Kota</h1>
        </div>
    </div>
        
        
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-info">
                <div class="panel-heading">
                    Input Data Anda
                </div>
                <div class="panel-body">
                    
                    <form method="post" action="{{ url('kota/update') }}">
                        {!! csrf_field() !!}
                        <input type="hidden" value="{{ $kota->id }}" name="kota_id" />

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="kota">Kota</label>
                                    <input type="text" name="kota" class="form-control" placeholder="Kota" required value="{{ $kota->kota }}"/>
                                </div>
                            </div>
                        </div>
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
                                <input type="submit" value="Ubah" class="btn btn-default btn-success btn-block" />
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