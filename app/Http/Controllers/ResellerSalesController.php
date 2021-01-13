<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\ShipmentApi;
use App\Http\Controllers\Custom\PriceFunction;
use App\Http\Controllers\Custom\StockBalanceFunction;
use App\Product;
use App\Bank;
use Cart;
use Hash;
use App\User;
use App\Usersetting;
use App\Orderheader;
use App\Ordermarketplace;
use App\Orderdetail;
use App\Stockin;
use App\Customeraddress;
use App\Paymentconfirmation;
use App\Dropship;
use App\Tablestatus;
use App\Http\Controllers\Custom\StockFunction;
use App\Http\Controllers\Custom\OrderFunction;

class ResellerSalesController extends Controller {

    public function viewResellerSales(Request $request) {

        Cart::instance('manualsalescart')->destroy();

        $data_rowid = Cart::instance('resellersalesdata')->search(array(
                    'name' => 'data'
                ))[0];

        $cart_data = Cart::instance('resellersalesdata')->get($data_rowid);

        $products = Product::distinct()
                ->join('prices', 'prices.product_id', '=', 'products.id')
                ->where('products.qty', '>', 0)
                ->orWhere('products.reserved_qty', '>', 0)
                ->orderBy('products.product_name')
                ->select('products.id', 'products.product_name', 'products.qty', 'products.reserved_qty')
                ->get();

        $user = auth()->user();
        $status = $user->usersetting->status_id;
        $marketing_name = $user->name;
        $marketing_initial = substr($marketing_name, 0, 1);

        #ambil data kecamatan
        $result = ShipmentApi::kecamatans();

        #ambil data reseller
        $resellers = User::join('usersettings', 'usersettings.user_id', '=', 'users.id')
                    ->join('tablestatuses', 'tablestatuses.id', '=', 'usersettings.status_id')
                    ->where('usersettings.status_id', '>', 1)
                    ->where('users.is_admin', '=', 0)
                    ->select('users.id', 'users.name', 'users.username', 'usersettings.status_id', 'usersettings.email', 'usersettings.hp', 'tablestatuses.status')
                    ->orderBy('users.name')
                    ->get();

        //menampilkan halaman untuk memasukan sales reseller
        return view('pages.admin-side.modules.' . $request->segment(1) . '.' . $request->segment(1))
                        ->with(array(
                            'products' => $products,
                            'marketing_name' => $marketing_name,
                            'marketing_initial' => $marketing_initial,
                            'status' => $status,
                            'cart_data' => $cart_data,
                    'kecamatans' => $result['data'],
                    'kecamatan_count' => $result['count'],
                    'resellers' => $resellers
        ));
    }

    public function viewResellerSales2() {

        $data_rowid = Cart::instance('resellersalesdata')->search(array(
                    'name' => 'data'
                ))[0];

        $cart_data = Cart::instance('resellersalesdata')->get($data_rowid);

        return view('pages.admin-side.modules.resellersales.resellersales2')->with(array(
                    'cart_data' => $cart_data
        ));
    }

    public function processResellerSales(Request $request) {

        #harus konfirmasi pembayaran
        $create_payment_link = $request->link_pembayaran;
        if (!$create_payment_link) {
            $create_payment_link = 0;
        }

        $this->validate($request, [
            'biaya_kirim' => 'required',
            'qty' => 'required',
            'note' => 'required',
            'nomor_hp_pengirim' => 'required_with:dikirim_oleh'
        ]);


        Cart::instance('resellersalesdata')->destroy();
        Cart::instance('resellersalesdata')->add(1, 'data', 1, 1, array(
            'reseller' => $request['reseller'],
            'username_reseller' => $request['username_reseller'],
            'nama_reseller' => $request['nama_reseller'],
            'status_reseller' => $request['status_reseller'],
            'status_id_reseller' => $request['status_id_reseller'],
            'hp_reseller' => $request['hp_reseller'],
            'email_reseller' => $request['email_reseller'],
            'note' => $request['note'],
            'ship_method' => $request['ship_method'],
            'ship_method_text' => $request['ship_method_text'],
            'inisial' => $request['inisial'],
            'biaya_kirim' => $request['total_biaya_kirim'],
            'dikirim_oleh' => $request['dikirim_oleh'],
            'nomor_hp_pengirim' => $request['nomor_hp_pengirim'],
            'nama_depan' => $request['nama_depan'],
            'alamat' => $request['alamat'],
            'kecamatan' => $request['kecamatan'],
            'kecamatan_text' => $request['kecamatan_text'],
            'kota' => $request['kota'],
            'hp' => $request['hp'],
            'marketing' => $request['marketing'],
            'inisial' => $request['inisial'],
            'nominal_unik' => 0,
            'link_pembayaran' => $create_payment_link
        ));

        return redirect('resellersales2');
    }

