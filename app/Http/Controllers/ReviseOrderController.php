<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Custom\StockFunction;
use App\Http\Controllers\Custom\OrderFunction;
use App\Http\Controllers\Custom\StockBalanceFunction;
use App\Http\Controllers\Api\ShipmentApi;
use App\Dropship;
use App\Orderheader;
use App\Orderdetail;
use App\Orderheaderhistory;
use App\Orderdetailhistory;
use App\Customeraddress;
use App\Tablestatus;
use App\Usersetting;
use App\Product;
use App\Stockin;
use DB;
use Cart;

class ReviseOrderController extends Controller {

    public function viewReviseOrder() {
        $user = auth()->user();
        if ($user->is_owner) {
            #owner bisa update order yang statusnya sampai belum dikirim
            $orders = Orderheader::select('id', 'invoicenumber', 'status_id', 'user_id', 'grand_total', 'shipment_cost', 'total_paid', 'invoicenumber', 'note', 'created_at')
                    ->where('status_id', '<', '14')
                    ->orderBy('invoicenumber', 'desc')
                    ->paginate(20);
        } else {
            #sisanya hanya bisa update order sampai statusnya pembayaran belum diverifikasi
            $orders = Orderheader::select('id', 'invoicenumber', 'status_id', 'user_id', 'grand_total', 'shipment_cost', 'total_paid', 'invoicenumber', 'note', 'created_at')
                    ->where('status_id', '<', '13')
                    ->orderBy('invoicenumber', 'desc')
                    ->paginate(20);
        }

        return view('pages.admin-side.modules.reviseorderstatus.viewreviseorder')->with(array(
                    'orders' => $orders,
        ));
    }

    public function viewReviseOrderDetail($id) {
        Cart::instance('manualsalescart')->destroy();
        $order_header = Orderheader::find($id);

        if ($order_header->customeraddress_id == 0) {
            $customer_address = Usersetting::whereUser_id($order_header->user_id)->first();
        } else {
            $customer_address = Customeraddress::find($order_header->customeraddress_id);
        }
        
        #ambil daftar ID produk yang sudah ada di detail
        $products_id = [];
        foreach($order_header->orderdetails as $detail){
            array_push($products_id, $detail->product_id);
        }

        #tampilin hanya produk yang tidak ada di detail
        $products = Product::join('prices', 'prices.id', '=', 'products.currentprice_id')
                ->where('products.qty', '<>', 0)
                ->whereNull('prices.deleted_at')
                ->whereNotIn('products.id', $products_id)
                ->orderBy('products.product_name')
                ->select('products.id', 'products.product_name', 'products.qty')
                ->get();

        #ambil data kecamatan
        $result = ShipmentApi::kecamatans();
        
        #Hitung ongkir per kg
        $total_kg = OrderFunction::calculateWeight($order_header->total_weight);
        $ongkir_per_kg = $order_header->shipment_cost / $total_kg;

        return view('pages.admin-side.modules.reviseorderstatus.viewreviseorderdetail')->with(array(
                    'order' => $order_header,
                    'customer_address' => $customer_address,
                    'products' => $products,
                    'kecamatans' => $result['data'],
                    'kecamatan_count' => $result['count'],
                    'ongkir_per_kg' => $ongkir_per_kg
        ));
    }

