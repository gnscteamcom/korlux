@extends('layouts.admin-side.default')


@section('title')
@parent
    Master Bank
@stop


@section('content')
    
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Bank</h1>
        </div>
    </div>


    <div class="row">
        <div class="col-lg-4">
            <div class="panel-body">
                <a href="{{ URL::to('addbank') }}">
                    <input type="button" value="Tambah Bank" class="form-control btn btn-primary" />
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

    @if(Session::has('err'))
    <div class="row">
        <div class="col-lg-12">
            <div class="form-group text-danger">
                <div class="alert alert-warning alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        {!! '<b>' . Session::get('err') . '</b>' !!}
                </div>
            </div>
        </div>
    </div>
    @endif

        
        
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-info">
                <div class="panel-heading">
                    Bank
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        
                        
                        <table class="table table-striped table-bordered table-hover" id="dataTables">
                            <thead>
                                <tr>
                                    <th class="col-sm-2">Tindakan</th>
                                    <th class="col-sm-2">Nama Bank</th>
                                    <th class="col-sm-2">Nomor Rekening</th>
                                    <th class="col-sm-2">Nama Rekening</th>
                                </tr>
                            </thead>
                            
                            <tbody>
                                @foreach($banks as $bank)
                                <tr>
                                    <td>
                                        <a href="#" title="Remove Bank">
                                            <i class="fa fa-trash-o fa-fw fa-2x" data-toggle="modal" data-target="<?php echo '#myModal' . $bank->id ?>"></i>
                                        </a>
                                        <div class="modal fade" id="<?php echo 'myModal' . $bank->id ?>" tabindex="-1" role="dialog" aria-labelledby="<?php echo 'myModalLabel' . $bank->id ?>" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                        <h4 class="modal-title" name="<?php echo 'myModalLabel' . $bank->id ?>">Konfirmasi Penghapusan Bank</h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        Anda yakin mau menghapus bank ini?
                                                    </div>
                                                    <div class="modal-footer">
                                                        <input type="button" class="btn btn-default" data-dismiss="modal" value="Batal" />
                                                        <a href="{{ URL::to('deletebank/' . $bank->id) }}" class="btn btn-primary">Hapus</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                            
                                        <a href="{{ URL::to('editbank/' . $bank->id) }}" title="Edit Bank"><i class="fa fa-pencil fa-fw fa-2x"></i></a>
                                    </td>
                                    <td>{{ $bank->bank_name }}</td>
                                    <td>{{ $bank->bank_account }}</td>
                                    <td>{{ $bank->bank_account_name }}</td>
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
        
    <script>
        $(document).ready(function() {
            $('#dataTables').dataTable();
        });
    </script>
    
@stop