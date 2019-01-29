<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Price;
use App\Product;
use Illuminate\Http\Request;
use Excel;

class PriceController extends Controller {

    public function viewImportPrice() {

        $prices = Price::orderBy('valid_date', 'desc')->orderBy('product_id')->paginate(50);

        //menampilkan halaman untuk melakukan import price
        return view('pages.admin-side.modules.price.importprice')->with(array(
                    'prices' => $prices
        ));
    }

    public function addPrice() {
        $products = Product::orderBy('id')->get();
        return view('pages.admin-side.modules.price.addprice')->with([
                    'products' => $products
        ]);
    }

    public function downloadPriceFormat() {
        Custom\ExportFunction::exportForImportPrice();
    }

    public function importPrice(Request $request) {

        $this->validate($request, [
            'file' => 'required'
        ]);

        //inisialisasi data
        $file = $request['file'];
        $filesize = $file->getSize(); //hasil dalam satuan bytes..
        //Validasi ukuran file
        //Ukuran file > 0B
        if ($filesize <= 0) {
            return back()->with('err', 'File tidak ada data');
        }

        //Validasi extension file
        //Extension file harus .xls atau .xlsx
        if ($file->getClientOriginalExtension() != 'xls' && $file->getClientOriginalExtension() != 'xlsx') {
            return back()->with('err', 'File harus .xls atau .xlss');
        }


        echo '<h1>Daftar Import yang Gagal :</h1>';

        Excel::selectSheets('Sheet1')->load($file, function($reader) {

            //Baca smua sheets
            $reader->each(function($row) {

                if (ctype_space($row->id) || $row->id == null || $row->id == '') {
                    echo '<b style="color:red">ID tidak boleh kosong</b><br />';
                } else if (ctype_space($row->nama_produk) || $row->nama_produk == null || $row->nama_produk == '') {
                    echo '<b style="color:red">Nama Produk Harus Diisi</b><br />';
                } else if (ctype_space($row->tanggal_berlaku) || $row->tanggal_berlaku == null || $row->tanggal_berlaku == '') {
                    echo '<b style="color:red">Tanggal Berlaku Harus Diisi</b><br />';
                } else if (!is_numeric($row->regular_price)) {
                    echo '<b style="color:red">Harga Regular Harus Angka</b><br />';
                } else if (!is_numeric($row->silver)) {
                    echo '<b style="color:red">Harga Silver Harus Angka</b><br />';
                } else if (!is_numeric($row->gold)) {
                    echo '<b style="color:red">Harga Gold Harus Angka</b><br />';
                } else if (!is_numeric($row->platinum)) {
                    echo '<b style="color:red">Harga Platinum Harus Angka</b><br />';
                } else if (!is_numeric($row->sale_price)) {
                    echo '<b style="color:red">Harga Sale Harus Angka</b><br />';
                } else {

                    $product = Product::find($row->id);

                    if ($product) {
                        $price = new Price;
                        $price->product_id = $row->id;
                        $price->regular_price = $row->regular_price;
                        $price->reseller_1 = $row->silver;
                        $price->reseller_2 = $row->gold;
                        $price->vvip = $row->platinum;
                        $price->sale_price = $row->sale_price;
                        $price->valid_date = $row->tanggal_berlaku;
                        $price->save();

                        $product->last_price_update = \Carbon\Carbon::now()->toDateString();
                        $product->save();
                        echo '<b style="color:orange">Berhasil menambahkan harga pada produk ' . $row->nama_produk. '.</b><br />';
                    } else {
                        echo '<b style="color:red">Tidak ada Produk pada ID ' . $row->id . '</b><br />';
                    }
                }
            });
        });

        echo '<br /><a href="' . url()->previous() . '">Kembali</a>';
    }

    public function editPrice($id) {

        $price = Price::find($id);

        return view('pages.admin-side.modules.price.editprice')->with(array(
                    'price' => $price,
        ));
    }
    
