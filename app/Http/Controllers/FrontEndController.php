<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\ShipmentApi;
use App\Address;
use App\Contact;
use App\Term;
use App\Product;
use App\Bank;
use App\User;
use App\Orderheader;
use Mail;

class FrontEndController extends Controller {

    public function test() {

    }

    public function viewFrontEnd() {

        $most_buy_products = Product::join('prices', 'products.id', '=', 'prices.product_id')
                ->where('prices.valid_date', '<=', \Carbon\Carbon::now())
                ->where('prices.regular_price', '>=', 80000)
                ->where('products.qty', '>', 0)
                ->orderBy('products.total_buy', 'desc')
                ->distinct('products.id')
                ->select('products.*')
                ->take(100)
                ->get();
        if ($most_buy_products->count() > 8) {
            $most_buy_products = $most_buy_products->random(8);
        }

        $new_arrivals = Product::join('prices', 'products.id', '=', 'prices.product_id')
                ->where('prices.valid_date', '<=', \Carbon\Carbon::now())
                ->where('products.qty', '>', 0)
                ->where('products.last_stock_update', '>=', \Carbon\Carbon::now()->subDays(30)->toDateTimeString())
                ->orderBy('products.created_at', 'desc')
                ->distinct('products.id')
                ->select('products.*')
                ->take(8)
                ->get();

        $new_stocks = Product::join('prices', 'products.id', '=', 'prices.product_id')
                ->where('prices.valid_date', '<=', \Carbon\Carbon::now())
                ->where('products.qty', '>', 0)
                ->where('products.last_stock_update', '>=', \Carbon\Carbon::now()->subDays(30)->toDateTimeString())
                ->orderBy('products.last_stock_update', 'desc')
                ->distinct('products.id')
                ->select('products.*')
                ->take(40)
                ->get();
        if ($new_stocks->count() > 8) {
            $new_stocks = $new_stocks->random(8);
        }

        return view('pages.front-end.home')->with([
                    'most_buy_products' => $most_buy_products,
                    'new_arrivals' => $new_arrivals,
                    'new_stocks' => $new_stocks
        ]);
    }

    public function viewProducts() {

        $products = Product::join('prices', 'products.currentprice_id', '=', 'prices.id')
                ->where('prices.valid_date', '<=', \Carbon\Carbon::now())
                ->where('products.qty', '>', 0)
                ->groupBy('prices.product_id');

        $total_product = $products->get()->count();

        $products = $products
                ->select('products.id', 'products.product_code', 'products.product_name', 'products.product_desc', 'products.qty', 'products.brand_id', 'products.currentprice_id')
                ->paginate(30);

        return view('pages.front-end.products')->with(array(
                    'products' => $products,
                    'total_product' => $total_product,
                    'brand' => 0,
                    'category' => 0,
                    'subcategory' => 0,
                    'sort' => 0,
                    'search' => ''
        ));
    }

    public function showResult($brand, $category, $subcategory, $sort, $search = null) {

        $products = Product::join('prices', 'products.currentprice_id', '=', 'prices.id')
                ->where('prices.valid_date', '<=', \Carbon\Carbon::now());

        //tampilin brand kalau sort by brand
        if ($brand != 0) {
            $products = $products->where('products.brand_id', '=', $brand);
        }

        //tampilin kategori kalau sort by kategori
        if ($category != 0) {
            $products = $products->where('products.category_id', '=', $category);
        }

        //tampilin subkategori kalau sort by subkategori
        if ($subcategory != 0) {
            $products = $products->where('products.subcategory_id', '=', $subcategory);
        }

        //sort berdasarkan pilihan kalau ada
        switch ($sort) {
            case "new_product": $products = $products->orderBy('products.created_at', 'desc');
            case "new_price": $products = $products->orderBy('products.last_price_update', 'desc');
            case "new_stock": $products = $products->orderBy('products.last_stock_update', 'desc');
            case "most_stock": $products = $products->orderBy('products.qty', 'desc');
            case "name": $products = $products->orderBy('products.product_name');
            case "low_price": $products = $this->filterPrice($products, 'asc');
            case "high_price": $products = $this->filterPrice($products, 'desc');
            case "most_buy": $products = $products->orderBy('products.total_buy', 'desc');
            case 0: break;
            default: break;
        }

        //kalau search
        if ($search != null) {
            if (strcmp($search, 'sale') == 0) {
                $products = $products->where('prices.sale_price', '>', 0);
            } else if (strcmp($search, 'paket') == 0) {
                $products = $products->where('products.is_set', '=', 1);
            } else {
                $products = $products->where('products.product_name', 'like', '%' . $search . '%');
            }
        }

        //lengkapin data product
        $products = $products->where('prices.valid_date', '<=', \Carbon\Carbon::now())
                ->where('products.qty', '>', 0)
                ->groupBy('prices.product_id');

        //hitung total product
        $total_product = $products
                ->get()
                ->count();

        $products = $products
                ->select('products.id', 'products.product_code', 'products.product_name', 'products.product_desc', 'products.qty', 'products.brand_id', 'products.currentprice_id')
                ->orderBy('prices.sale_price', 'desc')
                ->orderBy('products.product_name')
                ->paginate(30);

        return view('pages.front-end.products')->with(array(
                    'products' => $products,
                    'total_product' => $total_product,
                    'brand' => $brand,
                    'category' => $category,
                    'subcategory' => $subcategory,
                    'sort' => $sort,
                    'search' => $search
        ));
    }

