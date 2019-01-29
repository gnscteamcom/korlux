<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Product;
use App\Discountcoupon;
use App\Customerpoint;
use App\Productclass;
use App\Contact;
use App\Pointconfig;
use Cart;
use App\Http\Controllers\Custom\FreeSampleFunction;
use App\Http\Controllers\Custom\PackingFeeFunction;
use App\Http\Controllers\Api\ShipmentApi;

class CartController extends Controller {

    public function viewCart() {
        $this->refreshCartValue();
        $this->updateTotal();

        return view('pages.front-end.cart')->with(array(
                    'carts' => Cart::instance('main')->content()
        ));
    }

    public function refreshCart(){
        Cart::instance('main')->destroy();
        Cart::instance('shipcost')->destroy();
        Cart::instance('discountcoupon')->destroy();
        Cart::instance('discountpoint')->destroy();
        Cart::instance('insurancecost')->destroy();
        Cart::instance('unique')->destroy();
        Cart::instance('packingfee')->destroy();

        Cart::instance('manualsalesdata')->destroy();
        Cart::instance('manualsalescart')->destroy();

        return redirect('cart');
    }

    public function viewCheckout() {
        #cek apakah data alamat, kota dan kecamatan sudah lengkap atau belum, paksa isi
        $user_setting = auth()->user()->usersetting;
        if(strlen($user_setting->alamat) <= 0 || $user_setting->kecamatan_id <= 0 ||
                strlen($user_setting->kodepos) <= 0 || strlen($user_setting->hp) <= 0){
            return redirect('profile')->with([
                'err' => 'Silahkan lengkapi alamat Anda terlebih dahulu.'
            ]);
        }

        $this->refreshCartValue();
        $this->updateTotal();

        $total_point = 0;
        if (auth()->user()->customerpoint != null) {
            $total_point = auth()->user()->customerpoint->total_point;
        }

        $user_status = 1;
        if (auth()->user()->usersetting != null) {
            $user_status = auth()->user()->usersetting->status_id;
        }

        //Free Sampel
        $freesample_minimum_nominal = FreeSampleFunction::getFreeSampleMinimumNominal();
        $is_accumulative = FreeSampleFunction::isFreeSampleAccumulative();
        $is_active = FreeSampleFunction::isActiveFreeSample();
        $free_sample = FreeSampleFunction::countFreeSample($freesample_minimum_nominal, $is_accumulative, $is_active, Cart::instance('main')->total());

        #hitung total berat dalam satuan kg.
        $total_weight = 0;
        foreach (Cart::instance('main')->content() as $cart) {
            $total_weight += $cart->options->weight * $cart->qty;
        }
        $total_weight = Custom\OrderFunction::calculateWeight($total_weight);

        #ambil data kecamatan
        $result = ShipmentApi::kecamatans();

        #ambil data owner
        $contact = Contact::first();

        #ambil config point
        $config = Pointconfig::first();

        return view('pages.front-end.checkout')->with(array(
                    'user' => auth()->user(),
                    'user_status' => $user_status,
                    'total_point' => $total_point,
                    'freesample_minimum_nominal' => $freesample_minimum_nominal,
                    'is_accumulative' => $is_accumulative,
                    'free_sample' => $free_sample,
                    'is_active' => $is_active,
                    'total_weight' => $total_weight,
                    'kecamatans' => $result['data'],
                    'kecamatan_count' => $result['count'],
                    'contact' => $contact,
                    'config' => $config,
        ));
    }

    public function addToCart(Request $request) {
        if (!auth()->check()) {
            return redirect('login')->with(array(
                        'msg' => 'Silahkan login terlebih dahulu..'
            ));
        }


        $user = auth()->user();
        $product_id = $request['product_id'];
        $price = $request['price'];
        $qty = $request['qty'];

        $product = Product::find($product_id);

        $discountqty_id = '';
        if ($product->productclasses->count() > 0) {
            $discountqty_id = $product->productclasses->first()->discountqty_id;
        }

        $image_path = '';
        if ($product->productimages->count() > 0) {
            $image_path = $product->productimages->first()->image_path;
        }

        Cart::add($product->id, $product->product_name, $qty, $price, array(
            'weight' => $product->weight, 'max' => $product->qty,
            'image_path' => $image_path,
            'discountqty_id' => $discountqty_id
        ));

        $this->updateWholesalePrice($discountqty_id, $product->id);
        $this->updateTotal();

        return redirect('products')->with(array(
                    'msg' => 'Produk berhasil ditambahkan ke keranjang belanja.'
        ));
    }

