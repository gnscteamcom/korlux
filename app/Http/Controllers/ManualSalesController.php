<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\ShipmentApi;
use App\Http\Controllers\Custom\PriceFunction;
use App\Http\Controllers\Custom\StockBalanceFunction;
use App\Product;
use Cart;
use App\User;
use App\Orderheader;
use App\Ordermarketplace;
use App\Orderdetail;
use App\Stockin;
use App\Customeraddress;
use App\Paymentconfirmation;
use App\Dropship;
use App\Http\Controllers\Custom\StockFunction;
use App\Http\Controllers\Custom\OrderFunction;

class ManualSalesController extends Controller {

    public function viewManualSales(Request $request) {

        Cart::instance('manualsalescart')->destroy();

        $data_rowid = Cart::instance('manualsalesdata')->search(array(
                    'name' => 'data'
                ))[0];

        $cart_data = Cart::instance('manualsalesdata')->get($data_rowid);

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

        //menampilkan halaman untuk memasukan sales manual
        return view('pages.admin-side.modules.' . $request->segment(1) . '.' . $request->segment(1))
                        ->with(array(
                            'products' => $products,
                            'marketing_name' => $marketing_name,
                            'marketing_initial' => $marketing_initial,
                            'status' => $status,
                            'cart_data' => $cart_data,
                    'kecamatans' => $result['data'],
                    'kecamatan_count' => $result['count'],
        ));
    }

    public function viewManualSales2() {

        $data_rowid = Cart::instance('manualsalesdata')->search(array(
                    'name' => 'data'
                ))[0];

        $cart_data = Cart::instance('manualsalesdata')->get($data_rowid);

        return view('pages.admin-side.modules.manualsales.manualsales2')->with(array(
                    'cart_data' => $cart_data
        ));
    }

    public function viewManualSalesHistory() {

        $user = auth()->user();
        if ($user->is_owner == 1) {
            $orderheaders = Orderheader::where('invoicenumber', 'not like', 'Y%')
                            ->where('invoicenumber', 'not like', '#%')
                            ->orderBy('updated_at', 'desc')->paginate(100);
        } else {
            $orderheaders = Orderheader::whereUser_id($user->id)->orderBy('updated_at', 'desc')->paginate(100);
        }

        return view('pages.admin-side.modules.manualsales.manualsaleshistory')->with(array(
                    'orderheaders' => $orderheaders
        ));
    }

    public function processManualSales(Request $request) {

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

        Cart::instance('manualsalesdata')->destroy();
        Cart::instance('manualsalesdata')->add(1, 'data', 1, 1, array(
            'marketplace_invoice' => $request['marketplace_invoice'],
            'note' => $request['note'],
            'ship_method' => $request['ship_method'],
            'ship_method_text' => $request['ship_method_text'],
            'inisial' => $request['inisial'],
            'biaya_kirim' => $request['biaya_kirim'],
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
            'nominal_unik' => $request['nominal_unik'],
            'link_pembayaran' => $create_payment_link
        ));

        return redirect('manualsales2');
    }

    public function submitManualSales(Request $request) {

        //cek ketersediaan stok
        if (Custom\StockFunction::checkStock('manualsalescart', 1)) {
            return redirect('manualsales2')->with(array(
                        'err' => 'Pesanan anda melebihi dari stok yang tersedia, pesanan anda sudah diubah ke stok tersedia.. Mohon dicek kembali..'
            ));
        }

        $data_rowid = Cart::instance('manualsalesdata')->search(array(
                    'name' => 'data'
                ))[0];

        $cart_data = Cart::instance('manualsalesdata')->get($data_rowid);
        $user_id = auth()->user()->id;
        $total_weight = $request['total_weight'];
        $marketplace_invoice = $cart_data->options->marketplace_invoice;
        
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
        $order_header->user_id = $user_id;
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
        
        #set redirect link ke manualsales
        $link = 'manualsales';
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
            $payment_confirmation->user_id = $user_id;
            $payment_confirmation->orderheader_id = $order_header->id;
            $payment_confirmation->account_name = 'Manual Sales';
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
            $price = \App\Http\Controllers\Custom\PriceFunction::getCurrentPrice($product->currentprice_id);


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
                    StockBalanceFunction::addBalance($product->id, 0, $product->qty, 0, "Manual Sales stok utama: " . $order_header->invoicenumber);
                    $product->qty = 0;
                }else{
                    StockBalanceFunction::addBalance($product->id, 0, $order_detail->qty, 0, "Manual Sales stok utama: " . $order_header->invoicenumber);
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
                    StockBalanceFunction::addBalance($product->id, 0, $order_detail->qty - $product->reserved_qty, 0, "Manual Sales stok utama: " . $order_header->invoicenumber);
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
            $customeraddress->address_name = 'Manual Sales';
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
                $dropship->dropship_name = 'Manual Sales';
                $dropship->name = $cart_data->options->dikirim_oleh;
                $dropship->hp = $cart_data->options->nomor_hp_pengirim;
                $dropship->save();
            }


