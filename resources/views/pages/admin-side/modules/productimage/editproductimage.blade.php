@extends('layouts.admin-side.default')


@section('title')
@parent
    Ubah Foto Produk
@stop


@section('content')
    
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Ubah Foto Produk</h1>
        </div>
    </div>
        
        
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-info">
                <div class="panel-heading">
                    Silahkan ubah foto produk
                </div>
                <div class="panel-body">
                    
                    <form method="post" action="{{ URL::to('updateproductimage') }}" enctype="multipart/form-data">
                        {!! csrf_field() !!}
                        
                        <div class="row">
                            <div class="col-lg-6">
                                @if(Session::has('err'))
                                    <div class="form-group text-danger">
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
                                    </div>
                                @endif
                                @if(!$errors->isEmpty())
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="form-group text-danger">
                                                <div class="alert alert-danger alert-dismissible" role="alert">
                                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                    @if($errors->has('foto1'))
                                                        {{ $errors->first('foto1') }}
                                                    @endif
                                                    @if($errors->has('foto2'))
                                                        {{ $errors->first('foto2') }}
                                                    @endif
                                                    @if($errors->has('foto3'))
                                                        {{ $errors->first('foto3') }}
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="nama_produk">Produk</label>
                                    <input type="text" class="form-control" name="nama_produk" value="{{ $product->product_name }}" readonly="readonly" />
                                    <input type="hidden" name="produk" value="{{ $product->id }}" />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="col-lg-6">
                                    <h4>Foto Sekarang</h4><br>
                                    <?php $i = 1 ?>
                                    @foreach($product->productimages as $productimage)
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <a class="fancybox" href="{{ URL::to($productimage->image_path) }}" data-fancybox-group="gallery" title="">
                                                <img src="{{ URL::to($productimage->image_path) }}" alt=""  width="50px" alt="" class="img-responsive display-box"/>
                                            </a>
                                            <a href="{{ URL::to('deleteoneproductimage/' . $productimage->id) }}">Delete</a>
                                        </div>
                                        <div class="col-lg-6">
                                        </div>
                                    </div>
                                    <?php $i++; ?>
                                    @endforeach
                                </div>
                                <div class="col-lg-6">
                                    <h4>Foto Baru</h4><br>
                                    @for($j = $i; $j <= 3; $j++)
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label for="{{ 'foto' . $j }}">Foto {{ $j }}</label>
                                                <input type="file" name="{{ 'foto' . $j }}" id="{{ 'foto' . $j }}" accept="image/*"/>
                                            </div>
                                        </div>
                                    </div>
                                    @endfor
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-3"></div>
                            <div class="col-lg-6">
                                <input type="submit" value="Ubah" class="btn btn-default btn-success btn-block"/>
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
    <script type="text/javascript">
        $(document).ready(function() {
            $('.fancybox').fancybox();
        });
    </script>
    
@stop