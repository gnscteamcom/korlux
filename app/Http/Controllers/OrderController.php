<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Custom\StockBalanceFunction;
use App\Orderheader;
use App\Orderdetail;
use App\Product;
use App\Stockin;
use App\Paymentconfirmation;
use App\Customeraddress;
use App\Customerpoint;
use App\Dropship;
use App\Usersetting;
use App\Pointhistory;
use App\Discountcoupon;
use App\Discountcouponhistory;
use App\Bank;
use App\Http\Controllers\Custom\StockFunction;
use App\Shopeesales;
use Cart;
use Mail;

class OrderController extends Controller {

    public function placeOrder(Request $request) {

        $customeraddress_id = 0;
        $dropship_id = 0;
        $note = $request['note'];
        $shipcost = Cart::instance('shipcost')->total();
        $insurance_fee = Cart::instance('insurancecost')->total();
        $packing_fee = Cart::instance('packingfee')->total();
        $shipmethod = $request['ship_method'];
        $shipmethod_text = $request['ship_method_text'];
        $total_paid = Cart::instance('total')->total();
        $freesample_count = $request['free_sample'];
        $resi_otomatis = $request->resi_otomatis;

        //cek ketersediaan stok
        if (Custom\StockFunction::checkStock('main')) {
            return back()->with(array(
                        'err' => 'Pesanan anda melebihi dari stok yang tersedia, pesanan anda sudah diubah ke stok tersedia.. Mohon dicek kembali..'
            ));
        }

        if ($shipcost == 0 && strlen($resi_otomatis) <= 0) {
            return back()->with(array(
                        'err' => 'Metode pengiriman ke alamat Anda tidak tersedia, silahkan pilih yang lain..'
            ));
        }

        if (Cart::instance('main')->total() <= 0) {
            return redirect('products')->with(array(
                        'err' => 'Anda tidak memiliki barang di keranjang belanja..'
            ));
        }

        #cek apakah dia kirim ke alamat lain
        $kirim_alamat_lain = $request->kirim_alamat_lain;
        if($kirim_alamat_lain){
            #kalau kirim ke alamat lain, create customeraddress baru
            $customer_address = new Customeraddress;
            $customer_address->user_id = auth()->user()->id;
            $customer_address->address_name = $request['nama_penerima'];
            $customer_address->first_name = $request['nama_penerima'];
            $customer_address->last_name = '';
            $customer_address->alamat = $request['alamat_penerima'];
            $customer_address->kecamatan_id = $request['kecamatan'];
            $customer_address->kecamatan = $request['kecamatan_text'];
            $customer_address->kodepos = '';
            $customer_address->hp = $request['nomor_telepon_penerima'];
            $customer_address->save();

            $customeraddress_id = $customer_address->id;
        }

        #cek apakah dia dropship
        $kirim_dropship = $request->kirim_dropship;
        if($kirim_dropship){
            #kalau kirim dropship, bikin dropship baru
            $dropship = new Dropship;
            $dropship->user_id = auth()->user()->id;
            $dropship->dropship_name = $request['nama_pengirim'];
            $dropship->name = $request['nama_pengirim'];
            $dropship->hp = $request['nomor_hp_pengirim'];
            $dropship->save();

            $dropship_id = $dropship->id;
        }

        $total_weight = 0;
        $invoicenumber = Custom\OrderFunction::setInvoiceNumber();

        //tambah order baru
        $order_header = new Orderheader;
        $order_header->user_id = auth()->user()->id;
        $order_header->invoicenumber = $invoicenumber;
        $order_header->total_weight = 0;
        $order_header->shipment_cost = $shipcost;
        $order_header->discount_coupon = Cart::instance('discountcoupon')->total();
        $order_header->discount_point = Cart::instance('discountpoint')->total();
        $order_header->unique_nominal = Cart::instance('unique')->total();
        $order_header->insurance_fee = $insurance_fee;
        $order_header->grand_total = Cart::instance('main')->total();
        $order_header->shipment_method = $shipmethod_text;
        $order_header->shipmethod_id = $shipmethod;
        $order_header->customeraddress_id = $customeraddress_id;
        $order_header->total_paid = $total_paid;
        $order_header->packing_fee = $packing_fee;
        $order_header->dropship_id = $dropship_id;
        $order_header->status_id = 11;
        $order_header->is_print = 0;
        $order_header->note = $note;
        $order_header->freesample_qty = $freesample_count;
        $order_header->barcode = Custom\OrderFunction::setBarcode($invoicenumber);
        $order_header->resi_otomatis = $resi_otomatis;
        $order_header->save();


        //kalau ada pakai poin, tambah ke discountpoint
        if (Cart::instance('discountpoint')->total() > 0) {
            $this->decreasePoint(Cart::instance('discountpoint')->total(), $order_header->id);
        }


        //tambah seluruh detailnya
        foreach (Cart::instance('main')->content() as $cart) {

            if ($cart->qty == 0) {
                continue;
            }

            $order_detail = new Orderdetail;
            $order_detail->orderheader_id = $order_header->id;
            $order_detail->product_id = $cart->id;
            $order_detail->qty = $cart->qty;
            $order_detail->price = $cart->price;
            $order_detail->weight = $cart->options->weight;
            $order_detail->save();

            #masukin ke stok balance
            StockBalanceFunction::addBalance($cart->id, 0, $cart->qty, 0, 'Penjualan dari invoice: ' . $invoicenumber);

            $total_weight += $cart->qty * $cart->options->weight;

            //motong stok utama..
            //motong stok dari remaining qty di masing-masing stockin...
            $stockin_qty = $cart->qty;

            $product = Product::find($cart->id);
            if ($product->is_set) {
                StockFunction::decreaseSetStock($product->id, $stockin_qty, $order_header);
            }
            $product->qty -= $stockin_qty;
            $product->save();

            #hitung stock booked dan stock soldnya
            $stock_booked = StockFunction::getStockBooked($product->id);
            $stock_sold = StockFunction::getStockSold($product->id);
            $product->stock_booked = $stock_booked;
            $product->stock_sold_30_days = $stock_sold;
            $product->save();

            $stockins = Stockin::whereProduct_id($cart->id)->where('qty', '>', 0)->orderBy('created_at')->get();
            foreach ($stockins as $stockin) {

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


        //ambil id dari diskon kupon
        $data_rowid = Cart::instance('discountcoupon')->search(array(
                    'name' => 'discountcoupon'
                ))[0];
        $coupon_data = Cart::instance('discountcoupon')->get($data_rowid);

        //kurangi available count di diskon kupon
        if ($coupon_data != null) {
            if ($coupon_data->options->id != 0) {
                $discount_coupon = Discountcoupon::find($coupon_data->options->id);

                //masukkin ke histori
                $discountcouponhistory = new Discountcouponhistory;
                $discountcouponhistory->discountcoupon_id = $discount_coupon->id;
                $discountcouponhistory->user_id = auth()->user()->id;
                $discountcouponhistory->initial_available_count = $discount_coupon->available_count;
                $discountcouponhistory->change_available_count = $discount_coupon->available_count - 1;
                $discountcouponhistory->save();

                $discount_coupon->available_count -= 1;
                $discount_coupon->save();

                $order_header->discountcoupon_id = $coupon_data->options->id;
            }
        }

        //update total weight dan grand total nya...
        $order_header->total_weight = $total_weight;
        $order_header->save();

        $this->destroyAllCart();

        if (auth()->user()->usersetting != null) {
            $this->sendInvoice(auth()->user(), $order_header->id);
        }


        //kasih tampilan kalau order sudah diterima, dan masukkan pembayaran, kasih juga nomor invoice nya
        return view('pages.front-end.thx')->with(array(
                    'order' => $order_header,
                    'total_cart' => $total_paid
        ));
    }

    public function addTo(Request $request) {

        $this->validate($request, [
            'nama_alamat' => 'required|max:64',
            'nama_depan' => 'required|max:32',
            'nama_belakang' => 'required|max:32',
            'alamat' => 'required',
            'kecamatan' => 'required',
            'kodepos' => 'required|max:6',
            'hp' => 'required|max:16',
        ]);


        //validasi nama address tidak boleh sama
        $customer_address = Customeraddress::whereAddress_name($request['nama_alamat'])
                        ->whereUser_id(auth()->user()->id)->first();
        if ($customer_address) {
            return back()->withErrors(array(
                        'address_name' => 'Alamat sudah terdaftar, silahkan gunakan yang lain...'
            ));
        }

        $customer_address = new Customeraddress;
        $customer_address->user_id = auth()->user()->id;
        $customer_address->address_name = $request['nama_alamat'];
        $customer_address->first_name = $request['nama_depan'];
        $customer_address->last_name = $request['nama_belakang'];
        $customer_address->alamat = $request['alamat'];
        $customer_address->kecamatan_id = $request['kecamatan'];
        $customer_address->kecamatan = $request['kecamatan_text'];
        $customer_address->kodepos = $request['kodepos'];
        $customer_address->hp = $request['hp'];
        $customer_address->save();

        return redirect('checkout')->with(array(
                    'msg' => 'Anda berhasil menyimpan alamat pengiriman baru..'
        ));
    }

    public function updateTo(Request $request) {

        $this->validate($request, [
            'nama_alamat' => 'required',
            'nama_depan' => 'required|max:32',
            'nama_belakang' => 'required|max:32',
            'alamat' => 'required',
            'kodepos' => 'required|max:6',
            'hp' => 'required|max:16',
        ]);

        $del_customer_address = Customeraddress::find($request['nama_alamat']);

        //save sebagai customer address baru
        $customer_address = new Customeraddress;
        $customer_address->address_name = $del_customer_address->address_name;
        $customer_address->user_id = $del_customer_address->user_id;
        $customer_address->first_name = $request['nama_depan'];
        $customer_address->last_name = $request['nama_belakang'];
        $customer_address->alamat = $request['alamat'];

        if (strlen($request['addto_kecamatan']) > 0) {
            $customer_address->kecamatan_id = $request['addto_kecamatan'];
            $customer_address->kecamatan = $request['addto_kecamatan_text'];
        }
        $customer_address->kodepos = $request['kodepos'];
        $customer_address->hp = $request['hp'];
        $customer_address->save();

        //hapus customer address sebelumnya
        $del_customer_address->delete();

        return back()->with(array(
                    'msg' => 'Anda berhasil melakukan perubahan alamat ..'
        ));
    }

    public function addFrom(Request $request) {

        $this->validate($request, [
            'nama_pengiriman' => 'required|max:64',
            'dikirim_oleh' => 'required|max:32',
            'hp_pengirim' => 'required|max:16'
        ]);


        //validasi nama address tidak boleh sama
        $dropship = Dropship::whereDropship_name($request['nama_pengiriman'])
                        ->whereUser_id(auth()->user()->id)->first();
        if ($dropship) {
            return back()->withErrors(array(
                        'nama_pengiriman' => 'Nama Pengiriman sudah terdaftar, silahkan gunakan nama yang lain..'
            ));
        }


        //Insert dropship baru
        $dropship = new Dropship;
        $dropship->user_id = auth()->user()->id;
        $dropship->dropship_name = $request['nama_pengiriman'];
        $dropship->name = $request['dikirim_oleh'];
        $dropship->hp = $request['hp_pengirim'];
        $dropship->save();

        return redirect('checkout')->with(array(
                    'msg' => 'Anda berhasil menyimpan asal pengiriman baru'
        ));
    }

    public function updateFrom(Request $request) {

        $this->validate($request, [
            'nama_pengiriman' => 'required',
            'dikirim_oleh' => 'required|max:32',
            'hp_pengirim' => 'required|max:16'
        ]);

        $del_dropship = Dropship::find($request['nama_pengiriman']);

        //validasi nama address tidak boleh sama
        $dropship = new Dropship;
        $dropship->dropship_name = $del_dropship->dropship_name;
        $dropship->user_id = $del_dropship->user_id;
        $dropship->name = $request['dikirim_oleh'];
        $dropship->hp = $request['hp_pengirim'];
        $dropship->save();

        //hapus yang sebelumnya
        $del_dropship->delete();

        return back()->with(array(
                    'msg' => 'Anda berhasil melakukan perubahan data alamat asal'
        ));
    }

    public function confirmPayment(Request $request) {

        $this->validate($request, [
            'bank' => 'required|min:1',
            'nama_rekening' => 'required|max:64',
            'pesanan' => 'required',
            'tanggal_bayar' => 'required',
            'note' => 'min:1'
        ]);

        if(auth()->check()){
            $user_id = auth()->user()->id;
        }
        else{
            $order = Orderheader::find($request->nomor_order);
            $user_id = $order->user_id;
        }

        $orders = $request->pesanan;
        $paymentconfirmation_id = 0;
        foreach($orders as $order){
            #kalau hanya konfirmasi satu maka tidak menambahkan ID dari payment yang lain
            $payment_confirmation = new Paymentconfirmation;
            $payment_confirmation->user_id = $user_id;
            $payment_confirmation->orderheader_id = $order;
            $payment_confirmation->account_name = $request['nama_rekening'];
            $payment_confirmation->payment_date = date('Y-m-d', strtotime($request['tanggal_bayar']));
            $payment_confirmation->bank_id = $request['bank'];
            $payment_confirmation->note = $request['note'];
            $payment_confirmation->save();

            #kalau multiple konfirmasi
            if (sizeof($request->pesanan) > 1) {
                if($paymentconfirmation_id == 0){
                    $paymentconfirmation_id = $payment_confirmation->id;
                }
                #set ID payment semuanya supaya tahu kalau ini adalah multiple konfirmasi
                $payment_confirmation->paymentconfirmation_id = $paymentconfirmation_id;
                $payment_confirmation->save();
            }


            //update status order nya jadi 12
            $order = Orderheader::find($order);
            $order->status_id = 12;
            $order->save();
        }

        #bikin messagenya
        $link = 'home';
        $message = "Terima kasih atas konfirmasi pembayarannya.<br><br>
                    Kami akan menerima verifikasi pembayaran anda setiap hari jam 10:00-15:00 kecuali hari minggu dan libur.<br><br>";
        if(auth()->check()){
            $message .= "Silahkan cek status orderan anda di:http://www.koreanluxury.com/history<br><br>";
            $link = 'history';
        }
        $message .= "NOTE:<br>
                    * Bagi yang SUKSES melakukan konfirmasi pembayaran SEBELUM JAM 15:00 wib, barang diusahakan dikirim di hari yang sama.<br>
                    * Jika lewat, akan dikirim keesokan harinya. KECUALI MINGGU tidak ada pengiriman.<br>
                    * Resi diupdate KEESOKAN HARI (jam 3-5sore) setelah barang dikirim. kecuali kiriman hari sabtu, resi diupdate senin";

        return redirect($link)->with(array(
                    'msg' => $message
        ));
    }

    public function confirmPaymentLink(Request $request) {

        $this->validate($request, [
            'bank' => 'required|min:1',
            'nama_rekening' => 'required|max:64',
            'pesanan' => 'required',
            'tanggal_bayar' => 'required',
            'note' => 'min:1'
        ]);

        $order = Orderheader::find($request->pesanan);

        #kalau hanya konfirmasi satu maka tidak menambahkan ID dari payment yang lain
        $payment_confirmation = new Paymentconfirmation;
        $payment_confirmation->user_id = $order->user_id;
        $payment_confirmation->orderheader_id = $order->id;
        $payment_confirmation->account_name = $request['nama_rekening'];
        $payment_confirmation->payment_date = date('Y-m-d', strtotime($request['tanggal_bayar']));
        $payment_confirmation->bank_id = $request['bank'];
        $payment_confirmation->note = $request['note'];
        $payment_confirmation->save();

        #update status order nya jadi 12
        $order->status_id = 12;
        $order->save();

        #bikin messagenya
        $link = 'home';
        $message = "Terima kasih atas konfirmasi pembayarannya.<br><br>
                    Kami akan menerima verifikasi pembayaran anda setiap hari jam 10:00-15:00 kecuali hari minggu dan libur.<br><br>";
        $message .= "NOTE:<br>
                    * Bagi yang SUKSES melakukan konfirmasi pembayaran SEBELUM JAM 15:00 wib, barang diusahakan dikirim di hari yang sama.<br>
                    * Jika lewat, akan dikirim keesokan harinya. KECUALI MINGGU tidak ada pengiriman.<br>
                    * Resi diupdate KEESOKAN HARI (jam 3-5sore) setelah barang dikirim. kecuali kiriman hari sabtu, resi diupdate senin";

        return redirect($link)->with(array(
                    'msg' => $message
        ));
    }

    public function viewOrder() {

        $is_owner = auth()->user()->is_owner;
        if ($is_owner) {
            $orders = Orderheader::orderBy('updated_at', 'desc')->paginate(50);
        } else {
            $orders = Orderheader::where('status_id', '=', '13')
                            ->where('is_print', '=', 0)
                            ->orderBy('updated_at', 'desc')->paginate(50);
        }

        return view('pages.admin-side.modules.order.vieworder')->with(array(
                    'orders' => $orders,
                    'is_owner' => $is_owner
        ));
    }

    public function filterShipOrder(Request $request) {

        $filter = $request['type'];
        $is_owner = auth()->user()->is_owner;

        if (strcmp($filter, 'before') == 0) {
            $orders = Orderheader::where('status_id', '=', '13')
                            ->where('orderheaders.is_print', '=', 0)
                            ->orderBy('created_at', 'desc')->paginate(50);
        } else {
            $orders = Orderheader::where('status_id', '=', '13')
                            ->where('orderheaders.is_print', '=', 1)
                            ->orderBy('created_at', 'desc')->paginate(50);
        }

        return view('pages.admin-side.modules.order.vieworder')->with(array(
                    'orders' => $orders,
                    'is_owner' => $is_owner
        ));
    }

    public function viewOrderPrint() {

        $is_owner = auth()->user()->is_owner;
        if ($is_owner) {
            $orders = Orderheader::orderBy('created_at', 'desc')->paginate(50);
        } else {
            $orders = Orderheader::where('status_id', '=', '13')
                            ->orderBy('created_at', 'desc')->paginate(50);
        }

        return view('pages.admin-side.modules.order.vieworderprint')->with(array(
                    'orders' => $orders,
                    'is_owner' => $is_owner
        ));
    }

    public function viewOrderDetail($id) {

        $order_header = Orderheader::find($id);

        $order_details = Orderdetail::whereOrderheader_id($order_header->id)->get();

        if ($order_header->customeraddress_id == 0) {
            $customer_address = Usersetting::whereUser_id($order_header->user_id)->first();
        } else {
            $customer_address = Customeraddress::where('id', '=', $order_header->customeraddress_id)
                            ->withTrashed()->first();
        }

        return view('pages.admin-side.modules.order.vieworderdetail')->with(array(
                    'order' => $order_header,
                    'order_details' => $order_details,
                    'customer_address' => $customer_address
        ));
    }

    public function viewPayment() {

        $payments = Paymentconfirmation::join('orderheaders', 'orderheaders.id', '=', 'paymentconfirmations.orderheader_id')
                ->where('orderheaders.status_id', '=', 12)
                ->orderBy('paymentconfirmations.created_at', 'asc')
                ->select(
                'paymentconfirmations.orderheader_id', 'paymentconfirmations.id', 'paymentconfirmations.user_id', 'paymentconfirmations.created_at', 'orderheaders.invoicenumber', 'orderheaders.grand_total', 'orderheaders.shipment_cost', 'orderheaders.discount_coupon', 'orderheaders.discount_point', 'paymentconfirmations.account_name', 'paymentconfirmations.payment_date', 'paymentconfirmations.bank_id', 'paymentconfirmations.note', 'orderheaders.unique_nominal', 'orderheaders.insurance_fee', 'orderheaders.customeraddress_id', 'orderheaders.user_id', 'orderheaders.status_id', 'paymentconfirmations.paymentconfirmation_id', 'orderheaders.total_paid', 'orderheaders.packing_fee'
        );

        $confirmpayments = Paymentconfirmation::join('orderheaders', 'orderheaders.id', '=', 'paymentconfirmations.orderheader_id')
                ->whereIn('orderheaders.status_id', [13, 14])
                ->orderBy('orderheaders.updated_at', 'desc')
                ->select(
                'paymentconfirmations.orderheader_id', 'paymentconfirmations.id', 'paymentconfirmations.user_id', 'paymentconfirmations.created_at', 'orderheaders.invoicenumber', 'orderheaders.grand_total', 'orderheaders.shipment_cost', 'orderheaders.discount_coupon', 'orderheaders.discount_point', 'paymentconfirmations.account_name', 'paymentconfirmations.payment_date', 'paymentconfirmations.bank_id', 'paymentconfirmations.note', 'orderheaders.unique_nominal', 'orderheaders.insurance_fee', 'orderheaders.customeraddress_id', 'orderheaders.user_id', 'orderheaders.status_id', 'paymentconfirmations.paymentconfirmation_id', 'orderheaders.total_paid', 'orderheaders.packing_fee'
        );

        $payments = $payments->union($confirmpayments)->get();

        return view('pages.admin-side.modules.order.viewpayment')->with(array(
                    'payments' => $payments
        ));
    }

    public function acceptPayment($id) {

        $order = Orderheader::find($id);

        if ($order->status_id == 12) {
            $order->status_id = 14;
            $order->payment_date = date('Y-m-d');
            $order->accept_time = \Carbon\Carbon::now()->toDateTimeString();
            $order->accept_by = auth()->user()->id;
            $order->save();

            #Blok dulu sementara, jangan kirim email
//            if ($order->user->usersetting != null) {
//                $email = $order->user->usersetting->email;
//                if (strlen($email) > 10) {
//                    Custom\OrderFunction::paymentAcceptedEmail($email);
//                }
//            }
        }

        return redirect('viewpayment')->with(array(
                    'msg' => 'Pembayaran sudah diterima'
        ));
    }

    public function bulkAcceptPayment(Request $request) {
        if ($request['bulk']) {
            foreach ($request['bulk'] as $order_id) {
                $order = Orderheader::find($order_id);
                if ($order->status_id == 12) {
                    $order->status_id = 13;
                    $order->payment_date = date('Y-m-d');
                    $order->accept_time = \Carbon\Carbon::now()->toDateTimeString();
                    $order->accept_by = auth()->user()->id;
                    $order->save();

//                    if ($order->user->usersetting != null) {
//                        $email = $order->user->usersetting->email;
//                        if (strlen($email) > 10) {
//                            Custom\OrderFunction::paymentAcceptedEmail($email);
//                        }
//                    }
                }
            }
        }

        return redirect('viewpayment')->with(array(
                    'msg' => 'Pembayaran sudah diterima'
        ));
    }

    public function rejectPayment($id) {

        $payment = Paymentconfirmation::find($id);

        $order = $payment->orderheader;


        if ($order->status_id == 12) {
            $order->status_id = 11;
            $order->save();
            $payment->delete();

            if (strcmp($order->invoicenumber[0], '#') != 0) {
                Custom\StockFunction::returnManualSalesStock($order);
            } else {
                if ($order->user->usersetting != null) {
                    Custom\OrderFunction::paymentRejectEmail($order->user->usersetting->email, $order->id);
                }
            }
        }


        return redirect('viewpayment')->with(array(
                    'msg' => 'Pembayaran sudah ditolak'
        ));
    }

    public function completeOrderCustomer($id) {

        $order = Orderheader::find($id);

        if ($order != null) {
            $order->status_id = 15;
            $order->save();
        }

        return back();
    }

    public function rejectOrderCustomer(Request $request) {

        $order = Orderheader::find($request->order_id);
        $reject_reason = $request->reject_reason;

        Custom\StockFunction::returnManualSalesStock($order, $reject_reason);

        if ($order->discountcoupon_id != 0) {
            $discountcoupon = $order->discountcoupon;

            if ($discountcoupon) {
                //masukkin histori
                $discountcouponhistory = new Discountcouponhistory;
                $discountcouponhistory->discountcoupon_id = $discountcoupon->id;
                $discountcouponhistory->user_id = auth()->user()->id;
                $discountcouponhistory->initial_available_count = $discountcoupon->available_count;
                $discountcouponhistory->change_available_count = $discountcoupon->available_count + 1;
                $discountcouponhistory->save();

                $discountcoupon->available_count = $discountcoupon->available_count + 1;
                $discountcoupon->save();
            }
        }

        return redirect('vieworder')->with(array(
                    'msg' => 'Pesanan berhasil dibatalkan'
        ));
    }

    public function isPrint($id) {

        $order = Orderheader::find($id);

        if ($order != null) {
            $order->is_print = 1;
            $order->save();
        }

        return redirect('vieworder');
    }

    public function searchOrder(Request $request) {

        if (strlen($request['search']) <= 0) {
            return redirect('vieworder');
        }

        $is_owner = auth()->user()->is_owner;
        if ($is_owner) {
            $orders = Orderheader::where('invoicenumber', 'like', '%' . $request['search'] . '%')
                    ->orderBy('updated_at', 'desc');
            $counter = $orders->count();
            $orders = $orders->paginate($counter);
        } else {
            $orders = Orderheader::where('invoicenumber', 'like', '%' . $request['search'] . '%')
                    ->whereIn('status_id', [13,14])
                    ->orderBy('updated_at', 'desc');
            $counter = $orders->count();
            $orders = $orders->paginate($counter);
        }

        return view('pages.admin-side.modules.order.vieworder')->with(array(
                    'orders' => $orders,
                    'is_owner' => $is_owner
        ));
    }

    public function searchShopee(Request $request) {

        if (strlen($request['search']) <= 0) {
            return redirect('vieworder');
        }

        $is_owner = auth()->user()->is_owner;
        if ($is_owner) {
            $orders = Orderheader::join('shopeesales', 'orderheaders.id', '=', 'shopeesales.orderheader_id')
                    ->where('shopeesales.shopee_invoice_number', 'like', '%' . $request['search'] . '%')
                    ->orderBy('orderheaders.updated_at', 'desc')
                    ->select('orderheaders.*');
            $counter = $orders->count();
            $orders = $orders->paginate($counter);
        } else {
            $orders = Orderheader::join('shopeesales', 'orderheaders.id', '=', 'shopeesales.orderheader_id')
                    ->where('shopeesales.shopee_invoice_number', 'like', '%' . $request['search'] . '%')
                    ->where('orderheaders.status_id', '=', 13)
                    ->orderBy('orderheaders.updated_at', 'desc')
                    ->select('orderheaders.*')
                    ->withTrashed();
            $counter = $orders->count();
            $orders = $orders->paginate($counter);
        }

        return view('pages.admin-side.modules.order.vieworder')->with(array(
                    'orders' => $orders,
                    'is_owner' => $is_owner
        ));
    }

    public function viewProcess(){
        $orders = Orderheader::join('paymentconfirmations', 'paymentconfirmations.orderheader_id', '=', 'orderheaders.id')
                ->where('orderheaders.status_id', '=', 13)
                ->where('orderheaders.process_by', '=', 0)
                ->orderBy('paymentconfirmations.payment_date')
                ->select('orderheaders.*')
                ->get();

        return view('pages.admin-side.modules.order.viewprocess')->with([
            'orders' => $orders
        ]);
    }

    public function processOrder(Request $request){
        $barcode = $request->barcode;

        $order_header = Orderheader::where('barcode', 'like', $barcode)
                ->where('status_id', '=', 13)
                ->first();
        if(!$order_header){
            return back()->with([
                'err' => 'Barcode salah.'
            ]);
        }
        $order_header->status_id = 14;
        $order_header->process_time = \Carbon\Carbon::now()->toDateTimeString();
        $order_header->process_by = auth()->user()->id;
        $order_header->save();

        return back()->with([
            'msg' => 'Order : ' . $order_header->invoicenumber . ' sudah berhasil diproses.'
        ]);
    }

    public function adminNotes(Request $request) {
        $order_id = $request->order_id;
        $admin_notes = $request->admin_notes;

        $order = Orderheader::find($order_id);
        if (!$order) {
            $response = [
                'status' => 0,
                'msg' => 'Tidak ada pesanan.'
            ];

            return json_encode($response);
        }

        $order->admin_notes = $admin_notes;
        $order->save();

        $response = [
            'status' => 1,
            'msg' => 'Catatan Admin berhasil ditambahkan.'
        ];

        return json_encode($response);
    }

    public function revertCancelOrder($id) {
        $order = Orderheader::find($id);
        //cek ketersediaan stok
        if (Custom\StockFunction::checkRevertStock($order->orderdetails)) {
            return redirect('vieworder')->with([
                        'err' => 'Order dengan invoice : ' . $order->invoicenumber . ' tidak memiliki jumlah stok yang cukup.'
            ]);
        }

        StockFunction::decreaseStock($order, 11);
        return redirect('vieworder')->with([
                    'msg' => 'Order dengan invoice : ' . $order->invoicenumber . ' berhasil dikembalikan ke status "BARU"'
        ]);
    }

    public function getOrderList(Request $request) {
        $order_id = $request->order_id;
        $order = Orderheader::find($order_id);
        if (!$order) {
            $response = [
                'result' => 0,
                'count' => 0,
                'msg' => 'No Order Found.'
            ];
            return json_encode($response);
        }

        $orders_data[] = [];
        $i = 0;

        foreach ($order->orderdetails as $order_detail) {
            $orders_data[$i] = [
                'id' => $order_detail->id,
                'product_name' => $order_detail->product->product_name,
                'qty_text' => number_format($order_detail->qty, 0, ',', '.'),
                'qty' => $order_detail->qty,
                'price_text' => 'Rp. ' . number_format($order_detail->price, 0, ',', '.'),
                'price' => $order_detail->price,
                'total' => 'Rp. ' . number_format($order_detail->qty * $order_detail->price, 0, ',', '.')
            ];
            $i++;
        }

        $response = [
            'result' => 1,
            'msg' => 'Menampilkan data pesanan',
            'count' => $order->count(),
            'data' => $orders_data,
            'total_paid' => $order->total_paid
        ];

        return json_encode($response);
    }

    //###############################################################################################
    private function destroyAllCart() {

        Cart::instance('main')->destroy();
        Cart::instance('shipcost')->destroy();
        Cart::instance('discountcoupon')->destroy();
        Cart::instance('discountpoint')->destroy();
        Cart::instance('total')->destroy();
        Cart::instance('unique')->destroy();
        Cart::instance('packingfee')->destroy();
    }

    private function decreasePoint($point, $order_id) {

        $customer_point = Customerpoint::whereUser_id(auth()->user()->id)->first();
        $customer_point->total_point -= $point;
        $customer_point->save();

        //insert ke history
        $point_history = new Pointhistory;
        $point_history->user_id = auth()->user()->id;
        $point_history->point_added = 0;
        $point_history->point_used = $point;
        $point_history->orderheader_id = $order_id;
        $point_history->available_date = null;
        $point_history->isCalculate = 1;
        $point_history->save();
    }

    private function sendInvoice($user, $order_id) {

        $order = Orderheader::find($order_id);
        $banks = Bank::all();

        $email_message = "Pesan Baru : ";
        $message_data = [
            'order' => $order,
            'banks' => $banks
        ];
        $data = [
            'subject' => 'Pesanan Baru',
            'destination_email' => $user->usersetting->email
        ];

        $status = Mail::send('emails.invoice', $message_data, function ($message) use ($data) {
                    $message->from('noreply@koreanluxury.com', 'Koreanluxury');
                    $message->to($data['destination_email'], $name = null);
                    $message->replyTo('noreply@koreanluxury.com', 'Koreanluxury');
                    $message->subject('[Koreanluxury] ' . $data['subject'] . ' - Jangan Dibalas !!');
                });
    }

}