    public function submitResellerSales(Request $request) {

        //cek ketersediaan stok
        if (Custom\StockFunction::checkStock('manualsalescart', 1)) {
            return redirect('resellersales2')->with(array(
                        'err' => 'Pesanan anda melebihi dari stok yang tersedia, pesanan anda sudah diubah ke stok tersedia.. Mohon dicek kembali..'
            ));
        }

        $data_rowid = Cart::instance('resellersalesdata')->search(array(
                    'name' => 'data'
                ))[0];

        $cart_data = Cart::instance('resellersalesdata')->get($data_rowid);
        $reseller_email = $cart_data->options->email_reseller;
        $user_id = auth()->user()->id;
        $total_weight = $request['total_weight'];

        $invoice_number = Custom\OrderFunction::setInvoiceNumber($cart_data->options->inisial);
        $nominal_unik = $cart_data->options->nominal_unik;
        if(!$nominal_unik){
            $nominal_unik = 0;
        }
        $shipment_method = $cart_data->options->ship_method_text;
        if(!$shipment_method){
            $shipment_method = '';
        }
        $ship_method_id = $cart_data->options->ship_method;
        if(!$ship_method_id){
            $ship_method_id = 0;
        }

        //insert header dulu
        $order_header = new Orderheader;
        $order_header->user_id = $request->id_reseller;
        $order_header->invoicenumber = $invoice_number;
        $order_header->total_weight = $total_weight;
        $order_header->shipment_cost = 0;
        $order_header->discount_coupon = 0;
        $order_header->discount_point = 0;
        $order_header->grand_total = 0;
        $order_header->unique_nominal = $nominal_unik;
        $order_header->shipment_method = $shipment_method;
        $order_header->shipmethod_id = $ship_method_id;
        $order_header->customeraddress_id = 0;
        $order_header->dropship_id = 0;
        $order_header->note = $cart_data->options->note;
        $order_header->barcode = Custom\OrderFunction::setBarcode($invoice_number);

        #set redirect link ke resellersales
        $link = 'resellersales';
        #cek apakah harus konfirmasi pembayaran
        if ($cart_data->options->link_pembayaran) {
            #kalau ada link_pembayaran redirect ke chatsales
            $link = 'chatsales';

            #buat link pembayaran random
            $random_string = $order_header->invoicenumber . str_random(5);
            $order_header->payment_link = $random_string;
            $order_header->status_id = 11;
        } else {
            $order_header->status_id = 12;
        }
        $order_header->save();

        #kalau tidak perlu konfirmasi pembayaran
        if (!$cart_data->options->link_pembayaran) {
            $payment_confirmation = new Paymentconfirmation;
            $payment_confirmation->user_id = $request->id_reseller;
            $payment_confirmation->orderheader_id = $order_header->id;
            $payment_confirmation->account_name = 'Reseller Sales';
            $payment_confirmation->payment_date = date('Y-m-d', strtotime(\Carbon\Carbon::now()->toDateString()));
            $payment_confirmation->bank_id = 0;
            $payment_confirmation->note = $cart_data->options->note;
            $payment_confirmation->save();
        }

        $grand_total = Cart::instance('manualsalescart')->total();
        $i = 0;


        #insert detail
        foreach (Cart::instance('manualsalescart')->content() as $cart) {

            $order_detail = new Orderdetail;
            $order_detail->orderheader_id = $order_header->id;
            $order_detail->product_id = $cart->id;
            $order_detail->qty = $cart->qty;
            $order_detail->price = $cart->price;
            $order_detail->weight = $cart->options->weight;

            $product = Product::find($cart->id);
            if ($product->is_set) {
                StockFunction::decreaseSetStock($product->id, $order_detail->qty, $order_header);
            }
            $price = \App\Http\Controllers\Custom\PriceFunction::getCurrentPrice($product->currentprice_id, $cart_data->options->status_id_reseller);


            #Cek gunakan stok mana
            $gunakan_stok = $cart->options->gunakan_stok;
            if(!$gunakan_stok){
                $gunakan_stok = 1;
            }

            if($gunakan_stok == 1){
                #Kalau gunakan stok utama
                #potong stok utama dulu, kalau tidak cukup, potong stok cadangan
                $stockin_qty = $order_detail->qty;
                if($product->qty - $order_detail->qty < 0){
                    #save ke history pemotongan stok cadangan
                    StockFunction::saveReservedStockHistory($product->reserved_qty, $order_detail->qty - $product->qty, $order_header->id);

                    $product->reserved_qty = $product->reserved_qty - ($order_detail->qty - $product->qty);
                    StockBalanceFunction::addBalance($product->id, 0, $product->qty, 0, "Reseller Sales stok utama: " . $order_header->invoicenumber);
                    $product->qty = 0;
                }else{
                    StockBalanceFunction::addBalance($product->id, 0, $order_detail->qty, 0, "Reseller Sales stok utama: " . $order_header->invoicenumber);
                    $product->qty -= $order_detail->qty;
                }
                $product->save();
            }else{
                #kalau gunakan stok cadangan
                #seperti di atas, namun dibalik saja
                if($product->reserved_qty - $order_detail->qty < 0){
                    #save ke history pemotongan stok cadangan
                    StockFunction::saveReservedStockHistory($product->reserved_qty, $product->reserved_qty, $order_header->id);

                    $product->qty = $product->qty - ($order_detail->qty - $product->reserved_qty);
                    StockBalanceFunction::addBalance($product->id, 0, $order_detail->qty - $product->reserved_qty, 0, "Reseller Sales stok utama: " . $order_header->invoicenumber);
                    $product->reserved_qty = 0;
                }else{
                    #save ke history pemotongan stok cadangan
                    StockFunction::saveReservedStockHistory($product->reserved_qty, $order_detail->qty, $order_header->id);

                    $product->reserved_qty -= $order_detail->qty;
                }
                $product->save();
            }

            $stockins = Stockin::whereProduct_id($cart->id)->where('qty', '<>', 0)->orderBy('created_at')->get();
            $profit = 0;
            foreach ($stockins as $stockin) {
                if ($stockin_qty > 0 && $stockin->remaining_qty > 0) {

                    if ($stockin->remaining_qty - $stockin_qty <= 0) {
                        $stockin_qty -= $stockin->remaining_qty;
                        $stockin->remaining_qty = 0;
                        $stockin->save();
                    } else {
                        $stockin->remaining_qty -= $stockin_qty;
                        $stockin_qty = 0;
                        $stockin->save();
                    }
                }
            }


            $order_detail->profit = $profit;
            $order_detail->save();
            $i++;
        }


        if (isset($order_detail)) {

            //update total weight dan grand total nya...
            $order_header = $order_detail->orderheader;
            $kecamatan_id = $cart_data->options->kecamatan;
            if(!$kecamatan_id){
                $kecamatan_id = 0;
            }
            $kecamatan = $cart_data->options->kecamatan_text;;
            if(!$kecamatan){
                $kecamatan = '';
            }

            //tambah alamat pengirimannya
            $customeraddress = new Customeraddress;
            $customeraddress->user_id = $order_header->user_id;
            $customeraddress->address_name = 'Reseller Sales';
            $customeraddress->first_name = $cart_data->options->nama_depan;
            $customeraddress->last_name = '';
            $customeraddress->alamat = $cart_data->options->alamat;
            $customeraddress->kecamatan_id = $kecamatan_id;
            $customeraddress->kecamatan = $kecamatan;
            $customeraddress->provinsi = '';
            $customeraddress->kodepos = '';
            $customeraddress->hp = $cart_data->options->hp;
            $customeraddress->save();


            if (strlen($cart_data->options->dikirim_oleh) > 0) {
                $dropship = new Dropship;
                $dropship->user_id = $order_header->user_id;
                $dropship->dropship_name = 'Reseller Sales';
                $dropship->name = $cart_data->options->dikirim_oleh;
                $dropship->hp = $cart_data->options->nomor_hp_pengirim;
                $dropship->save();
            }


            $order_header->customeraddress_id = $customeraddress->id;
            if (isset($dropship)) {
                $order_header->dropship_id = $dropship->id;
            }
            $order_header->grand_total = $grand_total;
            $order_header->shipment_cost = $cart_data->options->biaya_kirim;
            $total_paid = $order_header->shipment_cost - $order_header->discount_coupon - $order_header->discount_point + $order_header->unique_nominal + $order_header->insurance_fee + $order_header->grand_total + $order_header->packing_fee;
            $order_header->total_paid = $total_paid;
            $order_header->save();

            //hapus semua session data
            Cart::instance('resellersalesdata')->destroy();
            Cart::instance('manualsalescart')->destroy();


            #Message untuk reseller sales
            $message = "Nomor order : " . $order_header->invoicenumber . "<br>"
                    . "Total belanja : Rp. " . number_format($total_paid, 0, ',', '.') . "<br>";

            $banks = Bank::get();

            #Message hanya untuk chat sales
            if($cart_data->options->link_pembayaran){
                $message .= "(tolong bayar sesuai total diatas agar kami mudah dalam pengecekan)<br><br>"
                        . "Mohon lakukan pembayaran ke :<br>";

                foreach($banks as $bank){
                    $message .= $bank->bank_name . ' - ' . $bank->bank_account . 'a.n ' . $bank->bank_account_name . '<br>';
                }

                #kalau buat link pembayaran
                if ($order_header->payment_link) {
                    $message .= "<br>Silahkan konfirmasi pembayaran melalui link di bawah ini:<br>"
                            . url('paymentlink/' . $order_header->payment_link) . "<br>"
                            . "(mohon cek detail order sebelum transfer)<br>"
                            . "Mohon lakukan konfirmasi pembayaran dalam 24 jam, atau orderan kakak akan batal otomatis dan barang yang kakak pesan tidak terjamin ketersediaannya.<br>";
                }
            }


            // Kirim email pesanan ini ke reseller
            OrderFunction::newOrderEmail($reseller_email, $order_header->id);


            return redirect($link)
                            ->with([
                                'msg' => $message
            ]);
        } else {

            //hapus semua session data
            Cart::instance('resellersalesdata')->destroy();
            Cart::instance('manualsalescart')->destroy();

            return redirect($link)->with('err', 'Tidak ada pesanan.');
        }
    }

