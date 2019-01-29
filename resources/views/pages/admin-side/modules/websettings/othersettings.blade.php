@extends('layouts.admin-side.default')


@section('title')
@parent
    Misc Configuration
@stop


@section('content')
    <?php
        use App\Socialmedia;
    ?>
    
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Pengaturan Lainnya</h1>
        </div>
    </div>

                    
    @if(Session::has('msg'))
    <div class="row">
        <div class="col-lg-12">
            <h3 class="text-success">
                {{ Session::get('msg') }}
            </h3>
        </div>
    </div>
    @endif
        
        
    
    <!--Bagian Social Media-->
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <h2>Pengaturan Jejaring Sosial</h2>
                </div>
                <div class="panel-body">
                    
                    
                    <form method="post" action="{{ URL::to('updatesocialmedia') }}">
                        {!! csrf_field() !!}
                        
                        @if(Session::has('err'))
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group text-danger">
                                    {{ Session::get('err') }}
                                </div>
                            </div>
                        </div>
                        @endif

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="social_media">Jejaring Sosial</label>

                                        @foreach($social_media_lists as $social_media)
                                            <div class="checkbox i-checks text-success">
                                                <label>
                                                    {!! '<input type="checkbox" value="' . $social_media['id'] . '/' . $social_media['name'] . '/' . $social_media['base'] . '/' . $social_media['icon'] . '" name="social_media[]"' !!}
                                                    @if(in_array($social_media['id'], $social_id))
                                                    {!! 'checked="checked"' !!}
                                                    @endif
                                                    {!! '>' !!}
                                                    @if(strcmp($social_media['name'], 'LINE') == 0)
                                                    <img src="{{ URL::asset('ext/img/socmed/line.png') }}" class="img-resposive" width="50" />
                                                    @else
                                                    <img src="{{ URL::asset('ext/img/socmed/ig.png') }}" class="img-resposive" width="50" />
                                                    @endif
                                                    <strong>{{ $social_media['name'] }}</strong> /
                                                    <?php
                                                        
                                                        $socmed = Socialmedia::whereSocial_id($social_media['id'])->first();
//                                                        $socmed = array();
                                                    ?>
                                                    @if($socmed)
                                                    <input type="text" name="additional_link[]" placeholder="Profile Name Link" value="{{ $socmed->social_additional_link }}"/>
                                                    @else
                                                    <input type="text" name="additional_link[]" placeholder="Profile Name Link" />
                                                    @endif
                                                </label>
                                            </div>
                                        @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <input type="submit" class="btn btn-default btn-success btn-block" value="Perbarui" />
                            </div>
                        </div>
                        
                    </form>

                </div>
            </div>
        </div>
    </div>
        
    
    
    
</div>
@stop