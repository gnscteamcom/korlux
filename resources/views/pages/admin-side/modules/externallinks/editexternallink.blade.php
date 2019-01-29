@extends('layouts.admin-side.default')


@section('title')
@parent
    Edit Link
@stop


@section('content')

    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Ubah Link</h1>
        </div>
    </div>


    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-info">
                <div class="panel-heading">
                    Input Data Anda
                </div>
                <div class="panel-body">

                    <form method="post" action="{{ url('extlink/update') }}">
                        {!! csrf_field() !!}
                        <input type="hidden" value="{{ $extlink->id }}" name="link_id" />

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="link">Nama Link</label>
                                    <input type="text" name="link" class="form-control" autofocus placeholder="Link" required value="{{ $extlink->name }}"/>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="link_koreanluxury">Link Korean Luxury</label>
                                    <input type="text" name="link_koreanluxury" class="form-control" autofocus placeholder="Jangan ada spasi" required value="{{ $extlink->link }}"/>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="tujuan">Tujuan</label>
                                    <input type="text" name="tujuan" class="form-control" placeholder="Misal. https://koreanluxury.com" required value="{{ $extlink->redirect_to }}"/>
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
                                                    @if($errors->has('link'))
                                                        {{ $errors->first('link') }}
                                                    @endif
                                                    @if($errors->has('link_koreanluxury'))
                                                        {{ $errors->first('link_koreanluxury') }}
                                                    @endif
                                                    @if($errors->has('tujuan'))
                                                        {{ $errors->first('tujuan') }}
                                                    @endif
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
