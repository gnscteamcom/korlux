<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Bank;
use App\Customeraddress;
use App\Jastip;
use App\Jastipdetail;

class JastipController extends Controller {

  public function addJastip() {
    $user = auth()->user();

    $marketing_initial = substr($user->name, 0, 1);

    return view('pages.admin-side.modules.jastips.add')->with([
      'marketing_id' => $user->id,
      'marketing_name' => $user->name,
      'marketing_initial' => $marketing_initial
    ]);
  }

  public function insertJastip(Request $request) {

    $this->validate($request, [
      'marketing_id' => 'required',
      'nama_marketing' => 'required',
      'inisial_marketing' => 'required',
      'nama_pembeli' => 'required',
      'hp_pembeli' => 'required',
      'alamat_pembeli' => 'required',
      'products_list' => 'required',
      'grand_total_rp' => 'required',
      'unique_nominal' => 'required',
      'total_ongkos_kirim' => 'required',
      'total_paid' => 'required',
      'total_payment' => 'required'
    ]);

    $products_list = json_decode($request->products_list);

    //save customer address
    $customer = new Customeraddress;
    $customer->user_id = $request->marketing_id;
    $customer->address_name = "";
    $customer->first_name = $request->nama_marketing;
    $customer->last_name = "";
    $customer->alamat = $request->alamat_pembeli;
    $customer->kecamatan_id = 0;
    $customer->kecamatan = "";
    $customer->provinsi = "";
    $customer->kodepos = "";
    $customer->hp = $request->hp_pembeli;
    $customer->save();

    //save jastipnya
    $jastip = new Jastip;
    $jastip->user_id = $request->marketing_id;
    $jastip->invoicenumber = Custom\OrderFunction::jastipInvoiceNumber($request->inisial_marketing);
    $jastip->total_weight = $request->total_weight;
    $jastip->shipment_cost = $request->total_ongkos_kirim;
    $jastip->unique_nominal = $request->unique_nominal;
    $jastip->grand_total = $request->grand_total_rp;
    $jastip->customeraddress_id = $customer->id;
    $jastip->total_dp = $request->total_payment;
    $jastip->total_pelunasan = $request->total_pelunasan;
    $jastip->total_paid = $jastip->total_dp + $jastip->total_pelunasan;
    if($jastip->total_pelunasan == 0) {
      $jastip->payment_date = \Carbon\Carbon::now();
    }
    $jastip->save();

    //save detailsnya
    foreach ($products_list as $product) {
      $detail = new Jastipdetail;
      $detail->jastip_id = $jastip->id;
      $detail->product_name = $product->nama_produk;
      $detail->qty = $product->qty;
      $detail->harga_won = $product->harga_barang_won;
      $detail->harga_rp = $product->harga_barang_rp;
      $detail->weight = $product->perkiraan_berat_satuan;
      $detail->product_link = $product->link_produk;
      $detail->save();
    }

    //siapkan message untuk di-copy
    $banks = Bank::get();
    $bank_msg = "";
    foreach($banks as $bank) {
      $bank_msg = $bank->bank_name . ' - ' . $bank->bank_account . 'a.n ' . $bank->bank_account_name . '<br>';
    }

    $message = "Nomor order : " . $jastip->invoicenumber . "<br>"
    . "Total belanja : Rp. " . number_format($jastip->total_paid, 0, ',', '.') . "<br>"
    . "(tolong bayar sesuai total diatas agar kami mudah dalam pengecekan)<br><br>"
    . "Mohon lakukan pembayaran ke :<br>"
    . $bank_msg
    . "<br>Silahkan konfirmasi pembayaran.<br>"
    . "(mohon cek detail order sebelum transfer)<br>"
    . "Mohon lakukan konfirmasi pembayaran dalam 24 jam, atau orderan kakak akan batal otomatis dan barang yang kakak pesan tidak terjamin ketersediaannya.<br>";

    return back()->with([
      'msg' => $message
    ]);
  }

  public function jastipHarusBeli() {
    $jastips = Jastip::where('has_ordered', '=', 0)
    ->get();

    return view('pages.admin-side.modules.jastips.jastipharusbeli')->with([
      'jastips' => $jastips
    ]);
  }

  public function jastipBuyNow($id) {
    $jastip = Jastip::find($id);
    $jastip->has_ordered = 1;
    $jastip->ordered_date = \Carbon\Carbon::now();
    $jastip->ordered_by = auth()->user()->id;
    $jastip->save();

    return back()->with([
      'msg' => 'Jastip ' . $jastip->invoicenumber . ' sudah dicentang untuk dibeli.'
    ]);
  }

}
