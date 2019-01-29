<?php

namespace App\Http\Controllers\Custom;
use App\Http\Controllers\Custom\StringFunction;
use App\Product;
use App\Capital;
use App\Customerpoint;
use App\Orderheader;
use App\Orderdetail;
use App\Pointhistory;
use App\Price;
use App\Stockin;
use App\User;
use App\Kecamatan;
use App\Kota;
use App\Shipcost;
use App\Shipmethod;
use App\Stockbalance;
use Excel;

class ExportFunction {


    public static function exportForImportShopeeSales() {
        Excel::create('shopee', function($excel) {

            $excel->sheet('Sheet1', function($sheet) {

                $sheet->setAutoSize(true);

                $sheet->cell('A1:K1', function($cells) {
                    $cells->setBackground('#00B0F0');
                    $cells->setFontSize(16);
                    $cells->setFontWeight('bold');
                    $cells->setBorder('none', 'none', 'thick', 'none');
                    $cells->setAlignment('center');
                });

                $sheet->setColumnFormat([
                    'B' => '#0'
                ]);

                $sheet->row(1, array(
                    'nomor_pesanan', 'kode_resi', 'username', 'opsi_pengiriman', 'nama_penerima', 'nomor_telepon', 'alamat_pengiriman', 'kodepos', 'daftar_produk', 'kirim_sebelum', 'dikirim_oleh', 'nomor_hp_pengirim'
                ));
            });
        })->export('xls');
    }

    public static function exportForImportStockOpname(){

        Excel::create('stockopname', function($excel){

            $excel->sheet('Sheet1', function($sheet){

                $sheet->setAutoSize(true);

                $sheet->cell('A1:E1', function($cells){
                    $cells->setBackground('#00B0F0');
                    $cells->setFontSize(16);
                    $cells->setFontWeight('bold');
                    $cells->setBorder('none', 'none', 'thick', 'none');
                    $cells->setAlignment('center');
                });

                $sheet->setColumnFormat([
                    'B' => '#0'
                ]);

                $sheet->row(1, array(
                    'id', 'barcode', 'nama_produk', 'qty', 'reserved_qty'
                ));

                //hanya produk yang bukan paket saja yang bisa di opname
                $products = Product::where('is_set', '=', 0)
                        ->orderBy('barcode')->get();
                $i = 2;

                foreach($products as $product){

                    $sheet->row($i++, array(
                        $product->id, $product->barcode, $product->product_name, 0, 0
                    ));

                }

            });

        })->export('xls');

    }

    public static function exportForImportStockRevise(){

        Excel::create('stockrevise', function($excel){

            $excel->sheet('Sheet1', function($sheet){

                $sheet->setAutoSize(true);

                $sheet->cell('A1:D1', function($cells){
                    $cells->setBackground('#00B0F0');
                    $cells->setFontSize(16);
                    $cells->setFontWeight('bold');
                    $cells->setBorder('none', 'none', 'thick', 'none');
                    $cells->setAlignment('center');
                });
                $sheet->cell('E1:F1', function($cells){
                    $cells->setBackground('#FFFF00');
                    $cells->setFontSize(16);
                    $cells->setFontWeight('bold');
                    $cells->setBorder('none', 'none', 'thick', 'none');
                    $cells->setAlignment('center');
                });


                $sheet->row(1, array(
                    'id', 'nama_produk', 'stok_utama', 'stok_cadangan', 'ubah_stok_utama', 'catatan'
                ));

                //hanya produk yang bukan paket saja yang bisa di opname
                $products = Product::orderBy('product_name')
                        ->get();
                $i = 2;

                foreach($products as $product){

                    $sheet->row($i++, array(
                        $product->id, $product->product_name, $product->qty, $product->reserved_qty, 0, ''
                    ));

                }

            });

        })->export('xls');

    }


    public static function exportForImportPrice(){

        Excel::create('price', function($excel){

            $excel->sheet('Sheet1', function($sheet){

                $sheet->setAutoSize(true);

                $sheet->cell('A1:H1', function($cells){
                    $cells->setBackground('#00B0F0');
                    $cells->setFontSize(16);
                    $cells->setFontWeight('bold');
                    $cells->setBorder('none', 'none', 'thick', 'none');
                    $cells->setAlignment('center');
                });

                $sheet->row(1, array(
                    'id', 'nama_produk', 'regular_price', 'silver',
                    'gold', 'platinum', 'sale_price', 'tanggal_berlaku'
                ));

                $products = Product::orderBy('id')->get();
                $i = 2;
                foreach($products as $product){

                    $sheet->row($i++, array(
                        $product->id, $product->product_name, '', '', '', '', 0, 'yyyy-mm-dd'
                    ));

                }

            });

        })->export('xls');

    }


    public static function exportForImportWholesalePrice() {

        Excel::create('wholesale_price', function($excel) {

            $excel->sheet('Sheet1', function($sheet) {

                $sheet->setAutoSize(true);

                $sheet->cell('A1:M1', function($cells) {
                    $cells->setBackground('#00B0F0');
                    $cells->setFontSize(16);
                    $cells->setFontWeight('bold');
                    $cells->setBorder('none', 'none', 'thick', 'none');
                    $cells->setAlignment('center');
                });

                $sheet->row(1, array(
                    'qty_minimum_regular_1', 'harga_satuan_regular_1', 'qty_minimum_regular_2', 'harga_satuan_regular_2',
                    'qty_minimum_regular_3', 'harga_satuan_regular_3', 'qty_minimum_reseller_1', 'harga_satuan_reseller_1',
                    'qty_minimum_reseller_2', 'harga_satuan_reseller_2', 'qty_minimum_reseller_3', 'harga_satuan_reseller_3',
                    'kode_produk'
                ));

                $sheet->row(2, array(
                    'qty minimum grosir regular level 1', 'harga satuan grosir regular level 1', 'qty minimum grosir regular level 2 (boleh kosong)', 'harga satuan grosir regular level 2 (boleh kosong)',
                    'qty minimum grosir regular level 3 (boleh kosong)', 'harga satuan grosir regular level 3 (boleh kosong)', 'qty minimum grosir reseller level 1 (boleh kosong)', 'harga satuan grosir reseller level 1 (boleh kosong)',
                    'qty minimum grosir reseller level 2 (boleh kosong)', 'harga satuan grosir reseller level 2 (boleh kosong)', 'qty minimum grosir reseller level 3 (boleh kosong)', 'harga satuan grosir reseller level 3 (boleh kosong)',
                    'kode produk dipisah , (koma). Contoh: kodeproduk1,kodeproduk2,kodeproduk3'
                ));
            });
        })->export('xls');
    }

