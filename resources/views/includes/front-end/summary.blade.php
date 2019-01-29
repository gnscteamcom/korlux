<div class="col-md-3">
  <div id="order-summary" class="box black-white">
    <div class="box-header">
      <h3>Rincian Pesanan</h3>
    </div>
    <p class="text-muted">
        Biaya pengiriman dihitung berdasarkan berat barang.
        Seluruh total belanja dihitung secara otomatis.
    </p>
    <div class="table-responsive">
      <table class="table">
        <tbody>
          <tr>
            <td>Subtotal</td>
            <th>{!! 'Rp. ' . number_format(Cart::instance('main')->total(), 0, ',', '.') !!}</th>
          </tr>
          <tr>
            <td>Nominal Identifikasi</td>
            <th>{!! 'Rp. ' . number_format(Cart::instance('unique')->total(), 0, ',', '.') !!}</th>
          </tr>
          <tr class="total">
            <td>Total</td>
            <th>{!! 'Rp. ' . number_format(Cart::instance('total')->total(), 0, ',', '.') !!}</th>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>