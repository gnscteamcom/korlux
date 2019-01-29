@extends('layouts.admin-side.default')


@section('title')
@parent
    Master Brand
@stop


@section('content')
    
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Merk</h1>
        </div>
    </div>


    <div class="row">
        <div class="col-lg-4">
            <div class="panel-body">
                <a href="{{ URL::to('addbrand') }}">
                    <input type="button" value="Tambah Merk" class="form-control btn btn-primary" />
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
                    Merk
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover" id="dataTables">
                            <thead>
                                <tr>
                                    <th class="col-sm-2">Tindakan</th>
                                    <th class="col-sm-2">Merk</th>
                                    <th class="col-sm-2">Inisial</th>
                                    <th class="col-sm-2">Total Produk</th>
                                </tr>
                            </thead>
                            
                            <tbody>
                                @foreach($brands as $brand)
                                <tr>
                                    <td>
                                        <a href="#" title="Delete Brand">
                                            <i class="fa fa-trash-o fa-fw fa-2x" data-toggle="modal" data-target="<?php echo '#myModal' . $brand->id ?>"></i>
                                        </a>
                                        <div class="modal fade" id="<?php echo 'myModal' . $brand->id ?>" tabindex="-1" role="dialog" aria-labelledby="<?php echo 'myModalLabel' . $brand->id ?>" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                        <h4 class="modal-title" name="<?php echo 'myModalLabel' . $brand->id ?>">Brand Deletion Confirmation</h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        Anda yakin mau menghapus merk ini?<br />
                                                        Produk dengan merk ini juga akan terhapus..
                                                    </div>
                                                    <div class="modal-footer">
                                                        <input type="button" class="btn btn-default" data-dismiss="modal" value="Batal"/>
                                                        <a href="{{ URL::to('deletebrand/' . $brand->id) }}" class="btn btn-primary">Hapus</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                            
                                        <a href="{{ URL::to('editbrand/' . $brand->id) }}" title="Edit Brand"><i class="fa fa-pencil fa-fw fa-2x"></i></a>
                                    </td>
                                    <td>{{ $brand->brand }}</td>
                                    <td>{{ $brand->initial }}</td>
                                    <td>{{ $brand->products->count() }}</td>
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
        
    <!--Fancybox-->
    <script type="text/javascript" src="{{ URL::asset('ext/js/jquery/jquery.fancybox.js') }}"></script>

    <script>
        $(document).ready(function() {
            $('#dataTables').dataTable();
        });
        

        // Remove padding, set opening and closing animations, close if clicked and disable overlay
        $(".fancybox-effects-d").fancybox({
                padding: 0,

                openEffect : 'elastic',
                openSpeed  : 150,

                closeEffect : 'elastic',
                closeSpeed  : 150,

                closeClick : true,

                helpers : {
                        overlay : null
                }
        });
    </script>
    
@stop