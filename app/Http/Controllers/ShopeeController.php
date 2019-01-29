<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Custom\StockBalanceFunction;
use App\Http\Controllers\Custom\ExportFunction;
use App\Http\Controllers\Custom\OrderFunction;
use App\Http\Controllers\Custom\StockFunction;
use App\Http\Controllers\Api\ShipmentApi;
use App\Paymentconfirmation;
use App\Shopeesales;
use App\Orderheader;
use App\Orderdetail;
use App\Customeraddress;
use App\Product;
use App\Stockin;
use App\Dropship;
use Excel;
use Cart;

class ShopeeController extends Controller {
    
    public function stuckShopeeSales(){
        #untuk hapus seluruh shopeesales yang nyangkut
        $shopeesales = Shopeesales::join('orderheaders', 'orderheaders.id', '=', 'shopeesales.orderheader_id')
                ->where('orderheaders.status_id', '=', 15)
                ->select('shopeesales.*')
                ->get();
        $total_shopee = $shopeesales->count();
        
        foreach($shopeesales as $shopee){
            $shopee->delete();
        }
        
        echo 'done: ' . $total_shopee . ' data.';
    }

    public function shopeeSales(Request $request) {
        $sales = Shopeesales::join('orderheaders', 'orderheaders.id', '=', 'shopeesales.orderheader_id')
                ->where('orderheaders.status_id', '!=', 13)
                ->orderBy('shopeesales.send_before')
                ->select('shopeesales.*')
                ->get();

        return view('pages.admin-side.modules.shopeesales.shopeesales')->with([
                    'sales' => $sales
        ]);
    }

    public function addShopeeSales($id) {
        #Destroy cart dulu
        Cart::instance('manualsalesdata')->destroy();
        Cart::instance('manualsalescart')->destroy();

        $sale = Shopeesales::find($id);
        if (!$sale) {
            return back()->with([
                        'err' => 'Tidak ada data penjualan Shopee yang ditemukan.'
            ]);
        }

        #ambil list produk
        $products = Product::distinct()
                ->join('prices', 'prices.product_id', '=', 'products.id')
                ->where('products.qty', '<>', 0)
                ->orWhere('products.reserved_qty', '>', 0)
                ->orderBy('products.product_name')
                ->select('products.id', 'products.product_name', 'products.qty')
                ->get();

        #ambil data kecamatan
        $result = ShipmentApi::kecamatans();
        if (!isset($result->data)) {
            return redirect('shopeesales/add/' . $id);
        }
        
        return view('pages.admin-side.modules.shopeesales.addshopeesales')->with([
                    'sale' => $sale,
                    'products' => $products,
                    'kecamatans' => $result['data'],
                    'kecamatan_count' => $result['count'],
        ]);
    }

    public function continueShopeeSales(Request $request) {
        $shopeesales_id = $request->shopeesales_id;
        $orderheader_id = $request->orderheader_id;
        $shipment_method = $request->ship_method;
        $shipment_method_text = $request->opsi;
        $biaya_kirim = $request->biaya_kirim;
        $notes = $request->note;
        $kecamatan_id = $request->kecamatan;
        $kecamatan_text = $request->kecamatan_text;

        #save dulu hasil inputnya
        $order_header = Orderheader::find($orderheader_id);
        $order_header->shipment_cost = $biaya_kirim;
        $order_header->shipment_method = $shipment_method_text;
        $order_header->shipmethod_id = $shipment_method;
        $order_header->note = $notes;
        $order_header->save();

        #save data kecamatan ke pengirim
        $customeraddress = $order_header->customeraddress;
        if ($customeraddress) {
            $customeraddress->kecamatan_id = $kecamatan_id;
            $customeraddress->kecamatan = $kecamatan_text;
            $customeraddress->save();
        }

        return view('pages.admin-side.modules.shopeesales.reviewshopeesales')->with([
                    'order' => $order_header,
                    'shopeesales_id' => $shopeesales_id
        ]);
    }