    public function addToCartAutomatic(Request $request){
        #kalau belum login, paksa login
        if(!auth()->check()){
            return [
                'result' => 0,
                'link' => url('login'),
                'msg' => 'Please Login'
            ];
        }

        $user = auth()->user();

        #cek apakah data alamat, kota dan kecamatan sudah lengkap atau belum, paksa isi
        $user_setting = $user->usersetting;
        if(strlen($user_setting->alamat) <= 0 || strlen($user_setting->kecamatan_id) <= 0 ||
                strlen($user_setting->kodepos) <= 0 || strlen($user_setting->hp) <= 0){
            return [
                'result' => 0,
                'link' => url('profile'),
                'msg' => 'Please complete your data in your profile tab.'
            ];
        }

        $product_id = $request->data[0]['value'];
        $price = $request->data[1]['value'];
        $qty = $request->data[2]['value'];

        if($qty > 0){
            $product = Product::find($product_id);

            if($qty > $product->qty){
                return [
                    'result' => 0,
                    'link' => '',
                    'err' => 'Stok tidak cukup. Gagal melakukan pembelian.'
                ];
            }

            $discountqty_id = '';
            if ($product->productclasses->count() > 0) {
                $discountqty_id = $product->productclasses->first()->discountqty_id;
            }

            $image_path = '';
            if ($product->productimages->count() > 0) {
                $image_path = $product->productimages->first()->image_path;
            }

            $rowid = Cart::search(array('id' => $product->id))[0];


            //update kalau sudah ada
            //insert kalau belum ada
            if($rowid){
                Cart::update($rowid, array(
                    'qty' => intval($qty),
                    'price' => $price
                ));
            }
            else{
                Cart::add($product->id, $product->product_name, $qty, $price, array(
                    'weight' => $product->weight, 'max' => $product->qty,
                    'image_path' => $image_path,
                    'discountqty_id' => $discountqty_id
                ));
            }

            $this->updateWholesalePrice($discountqty_id, $product->id);
            $this->updateTotal();


            return [
                'result' => 1,
                'msg' => 'Produk berhasil ditambahkan ke keranjang belanja.',
                'totalItem' => Cart::instance('main')->count(false),
                'product_cart' => $product->product_name . ' ( ' . $qty . ' buah )'
            ];

        }

        return [
            'result' => 0,
            'msg' => 'Tidak ada barang yang ditambahkan.',
        ];

    }

    public function updateCart(Request $request) {

        $user = auth()->user();

        $i = 0;
        foreach (Cart::content() as $cart) {

            if ($request['qty'][$i] != $cart->qty) {

                #ambil data produk
                $product = Product::find($cart->id);

                Cart::instance('main')->update($request['cart_rowid'][$i], array(
                    'qty' => intval($request['qty'][$i]),
                    'options' => [
                        'max' => $product->qty
                    ]
                ));

                $cart = Cart::instance('main')->get($request['cart_rowid'][$i]);
                $this->updateWholesalePrice($cart->options->discountqty_id, $cart->id);
            }

            $i++;
        }

        $this->updateTotal();

        return redirect('cart');
    }

    public function deleteItem($id) {

        $cart = Cart::instance('main')->get($id);
        Cart::remove($id);
        $this->updateWholesalePrice($cart->options->discountqty_id, $cart->id);
        $this->updateTotal();

        return back();
    }

    public function checkKode(Request $request) {

        $kode = $request['kode'];
        $nominal_discount = 0;
        $id = 0;

        if (strlen($kode) > 0) {
            $current = \Carbon\Carbon::now()->toDateString();

            #cek dulu apakah kode tersebut untuk user tersebut
            $discount_coupon = Discountcoupon::whereCoupon_code($kode)
                            ->where('valid_date', '<=', $current)
                            ->where('expired_date', '>=', $current)
                            ->where('available_count', '>', 0)
                            ->where('only_for_user', '=', auth()->user()->id)
                            ->select('id', 'nominal_discount', 'percentage_discount')
                            ->orderBy('created_at', 'asc')->first();

            #kalau tidak ada, baru ambil dari global
            if(!$discount_coupon){
                $discount_coupon = Discountcoupon::whereCoupon_code($kode)
                                ->where('valid_date', '<=', $current)
                                ->where('expired_date', '>=', $current)
                                ->where('available_count', '>', 0)
                                ->where('available_for_status', '=', auth()->user()->usersetting->status_id)
                                ->whereNull('only_for_user')
                                ->select('id', 'nominal_discount', 'percentage_discount')
                                ->orderBy('created_at', 'asc')
                                ->first();
            }

            if($discount_coupon){
                if ($discount_coupon->percentage_discount != 0) {
                    $total = Cart::instance('main')->total();
                    $nominal_discount = intval($total * ($discount_coupon->percentage_discount / 100));
                } else {
                    $nominal_discount = $discount_coupon->nominal_discount;
                }
            }
        }

        if ($discount_coupon != null) {
            $id = $discount_coupon->id;
        }

        Cart::instance('discountcoupon')->destroy();
        Cart::instance('discountcoupon')->add(1, 'discountcoupon', 1, $nominal_discount, array(
            'id' => $id
        ));
        $this->updateTotal();

        return $nominal_discount;
    }

