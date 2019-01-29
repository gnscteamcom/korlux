@extends('layouts.admin-side.default')


@section('title')
@parent
    Import Stok Masuk
@stop


@section('content')
    
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Import Stok Masuk</h1>
        </div>
    </div>


    <div class="row">
        <div class="col-lg-4">
            <div class="panel-body">
                <a href="{{ URL::to('downloadstockinformat') }}">
                    <input type="button" value="Unduh Format Excel" class="form-control btn btn-primary" />
                </a>
            </div>
        </div>
    </div>
        
        
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-info">
                <div class="panel-heading">
                    Pilih file untuk diimport
                </div>
                <div class="panel-body">
                    
                    <form method="post" action="{{ URL::to('importstockin') }}" enctype="multipart/form-data" class="form-horizontal">
                        {!! csrf_field() !!}
                                            
                        @if(Session::has('err'))
                        <div class="form-group">
                            <div class="col-sm-4 col-sm-offset-2">
                                <div class="form-group text-danger">
                                    {{ Session::get('err') }}
                                </div>
                            </div>
                        </div>
                        @endif


                        @if($errors->has('file'))
                        <div class="form-group">
                            <div class="col-sm-4 col-sm-offset-2">
                                <div class="form-group text-danger">
                                    {{ $errors->first('file') }}
                                </div>
                            </div>
                        </div>
                        @endif


                        @if($errors->has('file'))
                        <div class="form-group has-error">
                        @else
                        <div class="form-group">
                        @endif
                            <div class="col-sm-2 control-label">
                                <label for="file">File</label>
                            </div>
                            <div class="col-sm-10">
                                <input type="file" name="file" id="file"/>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6">
                                <input type="submit" class="btn btn-default btn-success btn-block" value="Import" />
                            </div>
                        </div>
                        
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@stop