    public static function exportForImportStockin(){

        Excel::create('stockin', function($excel){

            $excel->sheet('Sheet1', function($sheet){

                $sheet->setAutoSize(true);

                $sheet->cell('A1:E1', function($cells){
                    $cells->setBackground('#00B0F0');
                    $cells->setFontSize(16);
                    $cells->setFontWeight('bold');
                    $cells->setBorder('none', 'none', 'thick', 'none');
                    $cells->setAlignment('center');
                });

                $sheet->row(1, array(
                    'id', 'barcode', 'nama_produk', 'qty', 'reserved_qty'
                ));

                //yang bisa di stock in hanya produk yang bukan set
                $products = Product::where('is_set', '=', 0)
                        ->orderBy('barcode')->get();
                $i = 2;

                foreach($products as $product){

                    $sheet->row($i++, array(
                        $product->id, $product->barcode, $product->product_name, 0, 0
                    ));

                }

            });

        })->export('xls');

    }


    public static function exportMasterCapital(){

        Excel::create('daftar_modal', function($excel){

            $excel->sheet('Sheet1', function($sheet){

                $sheet->setAutoSize(true);

                $sheet->cell('A1:C1', function($cells){
                    $cells->setBackground('#00B0F0');
                    $cells->setFontSize(16);
                    $cells->setFontWeight('bold');
                    $cells->setBorder('none', 'none', 'thick', 'none');
                    $cells->setAlignment('center');
                });

                $sheet->row(1, array(
                    'Nama Produk', 'Modal', 'Tanggal Update'
                ));

                $capitals = Capital::all();
                $i = 2;

                foreach($capitals as $capital){

                    $sheet->row($i++, array(
                        $capital->product->product_name, $capital->capital, $capital->updated_at
                    ));

                }

            });

        })->export('xls');

    }


    public static function exportMasterPoint(){

        Excel::create('daftar_poin_loyalty', function($excel){

            $excel->sheet('Sheet1', function($sheet){

                $sheet->setAutoSize(true);

                $sheet->cell('A1:D1', function($cells){
                    $cells->setBackground('#00B0F0');
                    $cells->setFontSize(16);
                    $cells->setFontWeight('bold');
                    $cells->setBorder('none', 'none', 'thick', 'none');
                    $cells->setAlignment('center');
                });

                $sheet->row(1, array(
                    'Username', 'Nama Pengguna', 'Poin Tersedia', 'Tanggal Update'
                ));

                $customerpoints = Customerpoint::all();
                $i = 2;

                foreach($customerpoints as $customerpoint){
                    $user = $customerpoint->user;
                    $sheet->row($i++, array(
                        $user->username, $user->usersetting->first_name . ' ' . $user->usersetting->last_name, $customerpoint->total_point, $customerpoint->updated_at
                    ));

                }

            });

        })->export('xls');

    }


    public static function exportMasterOrderheader($date_start, $date_end){

        $date_start = \Carbon\Carbon::instance(new \DateTime($date_start))->toDateTimeString();
        $date_end = \Carbon\Carbon::instance(new \DateTime($date_end))->addDay()->toDateTimeString();

        Excel::create('daftar_order', function($excel) use ($date_start, $date_end){

            $excel->sheet('Sheet1', function($sheet) use($date_start, $date_end){

                $sheet->setAutoSize(true);

                $sheet->cell('A1:M1', function($cells){
                    $cells->setBackground('#00B0F0');
                    $cells->setFontSize(16);
                    $cells->setFontWeight('bold');
                    $cells->setBorder('none', 'none', 'thick', 'none');
                    $cells->setAlignment('center');
                });

                $sheet->row(1, array(
                    'Username', 'Nama Pengguna', 'Nomor Invoice', 'Berat Total', 'Ongkos Kirim',
                    'Diskon Kupon', 'Diskon Poin', 'Grand Total', 'Metode Pengiriman',
                    'Alamat Pengiriman', 'Dropship', 'Catatan', 'Tanggal Update'
                ));

                $orderheaders = Orderheader::where('created_at', '>=', $date_start)
                        ->where('created_at', '<=', $date_end)
                        ->whereBetween('status_id', [14,15])
                        ->get();
                $i = 2;

                foreach($orderheaders as $orderheader){
                    $user = $orderheader->user;
                    if($orderheader->customeraddress_id != 0){
                        $customeraddress = "'" . $orderheader->customeraddress->alamat . ' ' . $orderheader->customeraddress->kecamatan;
                    }
                    else{
                        $customeraddress = $user->usersetting->alamat . ' ' . $user->usersetting->kecamatan;
                    }

                    if($orderheader->dropship_id != 0){
                        $dropship = $orderheader->dropship->name . ' ' . $orderheader->dropship->alamat . ' ' . $orderheader->dropship->hp;
                    }
                    else{
                        $dropship = '';
                    }

                    $sheet->row($i++, array(
                        $user->username, $user->usersetting->first_name . ' ' . $user->usersetting->last_name,
                        $orderheader->invoicenumber, $orderheader->total_weight, $orderheader->shipment_cost,
                        $orderheader->discount_coupon, $orderheader->discount_point, $orderheader->grand_total,
                        $orderheader->shipment_method,
                        $customeraddress,
                        $dropship,
                        $orderheader->note, $orderheader->updated_at
                    ));

                }

            });

        })->export('xls');

    }


