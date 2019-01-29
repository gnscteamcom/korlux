@extends('layouts.admin-side.default')


@section('title')
@parent
    Daftar Harga Grosir
@stop


@section('content')

    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Harga Grosir</h1>
        </div>
    </div>


    <div class="row">
        <div class="col-lg-6">
            <div class="panel panel-info">
                <div class="panel-heading">
                    Harga Grosir
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12">
                            <a href="{{ URL::to('addwholesaleprice') }}">
                                <input type="button" value="Tambah Harga Grosir" class="form-control btn btn-primary" />
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6">
            <div class="panel panel-info">
                <div class="panel-heading">
                    Import Harga Grosir
                </div>
                <div class="panel-body">
                    <div class="row"><div class="col-md-12 margin-top-20">
                            <a href="{{ URL::to('downloadwholesaleformat') }}">
                                <input type="button" value="Unduh Format Excel" class="form-control btn btn-primary" />
                            </a>  
                            <div class="row margin-top-20">
                                <div class="col-md-12">
                                    <form method="post" action="{{ URL::to('importwholesale') }}" enctype="multipart/form-data" class="form-horizontal">
                                        {!! csrf_field() !!}
                                                            
                                        @if($errors->has('file'))
                                        <div class="form-group">
                                            <div class="col-sm-4 col-sm-offset-2">
                                                <div class="form-group text-danger">
                                                    {{ $errors->first('file') }}
                                                </div>
                                            </div>
                                        </div>
                                        @endif


                                        @if($errors->has('file'))
                                        <div class="form-group has-error">
                                        @else
                                        <div class="form-group">
                                        @endif
                                            <div class="col-sm-2 control-label">
                                                <label for="file">File</label>
                                            </div>
                                            <div class="col-sm-10">
                                                <input type="file" name="file" id="file"/>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-lg-12">
                                                <input type="submit" class="btn btn-default btn-success btn-block" value="Import" />
                                            </div>
                                        </div>
                                        
                                    </form>
                                </div>
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(Session::has('msg'))
    <div class="row">
        <div class="col-lg-12">
            <div class="form-group text-success">
                {!! '<b>' . Session::get('msg') . '</b>' !!}
            </div>
        </div>
    </div>
    @endif

    @if(Session::has('err'))
    <div class="row">
        <div class="col-lg-12">
            <div class="form-group text-danger">
                {!! '<b>' . Session::get('err') . '</b>' !!}
            </div>
        </div>
    </div>
    @endif
        
        
        
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-info">
                <div class="panel-heading">
                    Daftar Harga Grosir
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <div class="col-md-6 margin-bottom-20">
                            <form method="post" action="{{ URL::to('search/searchwholesale') }}">
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
                                    <th> </th>
                                    <th>Nama Produk</th>
                                    <th>Kode Produk</th>
                                    <th>Untuk</th>
                                    <th>Min Qty</th>
                                    <th>Harga Satuan</th>
                                    <th>Tanggal Mulai Berlaku</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php $a = 0; ?>
                                @foreach($discountqties as $discountqty)
                                <?php $a++; ?>
                                <tr>
                                    <td>
                                        <a href="#" title="Remove All Wholesale Price">
                                            <i class="fa fa-trash-o fa-fw" data-toggle="modal" data-target="<?php echo '#deleteModal' . $a ?>"></i>
                                        </a>
                                        <div class="modal fade" id="<?php echo 'deleteModal' . $a ?>" tabindex="-1" role="dialog" aria-labelledby="<?php echo 'deleteModalLabel' . $a ?>" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                        <h4 class="modal-title" name="<?php echo 'deleteModalLabel' . $a ?>">Hapus Harga Grosir</h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        Apakah Anda yakin ingin menghapus seluruh harga grosir ini?
                                                    </div>
                                                    <form method="post" action="{{ url('deleteallwholesale') }}">
                                                        {!! csrf_field(); !!}
                                                        <input type="hidden" name="product_ids" value="{{ $discountqty['product_ids'] }}" />
                                                        <div class="modal-footer">
                                                            <button class="btn btn-default" data-dismiss="modal"> Batal </button>
                                                            <button type="submit" class="btn btn-primary"> Hapus</a>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        {!! $discountqty['product_name'] !!}
                                    </td>
                                    <td>
                                        {!! $discountqty['product_code'] !!}
                                    </td>
                                    <td>
                                        {!! $discountqty['status'] !!}
                                    </td>
                                    <td>{!! $discountqty['min_qty'] !!}</td>
                                    <td>
                                        @foreach($discountqty['price'] as $price)
                                        <a href="#" title="Remove Wholesale Price">
                                            <i class="fa fa-trash-o fa-fw" data-toggle="modal" data-target="<?php echo '#myModal' . $price['id'] ?>"></i>
                                        </a>
                                        <div class="modal fade" id="<?php echo 'myModal' . $price['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="<?php echo 'myModalLabel' . $price['id'] ?>" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                        <h4 class="modal-title" name="<?php echo 'myModalLabel' . $price['id'] ?>">Hapus Harga Grosir</h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        Apakah Anda yakin ingin menghapus harga grosir ini?
                                                    </div>
                                                    <div class="modal-footer">
                                                        <input type="button" class="btn btn-default" data-dismiss="modal" value="Batal"/>
                                                        <a href="{{ URL::to('deletewholesaleprice/' . $price['id']) }}" class="btn btn-primary" >Hapus</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <a href="{{ URL::to('editwholesaleprice/' . $price['id']) }}"><i class="fa fa-pencil fa-fw"></i></a>
                                        {!! $price['price'] !!}
                                        <br>
                                        @endforeach
                                    </td>
                                    <td>{!! $discountqty['created_at'] !!}</td>
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