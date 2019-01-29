@extends('layouts.admin-side.default')


@section('title')
@parent
    Master Kupon Diskon
@stop


@section('content')
    
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Kupon Diskon</h1>
        </div>
    </div>


    <div class="row">
        <div class="col-lg-4">
            <div class="panel-body">
                <a href="{{ URL::to('adddiscountcoupon') }}">
                    <input type="button" value="Tambah Kupon Diskon" class="form-control btn btn-primary" />
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
                <div class="alert alert-success alert-dismissible" role="alert">
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
                    Diskon Kupon
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        
                        
                        <table class="table table-striped table-bordered table-hover" id="dataTables">
                            <thead>
                                <tr>
                                    <th class="col-sm-2">Tindakan</th>
                                    <th class="col-sm-2">Kode Kupon</th>
                                    <th class="col-sm-2">Tanggal Berlaku</th>
                                    <th class="col-sm-2">Tanggal Berakhir</th>
                                    <th class="col-sm-2">Nominal Diskon</th>
                                    <th class="col-sm-2">Jumlah Berlaku</th>
                                </tr>
                            </thead>
                            
                            <tbody>
                                @foreach($discount_coupons as $discount_coupon)
                                <tr>
                                    <td>
                                        <a href="#" title="Hapus Kupon Diskon">
                                            <i class="fa fa-trash-o fa-fw fa-2x" data-toggle="modal" data-target="<?php echo '#myModal' . $discount_coupon->id ?>"></i>
                                        </a>
                                        <div class="modal fade" id="<?php echo 'myModal' . $discount_coupon->id ?>" tabindex="-1" role="dialog" aria-labelledby="<?php echo 'myModalLabel' . $discount_coupon->id ?>" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                        <h4 class="modal-title" name="<?php echo 'myModalLabel' . $discount_coupon->id ?>">Konfirmasi Penghapusan Kupon Diskon</h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        Anda yakin mau menghapus kupon diskon ini?
                                                    </div>
                                                    <div class="modal-footer">
                                                        <input type="button" class="btn btn-default" data-dismiss="modal" value="Batal" />
                                                        <a href="{{ URL::to('deletediscountcoupon/' . $discount_coupon->id) }}" class="btn btn-primary">Hapus</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                            
                                        <a href="{{ URL::to('editdiscountcoupon/' . $discount_coupon->id) }}" title="Edit Kupon Diskon"><i class="fa fa-pencil fa-fw fa-2x"></i></a>
                                    </td>
                                    <td>
                                        <b>{{ $discount_coupon->coupon_code }}</b><br>
                                        {{ $discount_coupon->status->status }}
                                    </td>
                                    <td><b>{{ date('d F Y', strtotime($discount_coupon->valid_date)) }}</b></td>
                                    <td><b>{{ date('d F Y', strtotime($discount_coupon->expired_date)) }}</b></td>
                                    <td>
                                        <b>
                                            @if($discount_coupon->percentage_discount > 0)
                                            {{ $discount_coupon->percentage_discount . ' %' }}
                                            @else
                                            {{ 'Rp. ' . number_format($discount_coupon->nominal_discount, 2, ',', '.') }}
                                            @endif
                                        </b>
                                    </td>
                                    <td><b>{{ $discount_coupon->available_count }}</b></td>
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