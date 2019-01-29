@extends('layouts.admin-side.default')


@section('title')
@parent
    Master Kota
@stop


@section('content')
    
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Kota</h1>
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
        <div class="col-lg-4">
            <div class="panel panel-green">
                <div class="panel-heading">
                    View & Update Kota
                </div>
                <div class="panel-body">


                    <a href="{{ url('kota/download') }}">
                        <input type="button" value="Download Excel Format" class="form-control btn btn-primary" />
                    </a>
                    
                    <hr>
                    <h3>Upload Excel</h3>
                    
                    <form method="post" action="{{ url('kota/import') }}" enctype="multipart/form-data">
                        {!! csrf_field() !!}

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="file">Choose File</label>
                                    <input type="file" name="file" required/>
                                </div>
                            </div>
                        </div>
                        
                                               
                        <div class="row">
                            <div class="col-lg-6">
                                <input type="submit" value="Upload" class="btn btn-default btn-success btn-block" />
                            </div>
                        </div>
                        
                    </form>
                </div>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-lg-4">
            <div class="panel-body">
                <a href="{{ url('kota/add') }}">
                    <input type="button" value="Tambah Kota" class="form-control btn btn-primary" />
                </a>
            </div>
        </div>
    </div>

        
        
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-info">
                <div class="panel-heading">
                    Kota
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover" id="dataTables">
                            <thead>
                                <tr>
                                    <th class="col-sm-2">Tindakan</th>
                                    <th class="col-sm-2">Kota</th>
                                </tr>
                            </thead>
                            
                            <tbody>
                                @foreach($kotas as $kota)
                                <tr>
                                    <td>
                                        <a href="#" title="Delete Kota">
                                            <i class="fa fa-trash-o fa-fw fa-2x" data-toggle="modal" data-target="<?php echo '#myModal' . $kota->id ?>"></i>
                                        </a>
                                        <div class="modal fade" id="<?php echo 'myModal' . $kota->id ?>" tabindex="-1" role="dialog" aria-labelledby="<?php echo 'myModalLabel' . $kota->id ?>" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                        <h4 class="modal-title" name="<?php echo 'myModalLabel' . $kota->id ?>">Kota Deletion Confirmation</h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        Anda yakin mau menghapus kota ini?
                                                    </div>
                                                    <div class="modal-footer">
                                                        <input type="button" class="btn btn-default" data-dismiss="modal" value="Batal"/>
                                                        <a href="{{ url('kota/delete/' . $kota->id) }}" class="btn btn-primary">Hapus</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                            
                                        <a href="{{ url('kota/edit/' . $kota->id) }}" title="Edit Kota"><i class="fa fa-pencil fa-fw fa-2x"></i></a>
                                    </td>
                                    <td>{{ $kota->kota }}</td>
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