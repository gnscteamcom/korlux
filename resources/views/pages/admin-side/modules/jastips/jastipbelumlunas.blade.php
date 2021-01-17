@extends('layouts.admin-side.default')


@section('title')
@parent
    Jastip Belum Lunas
@stop


@section('content')

    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Jastip Belum Lunas</h1>
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
                    Daftar Jastip Belum Lunas & Sudah dipesan
                </div>
                <div class="panel-body">
                    <div class="table-responsive">


                        <table class="table table-striped table-bordered table-hover" id="dataTables">
                            <thead>
                                <tr>
                                    <th>Tindakan</th>
                                    <th>Nomor Invoice</th>
                                    <th>Marketing</th>
                                    <th>Rincian Produk</th>
                                    <th>Rincian Pesanan</th>
                                    <th>Penerima</th>
                                    <th>Jumlah Pelunasan</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($jastips as $jastip)
                                <tr>
                                    <td>
                                      @if($jastip->is_lunas == 0)
                                      <a href="{{ URL::to('jastip/lunasnow/' . $jastip->id) }}" title="Sudah Lunas"><i class="fa fa-fw fa-2x fa-check"></i></a>
                                      @endif
                                    </td>
                                    <td>{{ $jastip->invoicenumber }}</td>
                                    <td>{{ $jastip->user->name }}</td>
                                    <td>
                                      @foreach($jastip->jastipdetails as $detail)
                                      {{ $detail->product_name . ' @Rp.' . number_format($detail->harga_rp) . ' x ' . $detail->qty . ' (' . number_format($detail->qty * $detail->weight) . ' gram)' }}<br />
                                      @endforeach
                                    </td>
                                    <td>
                                      <b>Total Berat: </b> {{ number_format($jastip->total_weight) }} gram<br />
                                      <b>Ongkos Kirim: </b> Rp. {{ number_format($jastip->total_weight) }}<br />
                                      <b>Nominal Unik: </b> Rp. {{ number_format($jastip->unique_nominal) }}<br />
                                      <b>Total Produk: </b> Rp. {{ number_format($jastip->grand_total) }}<br />
                                      <b>Total yang harus dibayar: </b> Rp. {{ number_format($jastip->total_paid) }}<br />
                                      <b>Total DP: </b> Rp. {{ number_format($jastip->total_dp) }}<br />
                                      <b>Total Pelunasan: </b> Rp. {{ number_format($jastip->total_pelunasan) }}<br />
                                    </td>
                                    <td>
                                      {{ $jastip->customeraddress->first_name }}<br />
                                      {{ $jastip->customeraddress->alamat }}<br />
                                      {{ $jastip->customeraddress->hp }}
                                    </td>
                                    <td><b>Rp. {{ number_format($jastip->total_pelunasan) }}</b></td>
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