    public function checkPoin(Request $request) {

        $poin = 0;
        $max_point = 0;
        $customerpoint = Customerpoint::whereUser_id(auth()->user()->id)->first();

        if (auth()->user()->usersetting->status_id == 1) {
            if ($customerpoint != null) {
                $poin = $request['poin'];
                $max_point = $customerpoint->total_point;
                if ($poin > $max_point) {
                    $poin = 0;
                }
            }

            Cart::instance('discountpoint')->destroy();
            Cart::instance('discountpoint')->add('1', 'discountpoint', 1, $poin);
            $this->updateTotal();
        }

        return $poin;
    }

    public function setShipCost(Request $request) {

        $shipcost = $request['shipcost'];
        $ship_method = $request['ship_method'];

        $total_weight = 0;
        foreach (Cart::instance('main')->content() as $cart) {
            $total_weight += $cart->options->weight * $cart->qty;
        }
        $total_weight = Custom\OrderFunction::calculateWeight($total_weight);

        Cart::instance('shipcost')->destroy();
        Cart::instance('shipcost')->add('1', 'shipcost', 1, $shipcost * $total_weight);
        $this->updateTotal($ship_method);

        $response = [
            'weight' => $total_weight,
            'packing_fee' => Cart::instance('packingfee')->total(),
            'ship_method' => $ship_method
        ];

        return json_encode($response);
    }

    public function setInsuranceCost(Request $request) {

        $insurancecost = $request['insurancecost'];

        Cart::instance('insurancecost')->destroy();
        Cart::instance('insurancecost')->add('1', 'insurancecost', 1, $insurancecost);
        $this->updateTotal($request->ship_method);
    }

    public function getSummary() {

        $result = array();
        $result['shipcost'] = Cart::instance('shipcost')->total();
        $result['discountcoupon'] = Cart::instance('discountcoupon')->total();
        $result['discountpoint'] = Cart::instance('discountpoint')->total();
        $result['insurancecost'] = Cart::instance('insurancecost')->total();
        $result['total'] = Cart::instance('total')->total();
        $result['packingfee'] = Cart::instance('packingfee')->total();

        return $result;
    }

    private function calculateUniqueNominal() {

        $unique_nominal = Cart::instance('unique')->total();

        if ($unique_nominal == 0) {
            $unique_nominal = rand(1, 400);
            Cart::instance('unique')->add('1', 'unique', 1, $unique_nominal);
        }
    }

    private function refreshCartValue() {

        $this->calculateUniqueNominal();
        Cart::instance('shipcost')->destroy();
        Cart::instance('discountcoupon')->destroy();
        Cart::instance('discountpoint')->destroy();
        Cart::instance('insurancecost')->destroy();
        Cart::instance('packingfee')->destroy();
    }

    private function updateTotal($ship_method = 0) {
        $this->updatePackingFee($ship_method);

        Cart::instance('total')->destroy();
        $total = Cart::instance('main')->total() - Cart::instance('discountcoupon')->total() - Cart::instance('discountpoint')->total();
        if ($total < 0) {
            $total = 0;
        }
        $total = $total + Cart::instance('shipcost')->total() + Cart::instance('insurancecost')->total() + Cart::instance('unique')->total() + Cart::instance('packingfee')->total();
        Cart::instance('total')->add('1', 'total', 1, $total);

    }

    private function updatePackingFee($ship_method = 0){
        #hitung packing fee
        Cart::instance('packingfee')->destroy();
        $grand_total = Cart::instance('main')->total();

        $packing_fee = PackingFeeFunction::getPackingFee($grand_total, $ship_method);
        Cart::instance('packingfee')->add('1', 'packingfee', 1, $packing_fee);
    }

    private function updateWholesalePrice($discountqty_id, $product_id) {

        $product = Product::where('id', '=', $product_id)
                        ->select('currentprice_id')->first();

        if ($discountqty_id != '') {
            $rows = Cart::instance('main')->search(array('options' => array('discountqty_id' => $discountqty_id)));
            $qty = 0;
            if ($rows != false) {
                foreach ($rows as $row_id) {
                    $qty += Cart::instance('main')->get($row_id)->qty;
                }

                $price = Custom\PriceFunction::getCurrentPrice($product->currentprice_id);

                $productclasses = Productclass::join('discountqties', 'discountqties.id', '=', 'productclasses.discountqty_id')
                                ->where('productclasses.product_id', '=', $product_id)
                                ->where('productclasses.userstatus_id', '=', auth()->user()->usersetting->status_id)
                                ->orderBy('discountqties.min_qty')
                                ->select('discountqties.min_qty', 'discountqties.price')->get();
                foreach ($productclasses as $productclass) {
                    if ($qty >= $productclass->min_qty) {
                        if($price > $productclass->price){
                            $price = $productclass->price;
                        }
                    }
                }

                foreach ($rows as $row_id) {
                    Cart::instance('main')->update($row_id, array(
                        'price' => $price
                    ));
                }
            }
        }
    }

}
