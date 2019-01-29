@extends('layouts.admin-side.default')


@section('title')
@parent
    Master Metode
@stop


@section('content')
    
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Metode</h1>
        </div>
    </div>


    <div class="row">
        <div class="col-lg-4">
            <div class="panel-body">
                <a href="{{ url('shipmethod/add') }}">
                    <input type="button" value="Tambah Metode" class="form-control btn btn-primary" />
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
                    Metode
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover" id="dataTables">
                            <thead>
                                <tr>
                                    <th class="col-sm-2">Tindakan</th>
                                    <th class="col-sm-2">Metode</th>
                                    <th class="col-sm-2">Status</th>
                                    <th class="col-sm-2">Ganti Status</th>
                                </tr>
                            </thead>
                            
                            <tbody>
                                @foreach($shipmethods as $shipmethod)
                                
                                @if($shipmethod->is_active)
                                <tr>
                                @else
                                <tr class="danger">
                                @endif
                                    <td>
                                        <a href="#" title="Delete Metode">
                                            <i class="fa fa-trash-o fa-fw fa-2x" data-toggle="modal" data-target="<?php echo '#myModal' . $shipmethod->id ?>"></i>
                                        </a>
                                        <div class="modal fade" id="<?php echo 'myModal' . $shipmethod->id ?>" tabindex="-1" role="dialog" aria-labelledby="<?php echo 'myModalLabel' . $shipmethod->id ?>" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                        <h4 class="modal-title" name="<?php echo 'myModalLabel' . $shipmethod->id ?>">Metode Deletion Confirmation</h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        Anda yakin mau menghapus metode pengiriman ini?
                                                    </div>
                                                    <div class="modal-footer">
                                                        <input type="button" class="btn btn-default" data-dismiss="modal" value="Batal"/>
                                                        <a href="{{ url('shipmethod/delete/' . $shipmethod->id) }}" class="btn btn-primary">Hapus</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                            
                                        <a href="{{ url('shipmethod/edit/' . $shipmethod->id) }}" title="Edit Metode"><i class="fa fa-pencil fa-fw fa-2x"></i></a>
                                    </td>
                                    <td>{{ $shipmethod->shipmethod_name . ' - ' . $shipmethod->shipmethod_type }}</td>
                                    <td>
                                        @if($shipmethod->is_active == 1)
                                        Aktif
                                        @else
                                        Tidak Aktif
                                        @endif
                                    </td>
                                    <td>
                                        <form method="post" action="{{ url('shipmethod/activate') }}" id="form-{{ $shipmethod->id }}">
                                            {!! csrf_field(); !!}
                                            <input type="hidden" name="method_id" value="{{ $shipmethod->id }}"/>
                                            <div class="form-group">
                                                <select name="status" class="form-control active-select" data-formid="#form-{{ $shipmethod->id }}">
                                                    <option value="">-- Please Choose --</option>
                                                    <option value="1"> Aktif </option>
                                                    <option value="0"> Tidak Aktif </option>
                                                </select>
                                            </div>
                                        </form>
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
        
        $('.active-select').change(function(){
            var formid = $(this).attr('data-formid');
            $(formid).submit();
        });
    </script>
    
@stop