    public function reviseOrderDetail(Request $request) {
        $this->validate($request, [
            'alamat_penerima' => 'required_with:nama_penerima',
            'kecamatan' => 'required_with:nama_penerima',
            'kodepos_penerima' => 'required_with:nama_penerima',
            'hp_penerima' => 'required_with:nama_penerima',
            'dropship_hp' => 'required_with:dropship_name',
        ]);

        $dropship_name = $request->dropship_name;
        $dropship_hp = $request->dropship_hp;
        $biaya_kirim = $request->biaya_kirim;
        $ship_method_text = $request->ship_method_text;
        $shipmethod_id = $request->ship_method;
        $nama_penerima = $request->nama_penerima;
        $alamat_penerima = $request->alamat_penerima;
        $kecamatan = $request->kecamatan;
        $kecamatan_text = $request->kecamatan_text;
        $kodepos_penerima = $request->kodepos_penerima;
        $hp_penerima = $request->hp_penerima;
        $notes = $request->notes;
        
        $list_of_orderdetail_id = $request['orderdetail_id'];
        $list_of_revise_qty = $request['revise_qty'];
        $list_of_curr_qty = $request['curr_qty'];
        $orderheader = Orderheader::find($request['orderheader_id']);

        //checkstock dulu
        if (StockFunction::checkStockForRevise($orderheader, $list_of_revise_qty)) {
            return back()->with(array(
                        'err' => 'Pesanan Anda melebihi stok yang tersedia, silahkan ulangi.'
            ));
        }

        #simpan ke history dulu
        $orderheader_history = new Orderheaderhistory;
        $orderheader_history->orderheader_id = $orderheader->id;
        $orderheader_history->user_id = $orderheader->user_id;
        $orderheader_history->invoicenumber = $orderheader->invoicenumber;
        $orderheader_history->shipment_invoice = $orderheader->invoicenumber;
        $orderheader_history->total_weight = $orderheader->total_weight;
        $orderheader_history->shipment_cost = $orderheader->shipment_cost;
        $orderheader_history->discount_coupon = $orderheader->discount_coupon;
        $orderheader_history->discount_point = $orderheader->discount_point;
        $orderheader_history->unique_nominal = $orderheader->unique_nominal;
        $orderheader_history->insurance_fee = $orderheader->insurance_fee;
        $orderheader_history->packing_fee = $orderheader->packing_fee;
        $orderheader_history->grand_total = $orderheader->grand_total;
        $orderheader_history->total_paid = $orderheader->total_paid;
        $orderheader_history->shipment_method = $orderheader->shipment_method;
        $orderheader_history->shipmethod_id = $orderheader->shipmethod_id;
        $orderheader_history->customeraddress_id = $orderheader->customeraddress_id;
        $orderheader_history->dropship_id = $orderheader->dropship_id;
        $orderheader_history->status_id = $orderheader->status_id;
        $orderheader_history->note = $orderheader->note;
        $orderheader_history->shipment_date = $orderheader->shipment_date;
        $orderheader_history->payment_date = $orderheader->payment_date;
        $orderheader_history->is_print = $orderheader->is_print;
        $orderheader_history->is_process = $orderheader->is_process;
        $orderheader_history->discountcoupon_id = $orderheader->discountcoupon_id;
        $orderheader_history->freesample_qty = $orderheader->freesample_qty;
        $orderheader_history->payment_link = $orderheader->payment_link;
        $orderheader_history->edited_by = auth()->user()->id;
        $orderheader_history->edited_name = auth()->user()->usersetting->first_name;
        $orderheader_history->barcode = $orderheader->barcode;
        $orderheader_history->save();
        foreach ($orderheader->orderdetails as $detail) {
            $orderdetail_history = new Orderdetailhistory;
            $orderdetail_history->orderheaderhistory_id = $orderheader_history->id;
            $orderdetail_history->product_id = $detail->product_id;
            $orderdetail_history->qty = $detail->qty;
            $orderdetail_history->price = $detail->price;
            $orderdetail_history->weight = $detail->weight;
            $orderdetail_history->profit = $detail->profit;
            $orderdetail_history->save();
        }


        $i = 0;
        if ($list_of_orderdetail_id) {
            foreach ($list_of_orderdetail_id as $orderdetail_id) {

                //kalau berubah, kita update orderdetail nya, dan update lagi grand_total nya
                $orderdetail = Orderdetail::find($orderdetail_id);

                //ubah juga total qty product nya
                $product = $orderdetail->product;
                $product->qty += ($orderdetail->qty - $list_of_revise_qty[$i]);
                
                #balikin revisi dulu
                StockBalanceFunction::addBalance($product->id, $orderdetail->qty, 0, 0, "Balik revisi detail order: " . $orderheader->invoicenumber);
                
                #kurangi stok yang revisi
                StockBalanceFunction::addBalance($product->id, 0, $list_of_revise_qty[$i], 0, "Revisi detail order: " . $orderheader->invoicenumber);

                
                $product->save();

                //update profit
                $orderdetail->qty = $list_of_revise_qty[$i];
                $orderdetail->save();

                #kalau 0, hapus detailnya
                if ($list_of_revise_qty[$i] == 0) {
                    $orderdetail->delete();
                }
                $i++;
            }
        }

        //insert tambahan detail
        foreach (Cart::instance('manualsalescart')->content() as $cart) {

            $order_detail = new Orderdetail;
            $order_detail->orderheader_id = $orderheader->id;
            $order_detail->product_id = $cart->id;
            $order_detail->qty = $cart->qty;
            $order_detail->price = $cart->price;
            $order_detail->weight = $cart->options->weight;

            $product = Product::find($cart->id);
                
            //motong stok
            $stockin_qty = $order_detail->qty;
            $product->qty -= $order_detail->qty;
            
            #histori tambahan revisi
            StockBalanceFunction::addBalance($product->id, 0, $order_detail->qty, 0, "Revisi detail order: " . $orderheader->invoicenumber);
            $product->save();

            $stockins = Stockin::whereProduct_id($cart->id)->where('remaining_qty', '>', 0)->orderBy('created_at')->get();
            $profit = 0;
            foreach ($stockins as $stockin) {
                if ($stockin_qty > 0 && $stockin->remaining_qty > 0) {

                    if ($stockin->remaining_qty - $stockin_qty <= 0) {
                        $stockin_qty -= $stockin->remaining_qty;
                        $profit += $stockin->remaining_qty * ($cart->price - $stockin->capital);
                        $stockin->remaining_qty = 0;
                        $stockin->save();
                    } else {
                        $stockin->remaining_qty -= $stockin_qty;
                        $profit += $stockin_qty * ($cart->price - $stockin->capital);
                        $stockin_qty = 0;
                        $stockin->save();
                    }
                }
            }

            $order_detail->profit = $profit;
            $order_detail->save();
            $i++;
        }

        //update grand_total nya orderheader
        $grand_total = Orderdetail::where('orderheader_id', '=', $request['orderheader_id'])
                ->select(DB::raw('sum(qty * price) as total_sales, sum(qty * weight) as total_weight'))
                ->first();

        #kalau pengiriman berubah
        if ($biaya_kirim > 0) {
            #hitung total KG dari total weight
            $total_kg = OrderFunction::calculateWeight($grand_total->total_weight);
            $orderheader->shipment_cost = $biaya_kirim * $total_kg;
        }

        #kalau metode pengiriman berubah
        if (strlen($ship_method_text) > 0) {
            $orderheader->shipment_method = $ship_method_text;
            $orderheader->shipmethod_id = $shipmethod_id;
        }

        #kalau alamat penerima berubah
        if (strlen($nama_penerima) > 0) {
            $customer_address = new Customeraddress;
            $customer_address->user_id = $orderheader->user_id;
            $customer_address->address_name = $nama_penerima;
            $customer_address->first_name = $nama_penerima;
            $customer_address->last_name = '';
            $customer_address->alamat = $alamat_penerima;
            $customer_address->kecamatan_id = $kecamatan;
            $customer_address->kecamatan = $kecamatan_text;
            $customer_address->provinsi = '';
            $customer_address->kodepos = $kodepos_penerima;
            $customer_address->hp = $hp_penerima;
            $customer_address->save();
            
            $orderheader->customeraddress_id = $customer_address->id;
        }

        #kalau dropship diisi
        if (strlen($dropship_name) > 0) {
            #buat dropship baru
            $new_dropship = new Dropship;
            $new_dropship->user_id = $orderheader->user_id;
            $new_dropship->dropship_name = $dropship_name;
            $new_dropship->name = $dropship_name;
            $new_dropship->hp = $dropship_hp;
            $new_dropship->save();

            $orderheader->dropship_id = $new_dropship->id;
        }
        
        #kalau ada ganti notes
        if(strlen($notes) > 0){
            $orderheader->note = $notes;
        }

        $orderheader->grand_total = $grand_total->total_sales;
        $orderheader->total_weight = $grand_total->total_weight;
        $total = $grand_total->total_sales - $orderheader->discount_coupon - $orderheader->discount_point;
        if ($total < 0) {
            $total = 0;
        }
        $total = $total + $orderheader->shipment_cost + $orderheader->insurance_fee + $orderheader->unique_nominal + $orderheader->packing_fee;
        $orderheader->total_paid = $total;
        $orderheader->save();

        //hapus semua session data
        Cart::instance('manualsalesdata')->destroy();
        Cart::instance('manualsalescart')->destroy();

        return redirect('viewreviseorder')->with(array(
                    'msg' => 'Pesanan ' . $orderheader->invoicenumber . ' berhasil direvisi. Rp. ' . number_format($orderheader->total_paid, 0, ',', '.')
        ));
    }