    public function insertShopeeSales(Request $request) {
        $orderheader_id = $request->orderheader_id;
        $shopeesales_id = $request->shopeesales_id;

        #cek ketersediaan stok
        if (Custom\StockFunction::checkStock('manualsalescart', 1)) {
            return redirect('shopeesales/add/' . $shopeesales_id)->with(array(
                        'err' => 'Pesanan anda melebihi dari stok yang tersedia, pesanan anda sudah diubah ke stok tersedia.. Mohon dicek kembali..'
            ));
        }

        $user_id = auth()->user()->id;

        //insert header dulu
        $order_header = Orderheader::find($orderheader_id);
        $biaya_kirim = $order_header->shipment_cost;
        $order_header->shipment_cost = 0;
        $order_header->status_id = 13;
        $order_header->accept_time = \Carbon\Carbon::now()->toDateTimeString();
        $order_header->accept_by = auth()->user()->id;
        $order_header->save();

        $payment_confirmation = new Paymentconfirmation;
        $payment_confirmation->user_id = $user_id;
        $payment_confirmation->orderheader_id = $order_header->id;
        $payment_confirmation->account_name = 'Shopee Sales';
        $payment_confirmation->payment_date = date('Y-m-d', strtotime(\Carbon\Carbon::now()->toDateString()));
        $payment_confirmation->bank_id = 0;
        $payment_confirmation->note = $order_header->note;
        $payment_confirmation->save();

        $grand_total = 0;
        $total_weight = $request->total_weight;
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
            
            if($gunakan_stok == 1){
                #Kalau gunakan stok utama
                #potong stok utama dulu, kalau tidak cukup, potong stok cadangan
                $stockin_qty = $order_detail->qty;
                if($product->qty - $order_detail->qty < 0){
                    #save ke history pemotongan stok cadangan
                    StockFunction::saveReservedStockHistory($product->reserved_qty, $order_detail->qty - $product->qty, $order_header->id);
                    
                    $product->reserved_qty = $product->reserved_qty - ($order_detail->qty - $product->qty);
                    StockBalanceFunction::addBalance($product->id, 0, $product->qty, 0, "Pembelian shopee invoice: " . $order_header->invoicenumber);
                    $product->qty = 0;
                }else{
                    StockBalanceFunction::addBalance($product->id, 0, $order_detail->qty, 0, "Pembelian shopee invoice: " . $order_header->invoicenumber);
                    $product->qty -= $order_detail->qty;
                }
                $product->save();
            }else{
                #kalau gunakan stok cadangan
                #seperti di atas, namun dibalik saja
                if($product->reserved_qty - $order_detail->qty < 0){
                    #save ke history pemotongan stok cadangan
                    StockFunction::saveReservedStockHistory($product->reserved_qty, $product->reserved_qty, $order_header->id);

                    StockBalanceFunction::addBalance($product->id, 0, $order_detail->qty - $product->reserved_qty, 0, "Pembelian shopee invoice: " . $order_header->invoicenumber);
                    $product->qty = $product->qty - ($order_detail->qty - $product->reserved_qty);
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

            $grand_total += $order_detail->price * $order_detail->qty;
            $order_detail->profit = $profit;
            $order_detail->save();
            $i++;
        }


        if (isset($order_detail)) {

            //update total weight dan grand total nya...
            $order_header = $order_detail->orderheader;
            $order_header->total_weight = $total_weight;
            $order_header->grand_total = $grand_total;
            $order_header->shipment_cost = Custom\OrderFunction::calculateWeight($total_weight) * $biaya_kirim;
            $order_header->total_paid = $order_header->grand_total + $order_header->shipment_cost;
            $order_header->save();

            #hapus semua session data
            Cart::instance('manualsalesdata')->destroy();
            Cart::instance('manualsalescart')->destroy();

            #setelah save, hapus dari shopee sales
            $shopeesales = Shopeesales::find($shopeesales_id);
            if ($shopeesales) {
                $shopeesales->delete();
            }

            #Message untuk shopee sales
            $message = "Nomor order : " . $order_header->invoicenumber . "<br>"
                    . "Total belanja : Rp. " . number_format($order_header->grand_total + $order_header->shipment_cost + $order_header->insurance_fee + $order_header->unique_nominal - $order_header->discount_coupon - $order_header->discount_point, 0, ',', '.') . "<br>";

            return redirect('shopeesales')->with([
                        'msg' => $message
            ]);
        } else {

            #hapus semua session data
            Cart::instance('manualsalesdata')->destroy();
            Cart::instance('manualsalescart')->destroy();

            return redirect('shopeesales')->with([
                        'err' => 'Tidak ada pesanan.'
            ]);
        }
    }

    public function downloadShopeeSales() {
        ExportFunction::exportForImportShopeeSales();
    }

    public function importShopeeSales(Request $request) {

        $this->validate($request, [
            'file' => 'required'
        ]);

        #ambil data
        $file = $request['file'];
        $filesize = $file->getSize();
        if ($filesize <= 0) {
            return back()->with('err', 'File tidak ada data');
        }

        #validasi excel
        if ($file->getClientOriginalExtension() != 'xls' && $file->getClientOriginalExtension() != 'xlsx') {
            return back()->with('err', 'File harus .xls atau .xlss');
        }

        echo '<h1>Hasil Import Data :</h1>';

        Excel::selectSheets('Sheet1')->load($file, function($reader) {

            //Baca smua sheets
            $reader->each(function($row) {
                if (ctype_space($row->nomor_pesanan) || $row->nomor_pesanan == null || $row->nomor_pesanan == '') {
                    echo '<b style="color:red">Nomor Pesanan Harus Diisi</b><br />';
                } else if (ctype_space($row->kode_resi) || $row->kode_resi == null || $row->kode_resi == '') {
                    echo '<b style="color:red">Kode Resi Harus Diisi</b><br />';
                } else if (ctype_space($row->username) || $row->username == null || $row->username == '') {
                    echo '<b style="color:red">Username Harus Diisi</b><br />';
                } else if (ctype_space($row->opsi_pengiriman) || $row->opsi_pengiriman == null || $row->opsi_pengiriman == '') {
                    echo '<b style="color:red">Opsi Pengiriman Harus Diisi</b><br />';
                } else if (ctype_space($row->nama_penerima) || $row->nama_penerima == null || $row->nama_penerima == '') {
                    echo '<b style="color:red">Nama Penerima Harus Diisi</b><br />';
                } else if (ctype_space($row->nomor_telepon) || $row->nomor_telepon == null || $row->nomor_telepon == '') {
                    echo '<b style="color:red">Nomor Telepon Harus Diisi</b><br />';
                } else if (ctype_space($row->alamat_pengiriman) || $row->alamat_pengiriman == null || $row->alamat_pengiriman == '') {
                    echo '<b style="color:red">Alamat Pengiriman Harus Diisi</b><br />';
                } else if (ctype_space($row->kodepos) || $row->kodepos == null || $row->kodepos == '') {
                    echo '<b style="color:red">Kodepos Harus Diisi</b><br />';
                } else if (ctype_space($row->daftar_produk) || $row->daftar_produk == null || $row->daftar_produk == '') {
                    echo '<b style="color:red">Daftar Produk Harus Diisi</b><br />';
                } else if (ctype_space($row->kirim_sebelum) || $row->kirim_sebelum == null || $row->kirim_sebelum == '') {
                    echo '<b style="color:red">Kirim Sebelum Harus Diisi</b><br />';
                } else if (ctype_space($row->dikirim_oleh) || $row->dikirim_oleh == null || $row->dikirim_oleh == '') {
                    echo '<b style="color:red">Dikirim Oleh Harus Diisi</b><br />';
                } else if (ctype_space($row->nomor_hp_pengirim) || $row->nomor_hp_pengirim == null || $row->nomor_hp_pengirim == '') {
                    echo '<b style="color:red">Nomor HP Pengirim Harus Diisi</b><br />';
                } else {
                    $user_id = auth()->user()->id;

                    #cek apakah nomor invoice shopee duplikat
                    $sales = Shopeesales::withTrashed()
                            ->where('shopee_invoice_number', 'like', $row->nomor_pesanan)
                            ->first();

                    if (!$sales) {
                        $sales = new Shopeesales;
                        $sales->shopee_invoice_number = $row->nomor_pesanan;
                        $sales->shopee_resi = $row->kode_resi;
                        $sales->username = $row->username;
                        $sales->shipping_option = $row->opsi_pengiriman;
                        $sales->product_list = $row->daftar_produk;
                        $sales->send_before = $row->kirim_sebelum;

                        #simpan ke customeraddress kalau belum ada
                        $customeraddress = Customeraddress::where('first_name', 'like', $row->nama_penerima)
                                ->where('alamat', 'like', $row->alamat_pengiriman)
                                ->where('kodepos', 'like', $row->kodepos)
                                ->where('hp', 'like', $row->nomor_telepon)
                                ->first();
                        if (!$customeraddress) {
                            $customeraddress = new Customeraddress;
                            $customeraddress->user_id = $user_id;
                            $customeraddress->address_name = 'Shopee - ' . $row->username;
                            $customeraddress->first_name = $row->nama_penerima;
                            $customeraddress->last_name = '';
                            $customeraddress->alamat = $row->alamat_pengiriman;
                            $customeraddress->provinsi = '';
                            $customeraddress->kodepos = $row->kodepos;
                            $customeraddress->hp = $row->nomor_telepon;
                            $customeraddress->save();
                        }

                        $sales->customeraddress_id = $customeraddress->id;
                        
                        $invoice_number = OrderFunction::setInvoiceNumber('S');

                        #simpan ke orderheader
                        $orderheader = new Orderheader;
                        $orderheader->user_id = $user_id;
                        $orderheader->invoicenumber = $invoice_number;
                        $orderheader->total_weight = 0;
                        $orderheader->shipment_cost = 0;
                        $orderheader->discount_coupon = 0;
                        $orderheader->discount_point = 0;
                        $orderheader->unique_nominal = 0;
                        $orderheader->insurance_fee = 0;
                        $orderheader->grand_total = 0;
                        $orderheader->shipment_method = '';
                        $orderheader->shipmethod_id = 0;
                        $orderheader->customeraddress_id = $customeraddress->id;
                        $orderheader->status_id = 11;
                        $orderheader->is_print = 0;
                        $orderheader->barcode = Custom\OrderFunction::setBarcode($invoice_number);

                        #simpan ke dropship
                        $dropship = Dropship::where('dropship_name', 'like', 'Shopee Sales')
                                ->where('name', 'like', $row->dikirim_oleh)
                                ->where('hp', 'like', $row->nomor_hp_pengirim)
                                ->first();
                        if (!$dropship) {
                            $dropship = new Dropship;
                            $dropship->user_id = $user_id;
                            $dropship->dropship_name = 'Shopee Sales';
                            $dropship->name = $row->dikirim_oleh;
                            $dropship->hp = $row->nomor_hp_pengirim;
                            $dropship->save();
                        }
                        $orderheader->dropship_id = $dropship->id;
                        $orderheader->save();

                        $sales->orderheader_id = $orderheader->id;
                        $sales->save();

                        echo '<b style="color:orange">Berhasil menambahkan nomor pesanan shopee : ' . $row->nomor_pesanan . ' di sistem dengan nomor order : ' . $orderheader->invoicenumber . '.</b><br />';
                    } else {
                        echo '<b style="color:red">Nomor pesanan sudah terdaftar : ' . $row->nomor_pesanan . '</b><br />';
                    }
                }
            });
        });

        echo '<br /><a href="' . url()->previous() . '">Kembali</a>';
    }

    public function searchShopeeSales(Request $request) {
        $search_key = $request->search;

        if ($search_key) {
            $sales = Shopeesales::where('shopee_invoice_number', 'like', '%' . $search_key . '%')
                    ->orderBy('created_at', 'desc')
                    ->paginate(50);
        } else {
            return redirect('shopeesales');
        }

        return view('pages.admin-side.modules.shopeesales.shopeesales')->with([
                    'sales' => $sales
        ]);
    }

}