    public static function exportMasterOrderdetail($date_start, $date_end){

        $date_start = \Carbon\Carbon::instance(new \DateTime($date_start))->toDateTimeString();
        $date_end = \Carbon\Carbon::instance(new \DateTime($date_end))->toDateTimeString();

        Excel::create('daftar_detail_order', function($excel) use ($date_start, $date_end){

            $excel->sheet('Sheet1', function($sheet) use($date_start, $date_end){

                $sheet->setAutoSize(true);

                $sheet->cell('A1:F1', function($cells){
                    $cells->setBackground('#00B0F0');
                    $cells->setFontSize(16);
                    $cells->setFontWeight('bold');
                    $cells->setBorder('none', 'none', 'thick', 'none');
                    $cells->setAlignment('center');
                });

                $sheet->row(1, array(
                    'Nomor Invoice', 'Produk', 'Qty', 'Harga', 'Berat',
                    'Tanggal Update'
                ));

                $orderdetails = Orderdetail::where('created_at', '>=', $date_start)
                        ->where('created_at', '<=', $date_end)->get();
                $i = 2;

                foreach($orderdetails as $orderdetail){
                    $sheet->row($i++, array(
                        $orderdetail->orderheader->invoicenumber,
                        $orderdetail->productDelete($orderdetail->product_id)->product_name,
                        $orderdetail->qty,
                        $orderdetail->price, $orderdetail->weight, $orderdetail->updated_at
                    ));

                }

            });

        })->export('xls');

    }


    public static function exportMasterPointhistories($date_start, $date_end){

        $date_start = \Carbon\Carbon::instance(new \DateTime($date_start))->toDateTimeString();
        $date_end = \Carbon\Carbon::instance(new \DateTime($date_end))->toDateTimeString();

        Excel::create('daftar_riwayat_poin', function($excel) use ($date_start, $date_end){

            $excel->sheet('Sheet1', function($sheet) use($date_start, $date_end){

                $sheet->setAutoSize(true);

                $sheet->cell('A1:F1', function($cells){
                    $cells->setBackground('#00B0F0');
                    $cells->setFontSize(16);
                    $cells->setFontWeight('bold');
                    $cells->setBorder('none', 'none', 'thick', 'none');
                    $cells->setAlignment('center');
                });

                $sheet->row(1, array(
                    'Pengguna', 'Tambahan Poin', 'Pengurangan Poin', 'Nomor Invoice',
                    'Tanggal Berlaku', 'Tanggal Kadaluarsa'
                ));

                $pointhistories = Pointhistory::where('created_at', '>=', $date_start)
                        ->where('created_at', '<=', $date_end)->get();
                $i = 2;

                foreach($pointhistories as $pointhistory){
                    $sheet->row($i++, array(
                        $pointhistory->user->usersetting->first_name . ' ' . $pointhistory->user->usersetting->last_name,
                        $pointhistory->point_added, $pointhistory->point_used, $pointhistory->orderheader->invoicenumber,
                        $pointhistory->available_date, $pointhistory->expired_date
                    ));

                }

            });

        })->export('xls');

    }


    public static function exportMasterPrice($date_start, $date_end){

        $date_start = \Carbon\Carbon::instance(new \DateTime($date_start))->toDateString();
        $date_end = \Carbon\Carbon::instance(new \DateTime($date_end))->toDateString();

        Excel::create('daftar_harga', function($excel) use ($date_start, $date_end){

            $excel->sheet('Sheet1', function($sheet) use($date_start, $date_end){

                $sheet->setAutoSize(true);

                $sheet->cell('A1:G1', function($cells){
                    $cells->setBackground('#00B0F0');
                    $cells->setFontSize(16);
                    $cells->setFontWeight('bold');
                    $cells->setBorder('none', 'none', 'thick', 'none');
                    $cells->setAlignment('center');
                });

                $sheet->row(1, array(
                    'Nama Produk', 'Harga Regular', 'Harga Silver', 'Harga Gold', 'Harga Platinum', 'Harga Sale', 'Berlaku Dari'
                ));

                $prices = Price::where('created_at', '>=', $date_start)
                        ->where('created_at', '<=', $date_end)->get();
                $i = 2;

                foreach($prices as $price){
					$product_name = $price->product_id;
					if($price->product){
						$product_name = $price->product->product_name;
					}
                    $sheet->row($i++, array(
                        $product_name, $price->regular_price, $price->reseller_1, $price->reseller_2, $price->vvip, $price->sale_price, $price->valid_date
                    ));

                }

            });

        })->export('xls');

    }


    public static function exportMasterProduct(){

        Excel::create('daftar_produk', function($excel) {

            $excel->sheet('Sheet1', function($sheet) {

                $sheet->setAutoSize(true);

                $sheet->cell('A1:I1', function($cells){
                    $cells->setBackground('#00B0F0');
                    $cells->setFontSize(16);
                    $cells->setFontWeight('bold');
                    $cells->setBorder('none', 'none', 'thick', 'none');
                    $cells->setAlignment('center');
                });

                $sheet->row(1, array(
                    'Merk', 'Kategori', 'Subkategori', 'Barcode', 'Kode Produk',
                    'Nama Produk', 'Deskripsi', 'Berat', 'Stok Sistem',
                    //'Stok Booked', 'Stok Total'
                ));

                $products = Product::all();
                $i = 2;

                foreach($products as $product){
                    if($product->category_id != 0 && $product->subcategory_id != 0 && $product->brand_id != 0){
                        $brand = "";
                        $category = "";
                        $subcategory = "";
                        if($product->category != null){
                            $category = $product->category->category;
                        }
                        if($product->subcategory != null){
                            $subcategory = $product->subcategory->subcategory;
                        }
                        if($product->brand != null){
                            $brand = $product->brand->brand;
                        }

                        //$stock_booked = StockFunction::getStockBooked($product->id);
                        //$stock_total = $product->qty + $stock_booked;

                        $sheet->row($i++, array(
                            $brand, $category, $subcategory, $product->barcode, $product->product_code,
                            $product->product_name, $product->product_desc, $product->weight, $product->qty,
                           //$stock_booked, $stock_total
                        ));
                    }
                }

            });

        })->export('xls');

    }