    public function deletePrice($id) {
        $price = Price::find($id);

        #hapus pricenya
        if ($price) {
            $price->delete();

            #hapus price dari produk yang punya harga tersebut
            $products = Product::where('currentprice_id', '=', $id)
                    ->get();
            foreach ($products as $product) {
                $product->currentprice_id = 0;
                $product->save();
            }
        }

        return back()->with([
                    'msg' => 'Harga sudah dihapus..'
        ]);
    }
    
    public function deletePriceBulk(Request $request){
        $price_name = '';
        foreach($request->price as $price_id){
            $price = Price::find($price_id);
            if($price){
                #Kalau ada price, cek apakah price tersebut sedang digunakan atau tidak
                $product = Product::where('currentprice_id', '=', $price_id)
                        ->first();
                if($product){
                    $price_name .= "<br> Harga Produk: " . $price->product->product_name . ' sedang digunakan';
                    continue;
                }
                
                #Cek apakah price tersebut dalam "waiting list"
                if(\Carbon\Carbon::now()->toDateTimeString() <= $price->valid_date){
                    $price_name .= "<br> Harga Produk: " . $price->product->product_name . ' akan digunakan di kemudian hari';
                    continue;
                }
                
                #hapus data harga
                $price_name .= "<br> Harga Produk: " . $price->product->product_name . ' dihapus';
                $price->delete();
            }
        }
        
        return redirect('viewprice')->with([
            'msg' => 'Data harga telah dihapus.' . $price_name
        ]);
    }

    public function insertPrice(Request $request) {
        $this->validate($request, [
            'nama_produk' => 'required',
            'harga_regular' => 'required|numeric',
            'harga_reseller' => 'required|numeric',
            'harga_vip' => 'required|numeric',
            'harga_vvip' => 'required|numeric',
            'harga_sale' => 'min:0|numeric',
            'mulai_berlaku' => 'required|date'
        ]);

        //ambil id produk
        $product_id = explode(' | ', $request['nama_produk'])[0];

        //Insert harga produk
        $price = new Price;
        $price->product_id = $product_id;
        $price->regular_price = $request['harga_regular'];
        $price->reseller_1 = $request['harga_reseller'];
        $price->reseller_2 = $request['harga_vip'];
        $price->vvip = $request['harga_vvip'];
        $price->sale_price = $request['harga_sale'];
        $price->valid_date = date('Y-m-d', strtotime($request['mulai_berlaku']));
        $price->save();

        return redirect('viewprice')->with('msg', 'Data Harga berhasil ditambahkan..');
    }

    public function updatePrice(Request $request) {

        $this->validate($request, [
            'regular_price' => 'numeric',
            'reseller_1' => 'numeric',
            'reseller_2' => 'numeric',
            'vvip' => 'numeric',
            'sale_price' => 'numeric'
        ]);


        //Update harga produk
        $price = Price::find($request['price_id']);
        $price->regular_price = $request['regular_price'];
        $price->reseller_1 = $request['reseller_1'];
        $price->reseller_2 = $request['reseller_2'];
        $price->vvip = $request['vvip'];
        $price->sale_price = $request['sale_price'];
        $price->valid_date = date('Y-m-d', strtotime($request['valid_date']));
        $price->save();

        //update tanggal terakhir ubah harga
        $product = Product::find($price->product_id);
        $product->last_price_update = \Carbon\Carbon::now()->toDateString();
        $product->save();

        return redirect('viewprice')->with('msg', 'Data Harga berhasil diubah..');
    }

    public function searchPrice(Request $request) {
        if (strlen($request['search']) <= 0) {
            return redirect('viewprice');
        }

        $prices = Price::join('products', 'products.id', '=', 'prices.product_id')
                ->where('products.product_name', 'like', '%' . $request['search'] . '%')
                ->select('prices.id', 'prices.product_id', 'prices.regular_price', 'prices.reseller_1', 'prices.reseller_2', 'prices.vvip', 'prices.sale_price', 'prices.valid_date', 'products.product_name')
                ->orderBy('products.product_name');

        $counter = $prices->count();

        $prices = $prices->paginate($counter);

        //menampilkan halaman untuk melakukan import price
        return view('pages.admin-side.modules.price.importprice')->with(array(
                    'prices' => $prices
        ));
    }

}