    public function addResellerSales(Request $request) {
        $user_id = $request->user_id;
        $status_id = 1;
        if($user_id){
            $user = User::find($user_id);
            $status_id = $user->usersetting->status_id;
        }

        $product = Product::find($request['product']);
        $price = PriceFunction::getPriceByStatus($product->currentprice_id, $status_id);
        if ($product->currentprice->sale_price > 0 && $product->currentprice->sale_price < $price) {
            $price = $product->currentprice->sale_price;
        }

        if ($product->qty + $product->reserved_qty < $request['qty']) {
            $return_value = array(
                'err' => 'Tidak ada stok'
            );

            return $return_value;
        }

        #simpan discountqty_id untuk keperluan harga grosir
        $discountqty_id = '';
        if ($product->productclasses->count() > 0) {
            $discountqty_id = $product->productclasses->first()->discountqty_id;
        }

        $gunakan_stok = $request->gunakan_stok;
        if(!$gunakan_stok){
            $gunakan_stok = 1;
        }

        Cart::instance('manualsalescart')->add($product->id, $product->product_name, $request['qty'], $price, array(
            'weight' => $product->weight,
            'discountqty_id' => $discountqty_id,
            'gunakan_stok' => $gunakan_stok
        ));

        #update harga grosir
        $price = OrderFunction::updateWholesalePrice($discountqty_id, $product->id, 'manualsalescart', 1, $price);

        #ambil seluruh data cart
        $carts = Cart::instance('manualsalescart')->content();
        $cart_data[] = null;
        $i = 0;

        foreach ($carts as $cart) {
            $cart_data[$i] = [
                'id' => $cart->id,
                'rowid' => $cart->rowid,
                'product_name' => $cart->name,
                'price' => 'Rp. ' . number_format($cart->price, 0, ',', '.'),
                'qty' => number_format($cart->qty, 0, ',', '.')
            ];
            $i++;
        }

        $response = [
            'data' => $cart_data
        ];

        return json_encode($response);
    }

