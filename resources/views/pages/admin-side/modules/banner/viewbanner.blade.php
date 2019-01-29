@extends('layouts.admin-side.default')


@section('title')
@parent
    Banner
@stop


@section('content')

    
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Daftar Banner</h1>
        </div>
    </div>


    <div class="row">
        <div class="col-lg-4">
            <div class="panel-body">
                <a href="{{ URL::to('addbanner') }}">
                    <input type="button" value="Tambah Banner" class="form-control btn btn-primary" />
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
                    Daftar Banner
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        
                        
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th style="width:50px;">#</th>
                                    <th>Tindakan</th>
                                    <th>Foto</th>
                                    <th>Tautan</th>
                                </tr>
                            </thead>
                            
                            <tbody>
                                <?php 
                                    $i = 1;
                                ?>
                                @foreach($banners as $banner)
                                <tr>
                                    <td>{{ $i++ }}</td>
                                    <td>
                                        <a href="{{ URL::to('editbanner/' . $banner->id) }}" title="Edit Brand"><i class="fa fa-pencil fa-fw fa-2x"></i></a>

                                        <a href="#"><i class="fa fa-trash-o fa-fw fa-2x" data-toggle="modal" data-target="<?php echo '#myModal' . $banner->id ?>"></i></a>
                                        <div class="modal fade" id="<?php echo 'myModal' . $banner->id ?>" tabindex="-1" role="dialog" aria-labelledby="<?php echo 'myModalLabel' . $banner->id ?>" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                        <h4 class="modal-title" name="<?php echo 'myModalLabel' . $banner->id ?>">Konfirmasi</h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        Apakah Anda yakin akan menghapus banner ini?
                                                    </div>
                                                    <div class="modal-footer">
                                                        <input type="button" value="Batal" class="btn btn-default" data-dismiss="modal" />
                                                        <a href="{{ URL::to('deletebanner/' . $banner->id) }}" class="btn btn-primary" >Hapus Banner</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <a class="fancybox" href="{{ URL::to($banner->image_path) }}" data-fancybox-group="gallery" title="">
                                            <img src="{{ URL::to($banner->image_path) }}" alt=""  width="50px" alt="" class="img-responsive"/>
                                        </a>
                                    </td>
                                    <td>
                                        {!! $banner->redirect_link !!}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        
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