    public static function exportMasterStockin($date_start, $date_end){

        $date_start = \Carbon\Carbon::instance(new \DateTime($date_start))->toDateTimeString();
        $date_end = \Carbon\Carbon::instance(new \DateTime($date_end))->toDateTimeString();

        Excel::create('daftar_stok_masuk', function($excel) use ($date_start, $date_end){

            $excel->sheet('Sheet1', function($sheet) use($date_start, $date_end){

                $sheet->setAutoSize(true);

                $sheet->cell('A1:D1', function($cells){
                    $cells->setBackground('#00B0F0');
                    $cells->setFontSize(16);
                    $cells->setFontWeight('bold');
                    $cells->setBorder('none', 'none', 'thick', 'none');
                    $cells->setAlignment('center');
                });

                $sheet->row(1, array(
                    'Nama Produk', 'Stok Masuk', 'Sisa Stok',
                    'Tanggal Masuk'
                ));

                $stockins = Stockin::where('created_at', '>=', $date_start)
                        ->where('created_at', '<=', $date_end)->get();
                $i = 2;

                foreach($stockins as $stockin){
                    $sheet->row($i++, array(
                        $stockin->productDelete($stockin->product_id)->product_name, $stockin->qty, $stockin->remaining_qty,
                        $stockin->created_at
                    ));

                }

            });

        })->export('xls');

    }

    public static function exportHistoryBooking($product_id){

        Excel::create('histori_booking', function($excel) use ($product_id){

            $excel->sheet('Sheet1', function($sheet) use($product_id){

                $sheet->setAutoSize(true);

                $sheet->cell('A3:E3', function($cells){
                    $cells->setBackground('#00B0F0');
                    $cells->setFontSize(16);
                    $cells->setFontWeight('bold');
                    $cells->setBorder('none', 'none', 'thick', 'none');
                    $cells->setAlignment('center');
                });

                $product = Product::find($product_id);

                $sheet->row(1, array(
                    'Produk', $product->product_name
                ));

                $sheet->row(3, array(
                    'Nomor Invoice', 'Qty', 'Username', 'Status Order', 'Tanggal',
                ));

                $details = Orderdetail::join('orderheaders', 'orderheaders.id', '=', 'orderdetails.orderheader_id')
                        ->where('orderdetails.product_id', '=', $product_id)
                        ->where('orderheaders.status_id', '<', 14)
                        ->select('orderdetails.*')
                        ->get();
                $i = 4;

                foreach($details as $detail){
                    $sheet->row($i++, array(
                        $detail->orderheader->invoicenumber, $detail->qty, $detail->orderheader->user->username, $detail->orderheader->status->status, date('d F Y, H:i:s', strtotime($detail->created_at))
                    ));

                }

            });

        })->export('xls');

    }


    public static function exportMasterUser(){

        Excel::create('daftar_pengguna', function($excel) {

            $excel->sheet('Sheet1', function($sheet) {

                $sheet->setAutoSize(true);

                $sheet->cell('A1:J1', function($cells){
                    $cells->setBackground('#00B0F0');
                    $cells->setFontSize(16);
                    $cells->setFontWeight('bold');
                    $cells->setBorder('none', 'none', 'thick', 'none');
                    $cells->setAlignment('center');
                });

                $sheet->row(1, array(
                    'Username', 'Nama', 'Email', 'Jenis Kelamin',
                    'Alamat', 'Kecamatan', 'Kota', 'Kodepos',
                    'HP', 'Status'
                ));

                $users = User::where([
                    ['is_admin', '0'],
                    ['is_marketing', '0'],
                    ['is_warehouse', '0'],
                    ['is_owner', '0'],
                    ])
                        ->where('id', '!=', 6830)
                        ->get();
                $i = 2;

                foreach($users as $user){
                    $usersetting = $user->usersetting;
                    if($usersetting != null){
                        $sheet->row($i++, array(
                                $user->username, $usersetting->first_name . ' ' . $usersetting->last_name, $usersetting->email, $usersetting->jenis_kelamin,
                                $usersetting->alamat, $usersetting->kecamatan, $usersetting->kota, $usersetting->kodepos,
                                $usersetting->hp, $usersetting->status->status
                        ));
                    }
                }

            });

        })->export('xls');

    }

    public static function exportPricelist(){

        Excel::create('price_list', function($excel) {

            $excel->sheet('Sheet1', function($sheet) {

                $sheet->setAutoSize(true);

                $sheet->cell('A1', function($cells){
                    $cells->setBackground('#00B0F0');
                    $cells->setFontSize(16);
                    $cells->setFontWeight('bold');
                    $cells->setBorder('none', 'none', 'thick', 'none');
                    $cells->setAlignment('center');
                });

                #Tampilkan tanggal dan jam generate
                $sheet->row(1, array(
                    'Tanggal dan Jam Generate'
                ));
                $sheet->row(2, array(
                    \Carbon\Carbon::now()->toDateTimeString()
                ));

                $sheet->cell('A4:F4', function($cells){
                    $cells->setBackground('#00B0F0');
                    $cells->setFontSize(16);
                    $cells->setFontWeight('bold');
                    $cells->setBorder('none', 'none', 'thick', 'none');
                    $cells->setAlignment('center');
                });

                $sheet->row(4, array(
                    'Nama Produk', 'Reguler', 'Silver', 'Gold', 'Platinum', 'Sale'
                ));

                $products = Product::join('prices', 'prices.id', '=', 'products.currentprice_id')
                        ->orderBy('products.product_name')
                        ->select('products.product_name', 'prices.regular_price', 'prices.reseller_1', 'prices.reseller_2',
                                'prices.vvip', 'prices.sale_price')
                        ->get();
                $i = 5;

                foreach($products as $product) {
                    $sheet->row($i++, array(
                        $product->product_name, $product->regular_price, $product->reseller_1, $product->reseller_2,
                        $product->vvip, $product->sale_price
                    ));
                }

            });

        })->export('xls');

    }

