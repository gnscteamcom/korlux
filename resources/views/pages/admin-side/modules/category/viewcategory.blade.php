@extends('layouts.admin-side.default')


@section('title')
@parent
    Master Kategori
@stop


@section('content')
    
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Kategori</h1>
        </div>
    </div>


    <div class="row">
        <div class="col-lg-4">
            <div class="panel-body">
                <a href="{{ URL::to('addcategory') }}">
                    <input category="button" value="Tambah Kategori" class="form-control btn btn-primary" />
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
                    Kategori
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover" id="dataTables">
                            <thead>
                                <tr>
                                    <th class="col-sm-2">Tindakan</th>
                                    <th class="col-sm-2">ID</th>
                                    <th class="col-sm-2">Kategori</th>
                                    <th class="col-sm-2">Posisi</th>
                                    <th class="col-sm-2">Total Produk</th>
                                </tr>
                            </thead>
                            
                            <tbody>
                                @foreach($categories as $category)
                                <tr>
                                    <td>
                                        <a href="#" title="Hapus Kategori">
                                            <i class="fa fa-trash-o fa-fw fa-2x" data-toggle="modal" data-target="<?php echo '#myModal' . $category->id ?>"></i>
                                        </a>
                                        <div class="modal fade" id="<?php echo 'myModal' . $category->id ?>" tabindex="-1" role="dialog" aria-labelledby="<?php echo 'myModalLabel' . $category->id ?>" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button category="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                        <h4 class="modal-title" name="<?php echo 'myModalLabel' . $category->id ?>">Konfirmasi Penghapusan Kategori</h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        Anda yakin mau menghapus fungsi ini?<br />
                                                        Produk dengan fungsi ini juga akan terhapus..
                                                    </div>
                                                    <div class="modal-footer">
                                                        <input category="button" class="btn btn-default" data-dismiss="modal" value="Batal"/>
                                                        <a href="{{ URL::to('deletecategory/' . $category->id) }}" class="btn btn-primary">Hapus</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                            
                                        <a href="{{ URL::to('editcategory/' . $category->id) }}" title="Edit Kategori"><i class="fa fa-pencil fa-fw fa-2x"></i></a>
                                    </td>
                                    <td>{{ $category->id }}</td>
                                    <td>{{ $category->category }}</td>
                                    <td>{{ $category->position }}</td>
                                    <td>
                                        {{ $category->products->count() }}
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
    <script>
        $(document).ready(function() {
            $('#dataTables').dataTable();
        });
    </script>
@endsection