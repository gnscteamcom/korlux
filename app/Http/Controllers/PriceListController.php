<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Product;
use Excel;

class PriceListController extends Controller {

    public function downloadPriceList() {
        Excel::create('price_list', function($excel) {

            $excel->sheet('Sheet1', function($sheet) {

                $sheet->setAutoSize(true);

                $sheet->cell('A1:B1', function($cells) {
                    $cells->setBackground('#00B0F0');
                    $cells->setFontSize(16);
                    $cells->setFontWeight('bold');
                    $cells->setBorder('none', 'none', 'thick', 'none');
                    $cells->setAlignment('center');
                });

                #Tampilkan tanggal dan jam generate
                $sheet->row(1, array(
                    'Tanggal dan Jam Generate', 'Level Saya'
                ));
                $sheet->row(2, array(
                    \Carbon\Carbon::now()->toDateTimeString(), auth()->user()->usersetting->status->status
                ));

                $sheet->cell('A4:D4', function($cells) {
                    $cells->setBackground('#00B0F0');
                    $cells->setFontSize(16);
                    $cells->setFontWeight('bold');
                    $cells->setBorder('none', 'none', 'thick', 'none');
                    $cells->setAlignment('center');
                });

                $sheet->row(4, array(
                    'Nama Produk', 'Reguler', 'Harga Saya', 'Sale'
                ));
                
                $products = Product::join('prices', 'prices.id', '=', 'products.currentprice_id')
                        ->orderBy('products.product_name')
                        ->select('products.product_name', 'prices.regular_price', 'prices.reseller_1', 'prices.reseller_2', 'prices.vvip', 'prices.sale_price')
                        ->get();
                $i = 5;

                $status_id = auth()->user()->usersetting->status_id;
                foreach ($products as $product) {
                    $my_price = 0;
                    switch($status_id){
                        case 2: $my_price = $product->reseller_1;
                            break;
                        case 3: $my_price = $product->reseller_2;
                            break;
                        case 4: $my_price = $product->vvip;
                            break;
                    }
                    $sheet->row($i++, array(
                        $product->product_name, $product->regular_price, $my_price, $product->sale_price
                    ));
                }
            });
        })->export('xls');
    }

}