    public static function exportLaporanOrder($date_one) {
        $date_one = \Carbon\Carbon::instance(new \DateTime($date_one))->toDateTimeString();

        Excel::create('daftar_order_accepted', function($excel) use ($date_one) {

            $excel->sheet('Sheet1', function($sheet) use($date_one) {

                $sheet->setAutoSize(true);

                $sheet->cell('A3:I3', function($cells) {
                    $cells->setBackground('#000000');
                    $cells->setFontSize(14);
                    $cells->setFontColor('#FFFFFF');
                    $cells->setFontWeight('bold');
                    $cells->setBorder('none', 'none', 'thick', 'none');
                    $cells->setAlignment('center');
                });

                $sheet->mergeCells('A3:D3');
                $sheet->mergeCells('E3:I3');

                #ketentuannya:
                #Ambil semua order dari jam 17:00:01 kemarin sampai 17:00:00

                #parse hari ke carbon
                $hari = \Carbon\Carbon::parse($date_one);
                $hari_ini = $hari->hour(17)->second(0)->toDateTimeString();
                #khusus hari senin
                if($hari->dayOfWeek == 1){
                    $hari_kemarin = $hari->subDays(2)->hour(17)->second(1)->toDateTimeString();
                }else{
                    $hari_kemarin = $hari->subDay()->hour(17)->second(1)->toDateTimeString();
                }

                $sheet->row(1, array(
                    'Dari', $hari_kemarin, 'Sampai', $hari_ini
                ));

                $total_acc_jne = Orderheader::where('accept_time', '>=', $hari_kemarin)
                        ->where('accept_time', '<=', $hari_ini)
                        ->where('accept_by', '>', 0)
                        ->where('shipment_method', 'like', '%JNE%')
                        ->count();

                $total_scan_jne = Orderheader::where('accept_time', '>=', $hari_kemarin)
                        ->where('accept_time', '<=', $hari_ini)
                        ->where('process_by', '>', 0)
                        ->where('shipment_method', 'like', '%JNE%')
                        ->count();

                $total_acc_sc = Orderheader::where('accept_time', '>=', $hari_kemarin)
                        ->where('accept_time', '<=', $hari_ini)
                        ->where('accept_by', '>', 0)
                        ->where('shipment_method', 'like', '%SICEPAT%')
                        ->count();

                $total_scan_sc = Orderheader::where('accept_time', '>=', $hari_kemarin)
                        ->where('accept_time', '<=', $hari_ini)
                        ->where('process_by', '>', 0)
                        ->where('shipment_method', 'like', '%SICEPAT%')
                        ->count();

                $total_acc_jnt = Orderheader::where('accept_time', '>=', $hari_kemarin)
                        ->where('accept_time', '<=', $hari_ini)
                        ->where('accept_by', '>', 0)
                        ->where('shipment_method', 'like', '%J&T%')
                        ->count();

                $total_scan_jnt = Orderheader::where('accept_time', '>=', $hari_kemarin)
                        ->where('accept_time', '<=', $hari_ini)
                        ->where('process_by', '>', 0)
                        ->where('shipment_method', 'like', '%J&T%')
                        ->count();

                $total_acc = $total_acc_jne + $total_acc_jnt + $total_acc_sc;
                $total_scan = $total_scan_jne + $total_scan_jnt + $total_scan_sc;

                $sheet->row(3, array(
                    'Total Acc Pembayaran', '', '', '', 'Total Scan Barcode'
                ));

                $sheet->row(4, array(
                    'BY JNE', $total_acc_jne, '', '', 'BY JNE', $total_scan_jne
                ));

                $sheet->row(5, array(
                    'BY SICEPAT', $total_acc_sc, '', '', 'BY SICEPAT', $total_scan_sc
                ));

                $sheet->row(6, array(
                    'BY JNT', $total_acc_jnt, '', '', 'BY JNT', $total_scan_jnt
                ));

                $sheet->row(7, array(
                    'TOTAL ACC', $total_acc, '', '', 'TOTAL ACC', $total_scan
                ));

                $sheet->cell('A9:J9', function($cells) {
                    $cells->setBackground('#FFFF00');
                    $cells->setFontSize(14);
                    $cells->setFontColor('#000000');
                    $cells->setFontWeight('bold');
                    $cells->setBorder('none', 'none', 'thick', 'none');
                    $cells->setAlignment('center');
                });

                $sheet->row(9, array(
                    'NOMER ORDER', 'NAMA PENERIMA PAKET', 'JAM ACC', 'ACC BY',
                    'SCAN BARCODE', 'TANGGAL & JAM SCAN', 'SCANNED BY', 'NOMER RESI', 'STATUS',
                    'KIRIM BY'
                ));

                $orders = Orderheader::where('accept_time', '>=', $hari_kemarin)
                        ->where('accept_time', '<=', $hari_ini)
                        ->orderBy('invoicenumber')
                        ->get();

                $i = 10;
                foreach ($orders as $order) {
                    #penerima pesanan
                    if($order->customeraddress_id > 0){
                        if($order->customeraddress){
                            $penerima = $order->customeraddress->first_name . ' ' . $order->customeraddress->last_name;
                        }
                    }else{
                        $penerima = $order->user->usersetting->first_name . ' ' . $order->user->usersetting->last_name;
                    }

                    $barcode = '';
                    $accept_time = '';

                    $accept_by = '';
                    if($order->accept_by > 0){
                        $accept_by = $order->acceptby->name;
                        $accept_time = $order->accept_time;
                    }

                    $process_by = '';
                    $process_time = '';
                    if($order->process_by > 0){
                        $process_by = $order->processby->name;
                        $barcode = $order->barcode;
                        $process_time = $order->process_time;
                    }

                    $shipment_invoice = strlen($order->shipment_invoice) > 0 ? $order->shipment_invoice : '';

                    $sheet->row($i++, array(
                        $order->invoicenumber,
                        $penerima,
                        $accept_time,
                        $accept_by,
                        $barcode,
                        $process_time,
                        $process_by,
                        $shipment_invoice,
                        $order->status->status,
                        $order->shipment_method
                    ));
                }
            });
        })->export('xls');
    }

