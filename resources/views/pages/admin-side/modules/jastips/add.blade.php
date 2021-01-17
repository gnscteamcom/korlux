@extends('layouts.admin-side.default')


@section('title')
@parent
    New Jastip
@stop


@section('content')

    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Tambah Jastip Baru</h1>
        </div>
    </div>

    @if(Session::has('msg'))
    <div class="row">
        <div class="col-lg-12">
            <div class="form-group text-danger">
                <div class="alert alert-success alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <span id="copy-text">
                        {!! '<b>' . Session::get('msg') . '</b>' !!}
                    </span>
                    <button type="button" class="btn btn-default btn-info" id="copy-btn"><span aria-hidden="true">Copy Text</span></button>
                </div>
            </div>
        </div>
    </div>
    @endif


    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-info">
                <div class="panel-heading">
                    Input data anda
                </div>
                <div class="panel-body">

                    <form method="post" action="{{ URL::to('jastip/add') }}">
                        {!! csrf_field() !!}

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

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="nama_marketing">Nama Marketing</label>
                                    <input type="hidden" name="marketing_id" value="{{ $marketing_id }}" />
                                    <input type="text" name="nama_marketing" id="nama_marketing" class="form-control" placeholder="Nama Marketing" value="{{ $marketing_name }}" readonly/>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="inisial_marketing">Inisial Marketing</label>
                                    <input type="text" name="inisial_marketing" id="inisial_marketing" class="form-control" placeholder="Inisial Marketing" value="{{ $marketing_initial }}" readonly/>
                                </div>
                            </div>
                        </div>
                        <hr />

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="nama_pembeli">Nama Pembeli</label>
                                    <input type="text" name="nama_pembeli" id="nama_pembeli" class="form-control" placeholder="Nama Pembeli" required/>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="hp_pembeli">HP Pembeli</label>
                                    <input type="text" name="hp_pembeli" id="hp_pembeli" class="form-control" placeholder="HP Pembeli" required/>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="alamat_pembeli">Alamat Pembeli</label>
                                    <textarea class="form-control" rows="5" style="resize:none" id="alamat_pembeli" name="alamat_pembeli" placeholder="Alamat Pembeli" required></textarea>
                                </div>
                            </div>
                        </div>
                        <hr />

                        <div class="row">
                            <div class="col-md-12">
                                <h3>Input Data Produk</h3>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nama_produk">Nama Produk & Varian</label>
                                    <input type="text" name="nama_produk" id="nama_produk" class="form-control" placeholder="Nama Produk"/>
                                    <span class="text-danger hidden" id="nama_produk_error">Harus diisi</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="qty">Qty</label>
                                    <input type="number" name="qty" id="qty" class="form-control" placeholder="Qty" min="0"/>
                                    <span class="text-danger hidden" id="qty_error">Harus diisi</span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="harga_barang_won">Harga Barang Satuan (won)</label>
                                    <input type="number" name="harga_barang_won" id="harga_barang_won" class="form-control" placeholder="Harga Barang Satuan (won)" min="0"/>
                                    <span class="text-danger hidden" id="harga_won_error">Harus diisi</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="perkiraan_berat_satuan">Perkiraan Berat Satuan (gram)</label>
                                    <input type="number" name="perkiraan_berat_satuan" id="perkiraan_berat_satuan" class="form-control" placeholder="Perkiraan Berat Satuan (gram)" min="0"/>
                                    <span class="text-danger hidden" id="berat_error">Harus diisi</span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="harga_barang_rp">Harga Barang Satuan (Rp.)</label>
                                    <input type="number" name="harga_barang_rp" id="harga_barang_rp" class="form-control" placeholder="Harga Barang Satuan (Rp.)" min="0"/>
                                    <span class="text-danger hidden" id="harga_rp_error">Harus diisi</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="link_produk">Link Produk</label>
                                    <input type="text" name="link_produk" id="link_produk" class="form-control" placeholder="Link Produk"/>
                                    <span class="text-danger hidden" id="link_produk_error">Harus diisi</span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <input type="button" value="Tambah produk" class="btn btn-default btn-primary col-md-12" onclick="addProduct();">
                            </div>
                        </div>
                        <hr />

                        <div class="row">
                            <div class="col-md-12">
                                <h2>
                                    Daftar Jastip
                                </h2>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                              <div class="table-responsive">
                                  <table class="table table-striped table-bordered table-hover">
                                      <thead>
                                          <tr class="info">
                                              <th>#</th>
                                              <th class="text-center">Produk</th>
                                              <th>Qty</th>
                                              <th>Total Berat (gram)</th>
                                              <th>Total Harga (won)</th>
                                              <th>Total Harga (Rp.)</th>
                                              <th>Link</th>
                                              <th class="text-center"> </th>
                                          </tr>
                                      </thead>

                                      <input type="hidden" id="products_list" name="products_list" value=""/>
                                      <input type="hidden" id="grand_total_rp" name="grand_total_rp" value="0" />
                                      <input type="hidden" id="total_weight" name="total_weight" value="0" />
                                      <tbody id="products_table">
                                      </tbody>

                                      <tfoot>
                                        <tr class="info">
                                          <th colspan="2">Total Qty</th>
                                          <td id="grand_total_qty"></td>
                                        </tr>
                                        <tr class="info">
                                          <th colspan="3">Total Berat</th>
                                          <td id="grand_total_berat"></td>
                                        </tr>
                                        <tr class="info">
                                          <th colspan="4">Total Won</th>
                                          <td id="grand_total_won"></td>
                                        </tr>
                                        <tr class="info">
                                          <th colspan="5">Total Rupiah</th>
                                          <td id="grand_total_rp_text"></td>
                                        </tr>
                                      </tfoot>
                                  </table>
                              </div>
                            </div>
                        </div>

                        <hr />

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="unique_nominal">Nominal Unik</label>
                                    <input type="number" name="unique_nominal" id="unique_nominal" class="form-control" placeholder="Unique Nominal" value="{{ rand(1, 400) }}" readonly/>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="total_ongkos_kirim">Ongkos Kirim</label>
                                    <input type="number" name="total_ongkos_kirim" id="total_ongkos_kirim" class="form-control" placeholder="Total Ongkos Kirim" min="0" value="0" required/>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="total_paid_text">Total yang harus dibayar</label>
                                    <input type="hidden" name="total_paid" id="total_paid" value="0"/>
                                    <input type="text" name="total_paid_text" id="total_paid_text" class="form-control" placeholder="Total yang harus dibayar" readonly/>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="total_payment">Total DP / Pembayaran</label>
                                    <input type="number" name="total_payment" id="total_payment" class="form-control" placeholder="Total DP / Pembayaran" required/>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="total_pelunasan_text">Sisa yang harus dilunasi apabila DP (extra 3%)</label>
                                    <input type="hidden" name="total_pelunasan" id="total_pelunasan" value="0"/>
                                    <input type="text" name="total_pelunasan_text" id="total_pelunasan_text" class="form-control" placeholder="Sisa yang harus dilunasi apabila DP" readonly/>
                                </div>
                            </div>
                        </div>




                        <div class="row">
                            <div class="col-lg-6">
                                <input type="submit" class="btn btn-default btn-success btn-block" value="Simpan Jastip"/>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@include('includes.admin-side.validation')