    public function searchInvoiceNumber(Request $request) {

        $orders = Orderheader::select('id', 'invoicenumber', 'status_id', 'user_id', 'grand_total', 'shipment_cost', 'discount', 'invoicenumber', 'note')
                ->where('invoicenumber', 'like', '%' . $request['search'] . '%')
                ->orderBy('invoicenumber', 'asc')
                ->paginate(20);

        return view('pages.admin-side.modules.reviseorderstatus.vieworderlist')->with(array(
                    'orders' => $orders,
                    'statuses' => $this->retrieveOrderStatusList()
        ));
    }

    public function searchInvoiceNumberNotShip(Request $request) {

        $orders = Orderheader::select('id', 'invoicenumber', 'status_id', 'user_id', 'grand_total', 'shipment_cost', 'shipment_cost', 'total_paid', 'note', 'created_at')
                ->where('invoicenumber', 'like', '%' . $request['search'] . '%')
                ->where('status_id', '<', '14')
                ->orderBy('invoicenumber', 'asc')
                ->paginate(20);

        return view('pages.admin-side.modules.reviseorderstatus.viewreviseorder')->with(array(
                    'orders' => $orders
        ));
    }

    private function retrieveOrderStatusList() {

        $tablestatuses = Tablestatus::select('id', 'status')
                ->whereBetween('id', [11, 16])
                ->get();

        return $tablestatuses;
    }