    public function viewHowto() {

        $term = Term::select(
                        'howtobuy', 'pricing_policy', 'payment', 'order', 'payment_confirmation', 'shipment', 'return', 'faq'
                )
                ->first();

        return view('pages.front-end.howto')->with(array(
                    'term' => $term
        ));
    }

    public function viewReseller() {

        $term = Term::select('reseller')
                ->first();

        return view('pages.front-end.reseller')->with(array(
                    'term' => $term
        ));
    }

    public function viewAbout() {

        $address = Address::select('address_1', 'address_2', 'address_3', 'address_4')
                ->first();
        $contact = Contact::select('owner_name', 'whatsapp', 'line', 'email')
                ->first();

        return view('pages.front-end.about')->with(array(
                    'address' => $address,
                    'contact' => $contact
        ));
    }

    public function sendMessage(Request $request) {

        $this->validate($request, [
            'nama' => 'required',
            'email' => 'required|email',
            'line_whatsapp' => 'required',
            'no_order' => 'min:10',
            'pesan' => 'required'
        ]);

        $subject = "Pesan dari www.koreanluxury.com " . $request['no_order'];
        $email_message = "LINE / Whatsapp : " . $request['line_whatsapp']
                . "\r\nNomor Order : " . $request['no_order']
                . "\r\nPesan : "
                . "\r\n" . $request['pesan'];

        $contact = Contact::first();

        Mail::raw($email_message, function ($message) use ($request, $subject, $contact) {
            $message->from('noreply@koreanluxury.com', $request['nama']);
            $message->to($contact->email, $name = null);
            $message->cc('fasikristophani@gmail.com', $name = null);
            $message->replyTo($request['email'], $request['nama']);
            $message->subject($subject);
        });

        return back()->with(array(
                    'msg' => 'Pesan Anda telah dikirim. Terima kasih.'
        ));
    }

    public function viewLogin() {

        if (auth()->check()) {
            if (auth()->user()->is_admin) {
                return redirect('websettings');
            }

            return redirect('home');
        } else {
            //menampilkan halaman login
            return view('pages.front-end.login');
        }
    }

    public function viewProfile() {
        $result = ShipmentApi::kecamatans();

        $user = User::find(auth()->user()->id);

        return view('pages.front-end.profile')->with(array(
                    'user' => $user,
                    'kecamatans' => $result['data'],
                    'kecamatan_count' => $result['count'],
        ));
    }

    public function viewPaymentConfirmation() {

        $banks = Bank::orderBy('bank_name')
                ->select('id', 'bank_name', 'bank_account', 'bank_account_name')
                ->get();

        if (auth()->check()) {
            $orders = Orderheader::whereUser_id(auth()->user()->id)
                            ->whereStatus_id(11)->orderBy('created_at')
                            ->select('id', 'invoicenumber')->get();
        } else {
            $orders = array();
        }


        return view('pages.front-end.paymentconfirmation')->with(array(
                    'banks' => $banks,
                    'orders' => $orders
        ));
    }

    public function viewPaymentLink($link) {
        #cari data order
        $order = Orderheader::where('payment_link', 'like', $link)
                ->first();

        #ambil data banks
        $banks = Bank::orderBy('bank_name')
                ->select('id', 'bank_name', 'bank_account', 'bank_account_name')
                ->get();

        return view('pages.front-end.paymentlink')->with([
                    'banks' => $banks,
                    'order' => $order
        ]);
    }

    public function viewResetPassword() {
        return view('pages.front-end.resetpassword');
    }

    public function viewResetUsername() {
        return view('pages.front-end.resetusername');
    }

    public function viewAddTo() {
        $result = ShipmentApi::kecamatans();

        return view('pages.front-end.addto')->with([
                    'kecamatans' => $result['data'],
                    'kecamatan_count' => $result['count'],
        ]);
    }

    public function viewAddFrom() {

        return view('pages.front-end.addfrom');
    }

    private function filterPrice($products, $filter_order) {

        $user_status = 1;
        if (auth()->check()) {
            if (auth()->user()->usersetting) {
                $user_status = auth()->user()->usersetting->status_id;
            }
        }

        switch ($user_status) {
            case 1:
                $products = $products->orderBy('regular_price', $filter_order);
                break;
            case 2:
                $products = $products->orderBy('reseller_1', $filter_order);
                break;
            case 3:
                $products = $products->orderBy('reseller_2', $filter_order);
                break;
        }

        return $products;
    }

    public function viewStock() {

        $products = Product::where('qty', '>', 0)
                ->orderBy('product_name')
                ->select('product_name', 'qty')
                ->get();

        return view('pages.front-end.stock')->with([
                    'products' => $products
        ]);
    }

}
