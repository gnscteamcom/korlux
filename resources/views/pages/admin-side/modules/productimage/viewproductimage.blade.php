@extends('layouts.admin-side.default')


@section('title')
@parent
    Foto Produk
@stop


@section('content')

    
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Daftar Foto Produk</h1>
        </div>
    </div>


    <div class="row">
        <div class="col-lg-4">
            <div class="panel-body">
                <a href="{{ URL::to('addproductimage') }}">
                    <input type="button" value="Tambah Foto Produk" class="form-control btn btn-primary" />
                </a>
            </div>
        </div>
    </div>


    @if(Session::has('msg'))
    <div class="row">
        <div class="col-lg-12">
            <div class="form-group text-danger">
                <div class="alert alert-success alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        {!! '<b>' . Session::get('msg') . '</b>' !!}
                </div>
            </div>
        </div>
    </div>
    @endif
        
        
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-info">
                <div class="panel-heading">
                    Daftar Foto
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        
                        <div class="col-md-6 margin-bottom-20">
                            <form method="post" action="{{ URL::to('search/searchproductimage') }}">
                                {!! csrf_field() !!}
                                <div class="col-lg-12">
                                    <div class="input-group">
                                        <input type="text" name="search" id="search" class="form-control" required="required" autofocus="autofocus" placeholder="Nama Produk" />
                                        <span class="input-group-btn">
                                            <button class="btn btn-default" type="submit">Cari</button>
                                        </span>
                                    </div>
                                </div>
                            </form>
                        </div>
                        
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th style="width:50px;">#</th>
                                    <th>Tindakan</th>
                                    <th>Barcode</th>
                                    <th>Nama Produk</th>
                                    <th>Foto</th>
                                </tr>
                            </thead>
                            
                            <tbody>
                                <?php 
                                    $i = 1;
                                ?>
                                @foreach($products as $product)
                                <tr>
                                    <td>{{ $i++ }}</td>
                                    <td>
                                        <a href="#"><i class="fa fa-trash-o fa-fw fa-2x" data-toggle="modal" data-target="<?php echo '#myModal' . $product->id ?>"></i></a>
                                        <div class="modal fade" id="<?php echo 'myModal' . $product->id ?>" tabindex="-1" role="dialog" aria-labelledby="<?php echo 'myModalLabel' . $product->id ?>" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                        <h4 class="modal-title" name="<?php echo 'myModalLabel' . $product->id ?>">Konfirmasi</h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        Apakah Anda yakin akan menghapus foto produk ini?
                                                    </div>
                                                    <div class="modal-footer">
                                                        <input type="button" value="Batal" class="btn btn-default" data-dismiss="modal" />
                                                        <a href="{{ URL::to('deleteproductimage/' . $product->id) }}" class="btn btn-primary" >Hapus Foto</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                            
                                        <a href="{{ URL::to('editproductimage/' . $product->id) }}"><i class="fa fa-pencil fa-fw fa-2x"></i></a>
                                    </td>
                                    <td>
                                        {{ $product->barcode }}
                                    </td>
                                    <td>
                                        {!! '<b>' . $product->product_code . ' - ' . $product->product_name . '</b>' !!}
                                    </td>
                                    <td>
                                        @foreach($product->productimages as $productimage)
                                            <a class="fancybox" href="{{ URL::to($productimage->image_path) }}" data-fancybox-group="gallery" title="">
                                                <img src="{{ URL::to($productimage->image_path) }}" alt=""  width="50px" alt="" class="img-responsive"/>
                                            </a>
                                        @endforeach
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        
                        {!! $products->links() !!}
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop


@section('script')
    <script type="text/javascript">
        $(document).ready(function() {
            $('.fancybox').fancybox();
        });
    </script>
    
@stop