    public function searchShopee(Request $request) {
        $user = auth()->user();
        $search = $request->search;
        if ($user->is_owner) {
            #owner bisa update order yang statusnya sampai belum dikirim
            $orders = Orderheader::join('shopeesales', 'orderheaders.id', '=', 'shopeesales.orderheader_id')
                    ->where('shopeesales.shopee_invoice_number', 'like', '%' . $search . '%')
                    ->where('orderheaders.status_id', '<', '14')
                    ->select('orderheaders.id', 'orderheaders.invoicenumber', 'orderheaders.status_id', 'orderheaders.user_id', 'orderheaders.grand_total', 'orderheaders.shipment_cost', 'orderheaders.total_paid', 'orderheaders.note', 'orderheaders.created_at')
                    ->orderBy('orderheaders.invoicenumber', 'orderheaders.desc')
                    ->paginate(20);
        } else {
            #sisanya hanya bisa update order sampai statusnya pembayaran belum diverifikasi
            $orders = Orderheader::join('shopeesales', 'orderheaders.id', '=', 'shopeesales.orderheader_id')
                    ->where('shopeesales.shopee_invoice_number', 'like', '%' . $search . '%')
                    ->where('orderheaders.status_id', '<', '13')
                    ->select('orderheaders.id', 'orderheaders.invoicenumber', 'orderheaders.status_id', 'orderheaders.user_id', 'orderheaders.grand_total', 'orderheaders.shipment_cost', 'orderheaders.total_paid', 'orderheaders.note', 'orderheaders.created_at')
                    ->orderBy('orderheaders.invoicenumber', 'orderheaders.desc')
                    ->paginate(20);
        }

        return view('pages.admin-side.modules.reviseorderstatus.viewreviseorder')->with(array(
                    'orders' => $orders,
                    'search' => $search
        ));
    }

}
