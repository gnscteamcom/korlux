@extends('layouts.admin-side.default')


@section('title')
@parent
    Import Harga
@stop


@section('content')
    
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Import Data Harga</h1>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-4">
            <div class="panel panel-info">
                <div class="panel-heading">
                    Tambah atau Import Harga
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="panel-body">
                                <a href="{{ URL::to('addprice') }}">
                                    <input type="button" value="Add Price" class="form-control btn btn-info" />
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="panel-body">
                                <a href="{{ URL::to('downloadpriceformat') }}">
                                    <input type="button" value="Download Format Excel" class="form-control btn btn-primary" />
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="panel panel-info">
                <div class="panel-heading">
                    Pilih file untuk diimport
                </div>
                <div class="panel-body">
                    
                    <form method="post" action="{{ URL::to('importprice') }}" enctype="multipart/form-data" class="form-horizontal">
                        {!! csrf_field() !!}
                                            
                        @if(Session::has('err'))
                        <div class="form-group">
                            <div class="col-sm-10 col-sm-offset-2">
                                <div class="form-group text-danger">
                                    {{ Session::get('err') }}
                                </div>
                            </div>
                        </div>
                        @endif


                        @if($errors->has('file'))
                        <div class="form-group">
                            <div class="col-sm-0 col-sm-offset-2">
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
                    Master Harga
                </div>
                <div class="panel-body">
                    <div class="table-responsive">

                        <div class="col-md-6 margin-bottom-20">
                            <form method="post" action="{{ URL::to('search/searchprice') }}">
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

                        <form method="post" action="{{ url('deleteprice/bulk') }}">
                            {!! csrf_field(); !!}
                        
                            <div class="row form-group">
                                <input type="submit" value="Hapus yang Dipilih" class="btn btn-block btn-success"/>
                            </div>
                            <div class="row form-group">
                                <div class="col-lg-2">
                                    <input type="button" value="Select All" id="select-all" class="btn btn-block btn-info" data-is_select="1"/>
                                </div>
                            </div>

                            
                            <table class="table table-striped table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th style="width:50px;">#</th>
                                        <th>Pilih</th>
                                        <th>Action</th>
                                        <th>Barcode</th>
                                        <th>Nama Produk</th>
                                        <th>Harga</th>
                                        <th>Harga Sale</th>
                                        <th>Mulai Berlaku</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php 
                                        $i = 1;
                                    ?>
                                    @foreach($prices as $price)

                                    @if($price->product)
                                    <tr>
                                        <td>{{ $i++ }}</td>
                                        <td class="text-center">
                                            <input type="checkbox" name="price[]" value="{{ $price->id }}" style="transform: scale(1.5);"/>
                                        </td>
                                        <td>
                                            <a href="{{ URL::to('editprice/' . $price->id) }}"><i class="fa fa-pencil fa-fw fa-2x"></i></a>

                                            <a href="#"><i class="fa fa-trash-o fa-fw fa-2x" data-toggle="modal" data-target="<?php echo '#myModal' . $price->id ?>"></i></a>
                                            <div class="modal fade" id="<?php echo 'myModal' . $price->id ?>" tabindex="-1" role="dialog" aria-labelledby="<?php echo 'myModalLabel' . $price->id ?>" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                            <h4 class="modal-title" name="<?php echo 'myModalLabel' . $price->id ?>">Konfirmasi</h4>
                                                        </div>
                                                        <div class="modal-body">
                                                            Apakah Anda yakin akan menghapus harga ini?
                                                        </div>
                                                        <div class="modal-footer">
                                                            <input type="button" value="Batal" class="btn btn-default" data-dismiss="modal" />
                                                            <a href="{{ URL::to('deleteprice/' . $price->id) }}" class="btn btn-primary" >Hapus</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            {!! '<b>' . $price->product->barcode . '</b>' !!}
                                        </td>
                                        <td>
                                            {!! '<b>' . $price->product->product_name . '</b>' !!}
                                        </td>
                                        <td>
                                            <b>Regular</b> : {!! 'Rp. ' . number_format($price->regular_price, 2, ',', '.') !!}<br>
                                            <b>Silver</b> : {!! 'Rp. ' . number_format($price->reseller_1, 2, ',', '.') !!}<br>
                                            <b>Gold</b> : {!! 'Rp. ' . number_format($price->reseller_2, 2, ',', '.') !!}<br>
                                            <b>Platinum</b> : {!! 'Rp. ' . number_format($price->vvip, 2, ',', '.') !!}<br>
                                        </td>
                                        <td>{!! 'Rp. ' . number_format($price->sale_price, 2, ',', '.') !!}</td>
                                        <td>{{ date('d F Y', strtotime($price->valid_date)) }}</td>
                                    </tr>
                                    @endif

                                    @endforeach
                                </tbody>
                            </table>
                        </form>

                        {!! $prices->links() !!}

                    </div>
                </div>
            </div>
        </div>
    </div>        
        
        
</div>

@stop

@section('script')
<script>
    $("#select-all").click(function(){
        var is_checked = $(this).attr('data-is_select');
        if(is_checked == 1){
            $('input:checkbox').prop('checked', true);
            $(this).attr('data-is_select', 0);
            $(this).val('Unselect All');
        }else{
            $('input:checkbox').prop('checked', false);
            $(this).attr('data-is_select', 1);
            $(this).val('Select All');
        }
    });
</script>
@endsection