<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Bank;
use App\Contact;
use App\Customeraddress;
use App\Jastip;
use App\Jastipdetail;
use DB;
use PDF;

class JastipController extends Controller {

  #SET BARCODE STYLE
  private static $barcode_style = array(
    'position' => '',
    'align' => 'C',
    'stretch' => false,
    'fitwidth' => true,
    'cellfitalign' => '',
    'hpadding' => 'auto',
    'vpadding' => 'auto',
    'fgcolor' => array(0, 0, 0),
    'bgcolor' => false, //array(255,255,255),
    'stretchtext' => 10
  );

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
      $jastip->is_lunas = 1;
      $jastip->lunas_by = $jastip->user_id;
    }
    $jastip->barcode = Custom\OrderFunction::setBarcode($jastip->invoicenumber);
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

  public function daftarKirimIndonesia() {
    $details = Jastipdetail::join('jastips', 'jastips.id', '=', 'jastipdetails.jastip_id')
    ->select(DB::raw('sum(jastipdetails.qty) as jumlah, jastipdetails.product_name'))
    ->where('jastips.has_ordered', '=', 0)
    ->orderBy('jastipdetails.product_name')
    ->groupBy('jastipdetails.product_name')
    ->get();

    return view('pages.admin-side.modules.jastips.daftarkirimindonesia')->with([
      'details' => $details
    ]);
  }

  public function jastipBelumLunas() {
    $jastips = Jastip::where('has_ordered', '=', 1)
    ->where('is_lunas', '=', 0)
    ->get();

    return view('pages.admin-side.modules.jastips.jastipbelumlunas')->with([
      'jastips' => $jastips
    ]);
  }

  public function jastipLunasNow($id) {
    $jastip = Jastip::find($id);
    $jastip->payment_date = \Carbon\Carbon::now();
    $jastip->is_lunas = 1;
    $jastip->lunas_by = auth()->user()->id;
    $jastip->save();

    return back()->with([
      'msg' => 'Jastip ' . $jastip->invoicenumber . ' sudah dicentang sebagai tanda LUNAS.'
    ]);
  }

  public function jastipSiapKirim() {
    $jastips = Jastip::join('users as u1', 'u1.id', '=', 'jastips.ordered_by')
    ->join('users as u2', 'u2.id', '=', 'jastips.lunas_by')
    ->leftJoin('users as u3', 'u3.id', '=', 'jastips.print_by')
    ->where('jastips.has_ordered', '=', 1)
    ->where('jastips.is_lunas', '=', 1)
    ->select('jastips.*', 'u1.name as ordered_by_name', 'u2.name as lunas_by_name', 'u3.name as print_by_name')
    ->get();

    return view('pages.admin-side.modules.jastips.jastipsiapkirim')->with([
      'jastips' => $jastips
    ]);
  }

  public function printDO(Request $request) {
    $this->validate($request, [
      'jastips_list' => 'required'
    ]);

    //Generate Judul Page
    PDF::SetPageOrientation('L', true, 20);
    PDF::SetMargins(0, 0, 0, true);
    PDF::SetTitle('DO');


    $i = 1;
    $invoice = "<br />&nbsp;&nbsp;&nbsp;Order yang sudah diprint <br /><br />";

    $print_arrays = json_decode($request->jastips_list);
    sort($print_arrays);

    foreach(array_chunk($print_arrays, 4) as $four_array){
      PDF::AddPage();

      //Generate isi report
      $tbl =
      '<table cellspacing="20">'
      . '<tr>'
      . '<td>';

      foreach(array_chunk($four_array, 2) as $two_array){
        $tbl2 = '<table style="width:100%;">'
        . '<tr>';

        foreach($two_array as $jastip_id){

          $tbl2 .= '<td style="width:50%;">';

          //Ambil data
          $jastip = Jastip::find($jastip_id);
          $tbl2 .= $this->generateTableInvoice($jastip, $i++);
          $tbl2 .= '</td>';

          #Simpan nomor invoicenumber buat ditampilkan di terakhir.
          $invoice .= '&nbsp;&nbsp;&nbsp;' . $jastip->invoicenumber . '<br />';
        }
        $tbl2 .= '</tr>'
        . '</table>';
        PDF::SetFontSize(13, true);
        PDF::writeHTML($tbl2, true, false, false, false, 'C');
      }
      $tbl .= '</td>'
      . '</tr>'
      . '</table>';
      PDF::SetFontSize(13, true);
      PDF::writeHTML($tbl, true, false, false, false, 'C');
    }


    $invoice = '<div style="margin:20px; text-align:left; color:#757575;">' . $invoice . "</div>";
    PDF::AddPage();
    PDF::SetFontSize(13, true);
    PDF::writeHTML($invoice, true, false, false, false, 'C');

    //Close dan tampilkan hasilnya
    PDF::Output('PrintDO.pdf');
  }

  public function printAllDO(Request $request){

    //Generate Judul Page
    PDF::SetPageOrientation('L', true, 20);
    PDF::SetMargins(0, 0, 0, true);
    PDF::SetTitle('DO');

    #SET BARCODE STYLE
    $barcode_style = array(
      'position' => '',
      'align' => 'C',
      'stretch' => false,
      'fitwidth' => true,
      'cellfitalign' => '',
      'hpadding' => 'auto',
      'vpadding' => 'auto',
      'fgcolor' => array(0, 0, 0),
      'bgcolor' => false, //array(255,255,255),
      'stretchtext' => 4
    );


    $i = 1;
    $counter = 1;
    $invoice = "<br />&nbsp;&nbsp;&nbsp;Order yang sudah diprint <br /><br />";

    $jastips = Jastip::where('is_print', '=', 0)
    ->where('is_lunas', '=', 1)
    ->where('has_ordered', '=', 1)
    ->orderBy('invoicenumber')
    ->get();

    foreach($jastips->chunk(4) as $jastip_chunk){
      PDF::AddPage();

      //Generate isi report
      $tbl =
      '<table cellspacing="20">'
      . '<tr>'
      . '<td>';

      foreach($jastip_chunk->chunk(2) as $jastip_chunk_two){
        $tbl2 = '<table style="width:100%;">'
        . '<tr>';

        foreach($jastip_chunk_two as $jastip){

          $tbl2 .= '<td style="width:50%;">';

          //Ambil data
          $tbl2 .= $this->generateTableInvoice($jastip, $i++);
          $tbl2 .= '</td>';

          #Simpan nomor invoicenumber buat ditampilkan di terakhir.
          $invoice .= '&nbsp;&nbsp;&nbsp;' . $jastip->invoicenumber . '<br />';
        }
        $tbl2 .= '</tr>'
        . '</table>';
        PDF::SetFontSize(13, true);
        PDF::writeHTML($tbl2, true, false, false, false, 'C');
      }
      $tbl .= '</td>'
      . '</tr>'
      . '</table>';
      PDF::SetFontSize(13, true);
      PDF::writeHTML($tbl, true, false, false, false, 'C');
    }


    $invoice = '<div style="margin:20px; text-align:left; color:#757575;">' . $invoice . "</div>";
    PDF::AddPage();
    PDF::SetFontSize(13, true);
    PDF::writeHTML($invoice, true, false, false, false, 'C');

    //Close dan tampilkan hasilnya
    PDF::Output('PrintDO.pdf');
  }

  private static function generateTableInvoice($jastip, $i) {
    $tbl2 = '';
    if (!$jastip->shipment_invoice) {

      // $shipment_method = $jastip->shipment_method;
      $shipment_method = '';

      //ambil dari customeraddress
      $customer_address = Customeraddress::where('id', '=', $jastip->customeraddress_id)
      ->withTrashed()->first();
      $to = $customer_address->first_name . ' ' . $customer_address->last_name;
      $to_phone = $customer_address->hp;

      $to_address = $customer_address->alamat . '<br />'
      . $customer_address->kecamatan . ', '
      . $customer_address->kota . '<br />'
      . $customer_address->kodepos;


      //Tambahkan kontak pengirim
      $contact = Contact::first();
      $from = $contact->owner_name;
      $from_phone = $contact->whatsapp;


      $product_name = '';
      $details = Jastipdetail::whereJastip_id($jastip->id)->get();

      foreach ($details as $detail) {
        $product_name .= $detail->qty . ' x ' . $detail->product_name . '<br />';
      }

      #BIKIN BARCODE
      if (strlen($jastip->barcode) > 0) {
        $barcode_y_position = PDF::getY();
        PDF::write1DBarcode($jastip->barcode, 'C128', 2 + ( 148 * ($i % 2 == 0 ? 1 : 0) ), 3 + $barcode_y_position, '', 14, 0.4, JastipController::$barcode_style, 1);
        PDF::SetAutoPageBreak(TRUE, 0);
      }


      //bikin detail
      $tbl2 = $tbl2
      . '<table align="left" width="100%" style="margin-top:20px; color:#757575;">'
      . '<tr>'
      . '<td width="42%" style="border:.1px dashed #757575; vertical-align:top; padding-top:10px;">'
      . '<table border="0" style=" padding-left:10px;">'
      . '<tr>'
      . '<td>'
      . '<strong>' . $shipment_method . '</strong>'
      . '</td>'
      . '</tr>'
      . '<tr>'
      . '<td>'
      . '</td>'
      . '</tr>'
      . '<tr>'
      . '<td>'
      . '</td>'
      . '</tr>'
      . '<tr>'
      . '<td>'
      . $jastip->invoicenumber
      . '</td>'
      . '</tr>'
      . '<tr>'
      . '<td>'
      . 'FROM: ' . $from . ''
      . '</td>'
      . '</tr>'
      . '<tr>'
      . '<td>'
      . '' . $from_phone . ''
      . '</td>'
      . '</tr>';

      $tbl2 = $tbl2 . '</table>'
      . '</td>'
      . '<td width="56%" style="border:.1px dashed #757575; vertical-align:top; padding:10px;">'
      . '<table style=" padding-left:10px;">'
      . '<tr>'
      . '<td>'
      . '<strong>' . $shipment_method . '</strong>'
      . '</td>'
      . '</tr>'
      . '<tr>'
      . '<td>'
      . '<strong>Rp. ' . number_format($jastip->shipment_cost) . '</strong>'
      . '</td>'
      . '</tr>'
      . '<tr>'
      . '<td>'
      . 'KEPADA:'
      . '</td>'
      . '</tr>'
      . '<tr>'
      . '<td>'
      . '' . $to . ''
      . '</td>'
      . '</tr>'
      . '<tr>'
      . '<td>'
      . 'Telp: ' . $to_phone . ''
      . '</td>'
      . '</tr>'
      . '<tr>'
      . '<td>'
      . '' . $to_address . ''
      . '</td>'
      . '</tr>'
      . '</table>'
      . '</td>'
      . '</tr>';

      $tbl2 .= '<tr>'
      . '<td width="98%;" style="border:.1px dashed #757575; vertical-align:top;">'
      . '<table style="padding-left:10px;">'
      . '<tr>'
      . '<td>'
      . $jastip->invoicenumber
      . '</td>'
      . '</tr>'
      . '<tr>'
      . '<td style="font-size:8px;">'
      . '' . $product_name . ''
      . '</td>'
      . '</tr>'
      . '</table>'
      . '</td>'
      . '</tr>'
      . ' </table>';

      #jadikan dia sudah print
      $jastip->is_print = 1;
      $jastip->print_by = auth()->user()->id;
      $jastip->save();
    }

    return $tbl2;
  }

}