    public static function exportForImportShipmentInvoice(){

        Excel::create('shipmentinvoice', function($excel){

            $excel->sheet('Sheet1', function($sheet){

                $sheet->setAutoSize(true);

                $sheet->cell('A1:C1', function($cells){
                    $cells->setBackground('#00B0F0');
                    $cells->setFontSize(16);
                    $cells->setFontWeight('bold');
                    $cells->setBorder('none', 'none', 'thick', 'none');
                    $cells->setAlignment('center');
                });

                $sheet->row(1, array(
                    'nomor', 'nama_pelanggan', 'nomor_resi'
                ));

            });

        })->export('xls');

    }



    public static function exportForUpdateProductBulk($product_id){

        Excel::create('bulkproduct', function($excel) use($product_id){

            $excel->sheet('Sheet1', function($sheet) use($product_id){

                $sheet->setAutoSize(true);

                $sheet->cell('A1:H1', function($cells){
                    $cells->setBackground('#00B0F0');
                    $cells->setFontSize(16);
                    $cells->setFontWeight('bold');
                    $cells->setBorder('none', 'none', 'thick', 'none');
                    $cells->setAlignment('center');
                });

                $sheet->setColumnFormat([
                    'B' => '#0'
                ]);

                $sheet->row(1, array(
                    'id', 'barcode', 'kode_produk',
                    'nama_produk', 'deskripsi', 'berat',
                    'subcategory_id', 'inisial_merk'
                ));


                $products = Product::whereIn('id', $product_id)
                    ->orderBy('product_name')
                    ->get();
                $i = 2;

                foreach($products as $product){

                    $sheet->row($i++, array(
                        $product->id, $product->barcode, $product->product_code,
                        $product->product_name, $product->product_desc, $product->weight,
                        $product->subcategory_id, $product->brand->initial
                    ));

                }

            });

        })->export('xls');

    }


    public static function exportForTodayShipment() {

        Excel::create('todayshipment', function($excel) {

            $excel->sheet('Sheet1', function($sheet) {

                $sheet->setAutoSize(true);

                $sheet->cell('A1:E1', function($cells) {
                    $cells->setBackground('#00B0F0');
                    $cells->setFontSize(16);
                    $cells->setFontWeight('bold');
                    $cells->setBorder('none', 'none', 'thick', 'none');
                    $cells->setAlignment('center');
                });

                $sheet->row(1, array(
                    'nomor_invoice', 'nama', 'dikirim oleh', 'kecamatan', 'ongkos kirim'
                ));
                $i = 2;
                #bagian yang telat

                $orders = Orderheader::join('paymentconfirmations', 'paymentconfirmations.orderheader_id', '=', 'orderheaders.id')
                        ->where('orderheaders.status_id', '=', 14)
                        ->orderBy('paymentconfirmations.created_at', 'asc')
                        ->select('orderheaders.id', 'orderheaders.user_id', 'orderheaders.customeraddress_id', 'orderheaders.created_at', 'orderheaders.invoicenumber', 'orderheaders.total_weight', 'orderheaders.shipment_cost', 'orderheaders.shipment_method')
                        ->get();

                foreach ($orders as $order) {
                    #kalau ada customer address
                    if ($order->customeraddress) {
                        $name = $order->customeraddress->first_name . ' ' . $order->customeraddress->last_name;
                        $kecamatan = $order->customeraddress->kecamatan;
                    } else {
                        $name = $order->user->name;
                        $kecamatan = $order->user->usersetting->kecamatan;
                    }

                    $sheet->row($i++, array(
                        $order->invoicenumber, $name, $order->shipment_method, $kecamatan, 'Rp. ' . number_format($order->shipment_cost, 0, '.', ',')
                    ));
                }

            });
        })->export('xls');
    }

    public static function exportForStockTotal() {

        Excel::create('stocktotal', function($excel) {

            $excel->sheet('Sheet1', function($sheet) {

                $sheet->setAutoSize(true);

                $sheet->cell('A1:F1', function($cells) {
                    $cells->setBackground('#00B0F0');
                    $cells->setFontSize(16);
                    $cells->setFontWeight('bold');
                    $cells->setBorder('none', 'none', 'thick', 'none');
                    $cells->setAlignment('center');
                });

                $sheet->row(1, array(
                    'Nama Produk', 'Stok Sistem', 'Stok Cadangan', 'Stok Booked', 'Stok Total', 'Stok Terjual 30 Hari Terakhir'
                ));
                $i = 2;

                $products = Product::orderBy('product_name')
                        ->get();

                foreach ($products as $product) {
                    $stock_booked = $product->stock_booked;
                    $stock_total = $product->qty + $stock_booked + $product->reserved_qty;
                    $stock_sold = $product->stock_sold_30_days;

                    $sheet->row($i++, array(
                        $product->product_name, $product->qty, $product->reserved_qty, $stock_booked, $stock_total, $stock_sold
                    ));
                }

            });
        })->export('xls');
    }