            $order_header->customeraddress_id = $customeraddress->id;
            if (isset($dropship)) {
                $order_header->dropship_id = $dropship->id;
            }
            $order_header->grand_total = $grand_total;
            $order_header->shipment_cost = Custom\OrderFunction::calculateWeight($total_weight) * $cart_data->options->biaya_kirim;
            $total_paid = $order_header->shipment_cost - $order_header->discount_coupon - $order_header->discount_point + $order_header->unique_nominal + $order_header->insurance_fee + $order_header->grand_total + $order_header->packing_fee;
            $order_header->total_paid = $total_paid;
            $order_header->save();

            //hapus semua session data
            Cart::instance('manualsalesdata')->destroy();
            Cart::instance('manualsalescart')->destroy();

            
            #Message untuk manual sales
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
            
            #kalau ada input invoice marketplace
            if(strlen($marketplace_invoice) > 0){
                $order_marketplace = new Ordermarketplace;
                $order_marketplace->orderheader_id = $order_header->id;
                $order_marketplace->marketplace_invoice = $marketplace_invoice;
                $order_marketplace->save();
            }

            return redirect($link)
                            ->with([
                                'msg' => $message
            ]);
        } else {

            //hapus semua session data
            Cart::instance('manualsalesdata')->destroy();
            Cart::instance('manualsalescart')->destroy();

            return redirect($link)->with('err', 'Tidak ada pesanan.');
        }
    }

    public function addManualSales(Request $request) {
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

    public function removeManualSales(Request $request) {

        //loop semua isi nya, lalu remove yang terakhir
        $rowid = '';
        foreach (Cart::instance('manualsalescart')->content() as $cart) {
            $rowid = $cart->rowid;
        }

        Cart::instance('manualsalescart')->remove($rowid);

        return $request->rowid;
    }

    public function searchOrder(Request $request) {

        if (strlen($request['search']) <= 0) {
            return redirect('vieworder');
        }

        $is_owner = auth()->user()->is_owner;
        if ($is_owner) {
            $orders = Orderheader::leftJoin('ordermarketplaces', 'ordermarketplaces.orderheader_id', '=', 'orderheaders.id')
                    ->where('orderheaders.invoicenumber', 'like', '%' . $request['search'] . '%')
                    ->orWhere('ordermarketplaces.marketplace_invoice', 'like', '%' . $request->search . '%')
                    ->orderBy('orderheaders.updated_at', 'desc')
                    ->select('orderheaders.*');
            $counter = $orders->count();
            $orders = $orders->paginate($counter);
        } else {
            $orders = Orderheader::leftJoin('ordermarketplaces', 'ordermarketplaces.orderheader_id', '=', 'orderheaders.id')
                    ->where('orderheaders.invoicenumber', 'like', '%' . $request['search'] . '%')
                    ->where('orderheaders.user_id', '=', auth()->user()->id)
                    ->orWhere('ordermarketplaces.marketplace_invoice', 'like', '%' . $request->search . '%')
                    ->whereIn('orderheaders.status_id', [13,14])
                    ->orderBy('orderheaders.updated_at', 'desc')
                    ->select('orderheaders.*');
            $counter = $orders->count();
            $orders = $orders->paginate($counter);
        }

        return view('pages.admin-side.modules.manualsales.manualsaleshistory')->with(array(
                    'orderheaders' => $orders
        ));
    }
    

}