@stop

@section('script')
<script>
  $('#total_ongkos_kirim').keyup(function() {
    calculateTotalPaid()
  })

  $('#total_payment').keyup(function() {
    let total_payment = $(this).val()
    let total_paid = $('#total_paid').val()
    if(parseInt(total_payment, 10) > parseInt(total_paid, 10)) {
      $(this).val(total_paid)
    }

    calculateSisaPelunasan()
  })

  addProduct = () => {
    //inisialisasi array
    let products_list = $('#products_list').val()
    if(products_list) {
      products_list = JSON.parse(products_list)
    } else {
      products_list = []
    }


    //ambil seluruh data produk
    let nama_produk = $('#nama_produk').val()
    let qty = $('#qty').val()
    let harga_barang_won = $('#harga_barang_won').val()
    let harga_barang_rp = $('#harga_barang_rp').val()
    let perkiraan_berat_satuan = $('#perkiraan_berat_satuan').val()
    let link_produk = $('#link_produk').val()

    //hilangkan semua error dahulu
    $('#nama_produk_error').addClass('hidden')
    $('#qty_error').addClass('hidden')
    $('#harga_won_error').addClass('hidden')
    $('#harga_rp_error').addClass('hidden')
    $('#berat_error').addClass('hidden')
    $('#link_produk_error').addClass('hidden')

    //validasi
    let is_validated = true
    if(nama_produk.length <= 0) {
      $('#nama_produk_error').removeClass('hidden')
      is_validated = false
    }
    if (qty <= 0) {
      $('#qty_error').removeClass('hidden')
      is_validated = false
    }
    if (harga_barang_won <= 0) {
      $('#harga_won_error').removeClass('hidden')
      is_validated = false
    }
    if (harga_barang_rp <= 0) {
      $('#harga_rp_error').removeClass('hidden')
      is_validated = false
    }
    if (perkiraan_berat_satuan <= 0) {
      $('#berat_error').removeClass('hidden')
      is_validated = false
    }
    if (link_produk <= 0) {
      $('#link_produk_error').removeClass('hidden')
      is_validated = false
    }

    if(is_validated) {
      //siapkan data
      let data = {
        nama_produk: nama_produk,
        qty: qty,
        harga_barang_won: harga_barang_won,
        harga_barang_rp: harga_barang_rp,
        perkiraan_berat_satuan: perkiraan_berat_satuan,
        link_produk: link_produk
      }

      //tambahkan ke array-nya
      products_list.push(data)
      $('#products_list').val(JSON.stringify(products_list))

      //refresh table produk jastip
      refreshTable(products_list)


      //hapus semua input produk jastip
      $('#nama_produk').val('')
      $('#qty').val('')
      $('#harga_barang_won').val('')
      $('#harga_barang_rp').val('')
      $('#perkiraan_berat_satuan').val('')
      $('#link_produk').val('')
    }

  }

  deleteRow = (rowid) => {
    //ambil daftar array
    let products_list = $('#products_list').val()
    if(products_list) {
      products_list = JSON.parse(products_list)
      products_list.splice(rowid, 1)
      products_list = JSON.stringify(products_list)
      $('#products_list').val(products_list)
    }

    //refresh tabel
    refreshTable(JSON.parse(products_list))
  }

  refreshTable = (products_list) => {
    let grand_total_qty = 0
    let grand_total_berat = 0
    let grand_total_won = 0
    let grand_total_rp = 0

    $('#products_table').empty()
    let products_html = products_list.map((o, i) => {
      grand_total_qty += parseInt(o.qty)
      grand_total_berat += o.qty * o.perkiraan_berat_satuan
      grand_total_won += o.qty * o.harga_barang_won
      grand_total_rp += o.qty * o.harga_barang_rp

      return `<tr id="productrow${i}">
        <td>${i+1}</td>
        <td>${o.nama_produk}</td>
        <td>${o.qty}</td>
        <td>${(o.qty * o.perkiraan_berat_satuan).toLocaleString()}</td>
        <td>${(o.qty * o.harga_barang_won).toLocaleString()}</td>
        <td>Rp. ${(o.qty * o.harga_barang_rp).toLocaleString()}</td>
        <td>${o.link_produk}</td>
        <td><button type="button" onclick="deleteRow(${i})"><i class="fa fa-fw fa-times"></i></button></td>
      </tr>`
    })
    $('#products_table').append(products_html)

    //tampilin grand total
    $('#grand_total_qty').text(grand_total_qty.toLocaleString())
    $('#total_weight').val(grand_total_berat)
    $('#grand_total_berat').text(grand_total_berat.toLocaleString())
    $('#grand_total_won').text(grand_total_won.toLocaleString())
    $('#grand_total_rp_text').text(`Rp. ${grand_total_rp.toLocaleString()}`)
    $('#grand_total_rp').val(grand_total_rp)

    //hitung ulang total pembayaran
    calculateTotalPaid()
  }

  calculateTotalPaid = () => {
    let total_ongkos_kirim = parseInt($('#total_ongkos_kirim').val())
    let unique_nominal = parseInt($('#unique_nominal').val())
    let grand_total_rp = parseInt($('#grand_total_rp').val())

    let total_paid = total_ongkos_kirim + unique_nominal + grand_total_rp

    $('#total_paid').val(total_paid)
    $('#total_paid_text').val(`Rp. ${total_paid.toLocaleString()}`)

    calculateSisaPelunasan()
  }

  calculateSisaPelunasan = () => {
    let total_paid = $('#total_paid').val()
    let total_dp = $('#total_payment').val()
    let total_pelunasan = total_paid - total_dp

    if(total_pelunasan < 0) {
      total_pelunasan = 0
    } else {
      total_pelunasan = Math.ceil(total_pelunasan * 1.03)
    }

    $('#total_pelunasan').val(total_pelunasan)
    $('#total_pelunasan_text').val(`Rp. ${total_pelunasan.toLocaleString()}`)
  }

  $('#copy-btn').click(function(){
      var range = document.createRange();
      range.selectNode(document.getElementById('copy-text'));
      window.getSelection().addRange(range);
      document.execCommand("Copy");
  });
</script>
@stop