    public static function exportShipmentCost($kecamatan_id) {

        Excel::create('shipmentcost', function($excel) use($kecamatan_id) {

            $excel->sheet('Sheet1', function($sheet) use($kecamatan_id){

                $sheet->setAutoSize(true);

                $sheet->cell('A1:E1', function($cells) {
                    $cells->setBackground('#00B0F0');
                    $cells->setFontSize(16);
                    $cells->setFontWeight('bold');
                    $cells->setBorder('none', 'none', 'thick', 'none');
                    $cells->setAlignment('center');
                });

                $sheet->cell('F1:H1', function($cells) {
                    $cells->setBackground('#FFFF00');
                    $cells->setFontSize(16);
                    $cells->setFontWeight('bold');
                    $cells->setBorder('none', 'none', 'thick', 'none');
                    $cells->setAlignment('center');
                });

                $sheet->row(1, array(
                    'id', 'kota', 'kecamatan', 'metode', 'kecamatan_id', 'metode_id', 'metode', 'ongkos_kirim',
                ));
                $i = 2;

                if($kecamatan_id > 0){
                    $costs = Shipcost::where('kecamatan_id', '=', $kecamatan_id)
                            ->get();
                }else{
                    $costs = Shipcost::get();
                }

                foreach ($costs as $cost) {
                    $shipmethod = '';
                    $kota = '';
                    $kecamatan = '';

                    $shipmethod = $cost->shipmethod->shipmethod_type . ' - ' . $cost->shipmethod->shipmethod_name;
                    if($cost->kecamatan){
                        if($cost->kecamatan->kota){
                            $kota = $cost->kecamatan->kota->kota;
                        }
                        $kecamatan = $cost->kecamatan->kecamatan;
                    }

                    $sheet->row($i++, array(
                        $cost->id, $kota, $kecamatan, $shipmethod, $cost->kecamatan_id, $cost->shipmethod_id, $cost->shipmethod->shipmethod_name . ' - ' . $cost->shipmethod->shipmethod_type, $cost->price,
                    ));
                }

                $sheet->cell('I2', function($cell) {
                    $cell->setBackground('#00B0F0');
                    $cell->setValue('JANGAN HAPUS ID, DAN HATI-HATI SAAT SORT. ID TIDAK BOLEH SALAH.');
                });

                $sheet->cell('I3', function($cell) {
                    $cell->setBackground('#00B0F0');
                    $cell->setValue('Silahkan hapus baris ongkos kirim yang tidak diubah untuk mempercepat proses sistem saat melakukan perubahan pada ongkos kirim.');
                });

                $sheet->cell('I4', function($cell) {
                    $cell->setBackground('#FFFF00');
                    $cell->setValue('Silahkan isi kolom metode_id, ongkos_kirim dengan angka.');
                });

                $sheet->cell('I5', function($cell) {
                    $cell->setBackground('#FFFF00');
                    $cell->setValue('Silahkan copy paste baris kota kecamatan untuk menambahkan ongkos kirim pada metode berbeda.');
                });

                $sheet->cell('I6', function($cell) {
                    $cell->setBackground('#FFFF00');
                    $cell->setValue('Silahkan isi ongkos kirim dengan 0 apabila ingin menghapus ongkos kirim tersebut.');
                });

                #tampilin
                $sheet->cell('J8', function($cell) {
                    $cell->setBackground('#FFFF00');
                    $cell->setValue('id');
                });
                $sheet->cell('K8', function($cell) {
                    $cell->setBackground('#FFFF00');
                    $cell->setValue('nama');
                });

                $methods = Shipmethod::where('is_active', '=', 1)
                        ->orderBy('shipmethod_name')
                        ->get();

                $i = 9;
                foreach ($methods as $method) {
                    $sheet->cell('I' . $i, function($cell) use($method) {
                        $cell->setValue($method->id);
                    });
                    $sheet->cell('J' . $i++, function($cell) use($method) {
                        $cell->setValue($method->shipmethod_name . ' - ' . $method->shipmethod_type);
                    });
                }

            });
        })->export('xls');
    }

    public static function exportShipmentKecamatan($skip, $range) {

        Excel::create('shipmentkecamatan', function($excel) use($skip, $range) {

            $excel->sheet('Sheet1', function($sheet) use($skip, $range){

                $sheet->setAutoSize(true);

                $sheet->cell('A1:B1', function($cells) {
                    $cells->setBackground('#00B0F0');
                    $cells->setFontSize(16);
                    $cells->setFontWeight('bold');
                    $cells->setBorder('none', 'none', 'thick', 'none');
                    $cells->setAlignment('center');
                });

                $column_field = ['id', 'kota', 'kota_id', 'kecamatan'];

                #tambah metode
                $methods = Shipmethod::where('is_active', '=', 1)->orderBy('shipmethod_name')->get();
                foreach($methods as $method){
                    $column_name = StringFunction::clean($method->shipmethod_name . '_' . $method->shipmethod_type);
                    array_push($column_field, $column_name);
                }

                $total_column = sizeof($column_field);
                $last_column = $total_column + 64;
                $sheet->cell('C1:'.chr($last_column).'1', function($cells) {
                    $cells->setBackground('#FFFF00');
                    $cells->setFontSize(16);
                    $cells->setFontWeight('bold');
                    $cells->setBorder('none', 'none', 'thick', 'none');
                    $cells->setAlignment('center');
                });

                $sheet->row(1, $column_field);
                $i = 2;

                if($skip >= 0 && $range > 0){
                    $kecamatans = Kecamatan::join('kotas', 'kotas.id', '=', 'kecamatans.kota_id')
                            ->orderBy('kotas.kota')
                            ->orderBy('kecamatans.kecamatan')
                            ->select('kecamatans.id', 'kotas.kota', 'kecamatans.kota_id', 'kecamatans.kecamatan');

                    if($range > 0){
                        $kecamatans = $kecamatans->skip($skip)->take($range);
                    }
                    $kecamatans = $kecamatans->get();
                }else{
                    $kecamatans = [];
                }

                foreach ($kecamatans as $kecamatan) {
                    $column_field = [$kecamatan->id, $kecamatan->kota, $kecamatan->kota_id, $kecamatan->kecamatan];

                    #tampilin ongkirnya masing-masing
                    foreach($methods as $method){
                        $shipcost = Shipcost::where('shipmethod_id', '=', $method->id)
                                    ->where('kecamatan_id', '=', $kecamatan->id)
                                    ->first();
                        $cost = 0;
                        if($shipcost){
                            $cost = $shipcost->price;
                        }
                        array_push($column_field, $cost);
                    }

                    $sheet->row($i++, $column_field);
                }

                $last_column++;
                $sheet->cell(chr($last_column).'2', function($cell) {
                    $cell->setBackground('#00B0F0');
                    $cell->setValue('JANGAN HAPUS ID, DAN HATI-HATI SAAT SORT. ID TIDAK BOLEH SALAH.');
                });

                $sheet->cell(chr($last_column).'3', function($cell) {
                    $cell->setBackground('#00B0F0');
                    $cell->setValue('Silahkan hapus baris kecamatan yang tidak diubah untuk mempercepat proses sistem saat melakukan perubahan pada kecamatan.');
                });

                $sheet->cell(chr($last_column).'4', function($cell) {
                    $cell->setBackground('#FFFF00');
                    $cell->setValue('Untuk menambah kecamatan, silahkan hanya isi kolom kota_id dengan angka dan kecamatan dengan text.');
                });

                $sheet->cell(chr($last_column).'4', function($cell) {
                    $cell->setBackground('#FFFF00');
                    $cell->setValue('Untuk mengubah kecamatan, silahkan ubah kolom kota_id dengan angka dan kecamatan dengan text tanpa menghapus ID.');
                });

                #tampilin
                $last_column++;
                $sheet->cell(chr($last_column).'1', function($cell) {
                    $cell->setBackground('#FFFF00');
                    $cell->setValue('id_kota');
                });
                $last_column++;
                $sheet->cell(chr($last_column).'1', function($cell) {
                    $cell->setBackground('#FFFF00');
                    $cell->setValue('kota');
                });

                $kotas = Kota::orderBy('kota')
                        ->get();

                $i = 2;
                foreach ($kotas as $kota) {
                    $sheet->cell(chr($last_column-1) . $i, function($cell) use($kota) {
                        $cell->setValue($kota->id);
                    });
                    $sheet->cell(chr($last_column) . $i++, function($cell) use($kota) {
                        $cell->setValue($kota->kota);
                    });
                }

            });
        })->export('xls');
    }

