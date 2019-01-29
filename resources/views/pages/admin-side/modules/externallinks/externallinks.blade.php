@extends('layouts.admin-side.default')


@section('title')
@parent
    Master External Links
@stop


@section('content')

    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">External Link</h1>
        </div>
    </div>


    <div class="row">
        <div class="col-lg-4">
            <div class="panel-body">
                <a href="{{ url('extlink/add') }}">
                    <input type="button" value="Tambah External Link" class="form-control btn btn-primary" />
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
                    External Link
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover" id="dataTables">
                            <thead>
                                <tr>
                                    <th class="col-sm-2">Tindakan</th>
                                    <th class="col-sm-2">Nama Link</th>
                                    <th class="col-sm-2">Link Korean Luxury</th>
                                    <th class="col-sm-2">Tujuan</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($external_links as $link)
                                <tr>
                                    <td>
                                        <a href="#" title="Delete External Links">
                                            <i class="fa fa-trash-o fa-fw fa-2x" data-toggle="modal" data-target="<?php echo '#myModal' . $link->id ?>"></i>
                                        </a>
                                        <div class="modal fade" id="<?php echo 'myModal' . $link->id ?>" tabindex="-1" role="dialog" aria-labelledby="<?php echo 'myModalLabel' . $link->id ?>" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                        <h4 class="modal-title" name="<?php echo 'myModalLabel' . $link->id ?>">External Links Deletion Confirmation</h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        Anda yakin mau menghapus link ini?
                                                    </div>
                                                    <div class="modal-footer">
                                                        <input type="button" class="btn btn-default" data-dismiss="modal" value="Batal"/>
                                                        <a href="{{ url('extlink/delete/' . $link->id) }}" class="btn btn-primary">Hapus</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <a href="{{ url('extlink/edit/' . $link->id) }}" title="Edit External Links"><i class="fa fa-pencil fa-fw fa-2x"></i></a>
                                    </td>
                                    <td>{{ $link->name }}</td>
                                    <td>{{ $link->link }}</td>
                                    <td>{{ $link->redirect_to }}</td>
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
