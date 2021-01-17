@extends('layouts.admin-side.default')


@section('title')
@parent
    Jastip Siap Kirim
@stop


@section('content')

    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Jastip Siap Kirim</h1>
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

    @if($errors->any())
    <div class="row">
        <div class="col-lg-6">
            <div class="alert alert-danger alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <span>
                    {!! implode('', $errors->all('<div>:message</div>')) !!}
                </span>
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
                    Daftar Jastip Siap Kirim, Sudah Dipesan dan Sudah Lunas
                </div>
                <div class="panel-body">
                    <div class="table-responsive">

                      <form method="post" class="margin-top-20" action="{{ URL::to('jastip/printalldo') }}" target="_blank">
                      {!! csrf_field() !!}

                        <div class="row form-group">
                            <input type="submit" value="Cetak Semua Pesanan" class="btn btn-block btn-primary"/>
                        </div>
                      </form>

                      <form method="post" action="{{ URL::to('jastip/printdo') }}" target="_blank">
                      {!! csrf_field() !!}
                        <input type="hidden" name="jastips_list" id="jastips_list" value="" />

                        <div class="row form-group">
                            <input type="submit" value="Cetak yang Dipilih" class="btn btn-block btn-success"/>
                        </div>

                        <div class="row form-group">
                            <div class="col-lg-2">
                                <input type="button" value="Select All" id="select-all" class="btn btn-block btn-info" data-is_select="1"/>
                            </div>
                        </div>


                          <table class="table table-striped table-bordered table-hover" id="dataTables">
                              <thead>
                                  <tr>
                                      <th>Tindakan</th>
                                      <th>Nomor Invoice</th>
                                      <th>Marketing</th>
                                      <th>Rincian Produk</th>
                                      <th>Rincian Pesanan</th>
                                      <th>Penerima</th>
                                      <th>Tanggal Lunas</th>
                                      <th>Sudah Beli ?</th>
                                      <th>Sudah Lunas ?</th>
                                      <th>Sudah Print ?</th>
                                  </tr>
                              </thead>

                              <tbody>
                                  @foreach($jastips as $jastip)
                                  <tr>
                                      <td>
                                        @if($jastip->is_print == 0)
                                        <input type="checkbox" onclick="addJastipList({{$jastip->id}})" value="{{ $jastip->id }}" style='height: 25px; width: 25px;'/>
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
                                      <td>{{ $jastip->payment_date != null ? date_format(new DateTime($jastip->payment_date), 'd M Y') : '' }}</td>
                                      <td>
                                        {{ $jastip->has_ordered == 1 ? 'Sudah' : 'Belum' }} <br />
                                        (<b>{{ $jastip->ordered_by_name }}</b>)
                                      </td>
                                      <td>
                                        {{ $jastip->is_lunas == 1 ? 'Sudah' : 'Belum' }}<br />
                                        (<b>{{ $jastip->lunas_by_name }}</b>)
                                      </td>
                                      <td>
                                        {{ $jastip->is_print == 1 ? 'Sudah' : 'Belum' }}<br />
                                        @if($jastip->is_print == 1)
                                        (<b>{{ $jastip->print_by_name }}</b>)
                                        @endif
                                      </td>
                                  </tr>
                                  @endforeach
                              </tbody>
                          </table>
                        </form>


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

$('.printClass').change(() => {
  let jastips_list = $('#jastips_list').val()
  if(jastips_list.length > 0) {
    jastips_list = JSON.parse(jastips_list)
  } else {
    jastips_list = []
  }

  let jastip_id = $(this).length

  console.log(jastip_id);
})

addJastipList = (jastip_id) => {
  let jastips_list = $('#jastips_list').val()
  if(jastips_list.length > 0) {
    jastips_list = JSON.parse(jastips_list)
  } else {
    jastips_list = []
  }

  let index = jastips_list.indexOf(jastip_id)
  if(index >= 0) {
    jastips_list.splice(index, 1)
  } else {
    jastips_list.push(jastip_id)
  }

  $('#jastips_list').val(JSON.stringify(jastips_list))
}
</script>
@stop
