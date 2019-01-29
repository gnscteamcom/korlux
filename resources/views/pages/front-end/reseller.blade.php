@extends('layouts.front-end.layouts')


@section('content')

@if($term)

<div class="container">
    <div class="row"> 
        <div class="col-sm-10 col-sm-offset-1">
            <div class="row"> 
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-sm-6 col-sm-offset-3">
                            <h2 class="text-center heading">Reseller</h2>
                        </div>
                    </div>
                </div>
            </div>
            @if(strlen(strip_tags($term->reseller)) > 0)
            <div class="row">
                <div class="col-md-10 col-md-offset-1">
                    <blockquote>    
                        <p>
                            {!! $term->reseller !!}
                        </p>
                    </blockquote>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
    
@endif


@stop