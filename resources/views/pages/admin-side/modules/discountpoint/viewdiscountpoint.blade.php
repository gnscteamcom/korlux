@extends('layouts.admin-side.default')


@section('title')
@parent
    Poin Loyalty
@stop


@section('content')
    
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Poin Loyalty</h1>
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
        <div class="col-lg-6">
            <div class="panel panel-info">
                <div class="panel-heading">
                    Aktifkan Poin Loyalty
                </div>
                <div class="panel-body">
                    
                    <form method="post" action="{{ URL::to('discountpoint/activate') }}" enctype="multipart/form-data" class="form-horizontal">
                        {!! csrf_field() !!}


                        <div class="row">
                            <div class="col-lg-12">
                                Status saat ini : {{ $config->is_active ? 'aktif' : 'nonaktif' }}
                            </div>
                        </div>
                            

                        <div class="row">
                            <div class="col-lg-12">
                                @if($config->is_active)
                                <input type="submit" class="btn btn-default btn-danger btn-block" value="Nonaktifkan" />
                                @else
                                <input type="submit" class="btn btn-default btn-success btn-block" value="Aktifkan" />
                                @endif
                            </div>
                        </div>
                        
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="panel panel-info">
                <div class="panel-heading">
                    Kosongkan Seluruh Poin
                </div>
                <div class="panel-body">
                    
                    <form method="post" action="{{ URL::to('discountpoint/refresh') }}" enctype="multipart/form-data" class="form-horizontal">
                        {!! csrf_field() !!}


                        <div class="row">
                            <div class="col-lg-12">
                                Jumlah user yang memiliki poin > 0 : {{ $total_user }}
                            </div>
                        </div>
                            

                        <div class="row">
                            <div class="col-lg-12">
                                <input type="submit" class="btn btn-default btn-danger btn-block" value="Kosongkan" />
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
                <a href="{{ URL::to('discountpoint/add') }}">
                    <input type="button" value="Tambah Point" class="form-control btn btn-primary" />
                </a>
            </div>
        </div>
    </div>
        
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-info">
                <div class="panel-heading">
                    Poin Loyalty
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        
                        
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th class="col-sm-2">Tindakan</th>
                                    <th>Nominal Minimal</th>
                                    <th>Nominal Maksimal</th>
                                    <th>Persentase Poin</th>
                                </tr>
                            </thead>
                            
                            <tbody>
                                @foreach($points as $point)
                                <tr>
                                    <td>
                                        <a href="#" title="Remove Point">
                                            <i class="fa fa-trash-o fa-fw fa-2x" data-toggle="modal" data-target="<?php echo '#myModal' . $point->id ?>"></i>
                                        </a>
                                        <div class="modal fade" id="<?php echo 'myModal' . $point->id ?>" tabindex="-1" role="dialog" aria-labelledby="<?php echo 'myModalLabel' . $point->id ?>" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                        <h4 class="modal-title" name="<?php echo 'myModalLabel' . $point->id ?>">Konfirmasi Penghapusan Point</h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        Anda yakin mau menghapus point ini?
                                                    </div>
                                                    <div class="modal-footer">
                                                        <input type="button" class="btn btn-default" data-dismiss="modal" value="Batal" />
                                                        <a href="{{ URL::to('discountpoint/delete/' . $point->id) }}" class="btn btn-primary">Hapus</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                            
                                        <a href="{{ URL::to('discountpoint/edit/' . $point->id) }}" title="Edit Point"><i class="fa fa-pencil fa-fw fa-2x"></i></a>
                                    </td>
                                    <td><b>{{ 'Rp. ' . number_format($point->minimal_amount, 2, ',', '.') }}</b></td>
                                    <td><b>{{ 'Rp. ' . number_format($point->maximal_amount, 2, ',', '.') }}</b></td>
                                    <td><b>{{ number_format($point->point_percentage, 2, ',', '.') . '%'}}</b></td>
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