    public static function exportShipmentKota() {

        Excel::create('shipmentkota', function($excel) {

            $excel->sheet('Sheet1', function($sheet) {

                $sheet->setAutoSize(true);

                $sheet->cell('A1', function($cells) {
                    $cells->setBackground('#00B0F0');
                    $cells->setFontSize(16);
                    $cells->setFontWeight('bold');
                    $cells->setBorder('none', 'none', 'thick', 'none');
                    $cells->setAlignment('center');
                });

                $sheet->cell('B1', function($cells) {
                    $cells->setBackground('#FFFF00');
                    $cells->setFontSize(16);
                    $cells->setFontWeight('bold');
                    $cells->setBorder('none', 'none', 'thick', 'none');
                    $cells->setAlignment('center');
                });

                $sheet->row(1, array(
                    'id', 'kota'
                ));
                $i = 2;

                $kotas = Kota::orderBy('kota')
                        ->get();

                foreach ($kotas as $kota) {
                    $sheet->row($i++, array(
                        $kota->id, $kota->kota,
                    ));
                }

                $sheet->cell('C2', function($cell) {
                    $cell->setBackground('#00B0F0');
                    $cell->setValue('JANGAN HAPUS ID, DAN HATI-HATI SAAT SORT. ID TIDAK BOLEH SALAH.');
                });

                $sheet->cell('C3', function($cell) {
                    $cell->setBackground('#00B0F0');
                    $cell->setValue('Silahkan hapus baris kota yang tidak diubah untuk mempercepat proses sistem saat melakukan perubahan pada kota.');
                });

                $sheet->cell('C4', function($cell) {
                    $cell->setBackground('#FFFF00');
                    $cell->setValue('Untuk menambah kota, silahkan kosongkan kolom id.');
                });

                $sheet->cell('C4', function($cell) {
                    $cell->setBackground('#FFFF00');
                    $cell->setValue('Untuk mengubah kecamatan, jangan hapus kolom id.');
                });

            });
        })->export('xls');
    }

    public static function exportForStockBalance($product_id, $date_start, $date_end) {

        $date_start = \Carbon\Carbon::instance(new \DateTime($date_start))->toDateTimeString();
        $date_end = \Carbon\Carbon::instance(new \DateTime($date_end))->addDay()->toDateTimeString();

        Excel::create('stockbalance', function($excel) use ($product_id, $date_start, $date_end) {

            $excel->sheet('Sheet1', function($sheet) use ($product_id, $date_start, $date_end) {

                $sheet->setAutoSize(true);

                $sheet->cell('A3:H3', function($cells) {
                    $cells->setBackground('#00B0F0');
                    $cells->setFontSize(16);
                    $cells->setFontWeight('bold');
                    $cells->setBorder('none', 'none', 'thick', 'none');
                    $cells->setAlignment('center');
                });

                $product = Product::find($product_id);

                $sheet->row(1, array(
                    'Produk', $product->product_name
                ));
                $sheet->row(3, array(
                    'Current', 'Stok In', 'Stok Booked', 'Stok Out', 'Stok Total', 'Stok System', 'Notes', 'Tanggal',
                ));

                $balances = Stockbalance::where('product_id', '=', $product_id)
                        ->where('created_at', '>=', $date_start)
                        ->where('created_at', '<=', $date_end)
                        ->get();
                $i = 4;
                foreach ($balances as $balance) {
                    $sheet->row($i++, array(
                        $balance->current_stock, $balance->stock_in, $balance->stock_booked, $balance->stock_out, $balance->stock_total, $balance->stock_system, $balance->notes, date('d M Y, H:i:s', strtotime($balance->created_at)),
                    ));
                }
            });
        })->export('xls');
    }

}
