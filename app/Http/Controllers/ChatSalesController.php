<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\ShipmentApi;
use App\Http\Controllers\Custom\PriceFunction;
use App\Product;
use Cart;
use App\User;
use App\Orderheader;
use App\Orderdetail;
use App\Stockin;
use App\Customeraddress;
use App\Paymentconfirmation;
use App\Dropship;
use App\Http\Controllers\Custom\StockFunction;
use App\Http\Controllers\Custom\OrderFunction;

class ChatSalesController extends Controller {

    public function viewChatSales(Request $request) {

        Cart::instance('manualsalescart')->destroy();

        $data_rowid = Cart::instance('manualsalesdata')->search(array(
                    'name' => 'data'
                ))[0];

        $cart_data = Cart::instance('manualsalesdata')->get($data_rowid);

        $products = Product::distinct()
                ->join('prices', 'prices.product_id', '=', 'products.id')
                ->where('products.qty', '<>', 0)
                ->orWhere('products.reserved_qty', '>', 0)
                ->orderBy('products.product_name')
                ->select('products.id', 'products.product_name', 'products.qty')
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

}