    public function removeResellerSales(Request $request) {

        //loop semua isi nya, lalu remove yang terakhir
        $rowid = '';
        foreach (Cart::instance('manualsalescart')->content() as $cart) {
            $rowid = $cart->rowid;
        }

        Cart::instance('manualsalescart')->remove($rowid);

        return $request->rowid;
    }

    public function addReseller(Request $request) {
      $statuses = Tablestatus::where('id', '<', 5)->get();

      return view('pages.admin-side.modules.resellersales.addreseller')->with([
        'statuses' => $statuses
      ]);
    }

    public function insertReseller(Request $request) {

      $this->validate($request,[
        'nama_reseller' => 'required',
        'username' => 'required',
        'email' => 'required|email',
        'hp' => 'required',
        'tipe_reseller' => 'required'
      ]);

      //Simpan user baru
      $user = new User;
      $user->username = $request->username;
      $user->password = Hash::make($request->password);
      $user->name = $request->nama_reseller;
      $user->is_admin = 0;
      $user->is_owner = 0;
      $user->is_marketing = 0;
      $user->is_warehouse = 0;
      $user->is_finance = 0;
      $user->is_processed = 0;
      $user->save();

      $usersetting = new Usersetting;
      $usersetting->user_id = $user->id;
      $usersetting->first_name = $user->name;
      $usersetting->last_name = "";
      $usersetting->email = $request->email;
      $usersetting->jenis_kelamin = "";
      $usersetting->alamat = "";
      $usersetting->kodepos = "";
      $usersetting->hp = $request->hp;
      $usersetting->status_id = $request->tipe_reseller;
      $usersetting->save();


      return back()->with('msg', 'Berhasil menambah reseller baru. Klik tombol kembali untuk membuat penjualan reseller.');
    }

}
