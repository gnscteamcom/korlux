<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Product;
use App\Productclass;
use App\Discountqty;
use Illuminate\Http\Request;
use Excel;

class WholesaleController extends Controller {

    public function viewWholeSalePrice() {
        $productclasses = Productclass::get();
        $discountqties[] = [];
        $products_id[] = [];
        $i = 0;
        foreach ($productclasses as $productclass) {
            if (sizeof(array_filter($products_id)) > 0) {
                $skip_product = false;
                foreach ($products_id as $product_id) {
                    if ($productclass->product_id == $product_id['id']) {
                        $skip_product = true;
                        break;
                    }
                }
                if ($skip_product) {
                    continue;
                }
            }

            $discountqties_id = Productclass::where('product_id', '=', $productclass->product_id)
                    ->distinct()
                    ->select('userstatus_id', 'discountqty_id')
                    ->get();

            $min_qty = '';
            $product_ids = '';
            $product_code = '';
            $product_name = '';
            unset($price);
            $price[] = [];
            $created_at = '';
            $status = '';
            $curr_status = '';
            $is_done = false;
            $j = 0;
            foreach ($discountqties_id as $discountqty_id) {
                $productclasses_products = Productclass::where('discountqty_id', '=', $discountqty_id->discountqty_id)
                        ->distinct('product_id')
                        ->select('product_id')
                        ->get();

                if (!$is_done) {
                    $k = 0;
                    foreach ($productclasses_products as $productclasses_product) {
                        $product = Product::where('id', '=', $productclasses_product->product_id)
                                ->withTrashed()->first();
                        $product_ids .= $product->id . ',';
                        $product_code .= $product->product_code . '<br>';
                        $product_name .= $product->product_name . '<br>';

                        $products_id[$k] = [
                            'id' => $product->id
                        ];
                        $k++;
                    }
                    $is_done = true;
                }

                if (strlen($curr_status) == 0) {
                    $curr_status = $discountqty_id->userstatus->status;
                    $status .= $curr_status . '<br>';
                } else {
                    if (strcmp($curr_status, $discountqty_id->userstatus->status) == 0) {
                        $status .= '<br>';
                    } else {
                        $curr_status = $discountqty_id->userstatus->status;
                        $status .= $curr_status . '<br>';
                    }
                }
                $discountqty = Discountqty::find($discountqty_id->discountqty_id);
                $min_qty .= '<b>' . $discountqty->min_qty . '</b><br>';
                $created_at = date('d F Y', strtotime($discountqty->created_at));

                $price[$j] = [
                    'price' => 'Rp. ' . number_format($discountqty->price, 0, ',', '.'),
                    'id' => $discountqty_id['discountqty_id']
                ];
                $j++;
            }

            $discountqties[$i] = [
                'product_ids' => $product_ids,
                'product_code' => $product_code,
                'product_name' => $product_name,
                'status' => $status,
                'min_qty' => $min_qty,
                'price' => array_filter($price),
                'created_at' => $created_at
            ];
            $i++;
            $is_done = false;
        }
        $discountqties = array_filter($discountqties);

        return view('pages.admin-side.modules.wholesale.viewwholesaleprice')->with(array(
                    'discountqties' => $discountqties
        ));
    }

    public function downloadWholesaleFormat() {
        Custom\ExportFunction::exportForImportWholesalePrice();
    }

    public function viewAddWholeSalePrice() {

        return view('pages.admin-side.modules.wholesale.addwholesaleprice');
    }

    public function viewEditWholeSalePrice($id) {

        $discountqty = Discountqty::find($id);

        $product = '';
        foreach ($discountqty->productclasses as $productclass) {
            $product .= $productclass->productDelete($productclass->product_id)->product_code . "\r\n";
        }

        return view('pages.admin-side.modules.wholesale.editwholesaleprice')->with(array(
                    'discountqty' => $discountqty,
                    'product' => $product
        ));
    }

    public function insertWholeSalePrice(Request $request) {

        $this->validate($request, [
            'qty_minimum_1' => 'required',
            'harga_satuan_1' => 'required',
            'qty_minimum_2' => 'min:1',
            'harga_satuan_2' => 'required_with:qty_minimum_2|min:1',
            'qty_minimum_3' => 'min:1',
            'harga_satuan_3' => 'required_with:qty_minimum_3|min:1',
            'qty_minimum_4' => 'min:1',
            'harga_satuan_4' => 'required_with:qty_minimum_4|min:1',
            'qty_minimum_5' => 'min:1',
            'harga_satuan_5' => 'required_with:qty_minimum_5|min:1',
            'qty_minimum_6' => 'min:1',
            'harga_satuan_6' => 'required_with:qty_minimum_6|min:1',
            'kode_produk' => 'required'
        ]);

        $err_msg = "";
        $temp_kode_produk = preg_split('/\r\n|[\r\n]/', $request['kode_produk']);
        $temp_kode_produk = array_unique($temp_kode_produk);

        if (sizeof($temp_kode_produk) > 0) {

            //validasi harga tidak boleh di bawah 50%
            //validasi juga kalau ada kode produk yang salah input
            foreach ($temp_kode_produk as $kode_produk) {
                $product = Product::whereProduct_code($kode_produk)->first();
                if ($product != null) {
                    $err = [
                        'err' => 'Produk ' . $product->product_name . ' memiliki harga grosir lebih murah dari 50% harga normal'
                    ];
                    if ($product->currentprice->regular_price / 2 >= $request['harga_satuan_1']) {
                        return back()->with($err);
                    }
                    if ($request['harga_satuan_2'] > 0) {
                        if ($product->currentprice->regular_price / 2 >= $request['harga_satuan_2']) {
                            return back()->with($err);
                        }
                    }
                    if ($request['harga_satuan_3'] > 0) {
                        if ($product->currentprice->regular_price / 2 >= $request['harga_satuan_3']) {
                            return back()->with($err);
                        }
                    }
                    if ($request['harga_satuan_4'] > 0) {
                        if ($product->currentprice->reseller_2 / 2 >= $request['harga_satuan_4']) {
                            return back()->with($err);
                        }
                    }
                    if ($request['harga_satuan_5'] > 0) {
                        if ($product->currentprice->reseller_2 / 2 >= $request['harga_satuan_5']) {
                            return back()->with($err);
                        }
                    }
                    if ($request['harga_satuan_6'] > 0) {
                        if ($product->currentprice->reseller_2 / 2 >= $request['harga_satuan_6']) {
                            return back()->with($err);
                        }
                    }
                } else {
                    $err_msg .= "Kode Produk " . $kode_produk . " tidak ada..";
                    return back()->with([
                                'err' => $err_msg
                    ]);
                }
            }

            //insert ke discountqty..
            $discountqty1 = new Discountqty;
            $discountqty1->min_qty = $request['qty_minimum_1'];
            $discountqty1->price = $request['harga_satuan_1'];
            $discountqty1->save();

            if ($request['qty_minimum_2'] > 0 && $request['harga_satuan_2'] > 0) {
                $discountqty2 = new Discountqty;
                $discountqty2->min_qty = $request['qty_minimum_2'];
                $discountqty2->price = $request['harga_satuan_2'];
                $discountqty2->save();
            }

            if ($request['qty_minimum_3'] > 0 && $request['harga_satuan_3'] > 0) {
                $discountqty3 = new Discountqty;
                $discountqty3->min_qty = $request['qty_minimum_3'];
                $discountqty3->price = $request['harga_satuan_3'];
                $discountqty3->save();
            }

            if ($request['qty_minimum_4'] > 0 && $request['harga_satuan_4'] > 0) {
                $discountqty4 = new Discountqty;
                $discountqty4->min_qty = $request['qty_minimum_4'];
                $discountqty4->price = $request['harga_satuan_4'];
                $discountqty4->save();
            }

            if ($request['qty_minimum_5'] > 0 && $request['harga_satuan_5'] > 0) {
                $discountqty5 = new Discountqty;
                $discountqty5->min_qty = $request['qty_minimum_5'];
                $discountqty5->price = $request['harga_satuan_5'];
                $discountqty5->save();
            }

            if ($request['qty_minimum_6'] > 0 && $request['harga_satuan_6'] > 0) {
                $discountqty6 = new Discountqty;
                $discountqty6->min_qty = $request['qty_minimum_6'];
                $discountqty6->price = $request['harga_satuan_6'];
                $discountqty6->save();
            }


            foreach ($temp_kode_produk as $kode_produk) {
                if (strlen($kode_produk) > 0) {

                    $product = Product::whereProduct_code($kode_produk)->first();
                    //delete yang lama
                    $productclasses = Productclass::whereProduct_id($product->id)->select('discountqty_id')->get();
                    foreach ($productclasses as $productclass) {
                        $discountqty_delete = $productclass->discountqty;
                        if ($productclass != null) {
                            $productclasses2 = Productclass::whereDiscountqty_id($productclass->discountqty_id)->get();

                            foreach ($productclasses2 as $productclass2) {
                                $productclass2->delete();
                            }
                        }
                        if ($discountqty_delete != null) {
                            $discountqty_delete->delete();
                        }
                    }

                    $productclass = new Productclass;
                    $productclass->product_id = $product->id;
                    $productclass->discountqty_id = $discountqty1->id;
                    $productclass->userstatus_id = 1;
                    $productclass->save();

                    if (isset($discountqty2)) {
                        $productclass = new Productclass;
                        $productclass->product_id = $product->id;
                        $productclass->discountqty_id = $discountqty2->id;
                        $productclass->userstatus_id = 1;
                        $productclass->save();
                    }

                    if (isset($discountqty3)) {
                        $productclass = new Productclass;
                        $productclass->product_id = $product->id;
                        $productclass->discountqty_id = $discountqty3->id;
                        $productclass->userstatus_id = 1;
                        $productclass->save();
                    }


                    //insert harga ke seluruh reseller
                    for ($i = 2; $i <= 4; $i++) {
                        if (isset($discountqty4)) {
                            $productclass = new Productclass;
                            $productclass->product_id = $product->id;
                            $productclass->discountqty_id = $discountqty4->id;
                            $productclass->userstatus_id = $i;
                            $productclass->save();
                        }

                        if (isset($discountqty5)) {
                            $productclass = new Productclass;
                            $productclass->product_id = $product->id;
                            $productclass->discountqty_id = $discountqty5->id;
                            $productclass->userstatus_id = $i;
                            $productclass->save();
                        }

                        if (isset($discountqty6)) {
                            $productclass = new Productclass;
                            $productclass->product_id = $product->id;
                            $productclass->discountqty_id = $discountqty6->id;
                            $productclass->userstatus_id = $i;
                            $productclass->save();
                        }
                    }
                }
            }
        } else {
            $err_msg .= 'Silahkan input kode barcode';
        }

        if (strlen($err_msg) > 0) {
            return back()->with(array(
                        'err' => $err_msg
            ));
        } else {
            return redirect('viewwholesaleprice')->with(array(
                        'msg' => 'Anda telah berhasil memasukkan harga grosir baru'
            ));
        }
    }

    public function importWholesale(Request $request) {
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

            $i = 0;
            //Baca smua sheets
            $reader->each(function($row) use ($i) {
                $i++;
                echo '<b>Baris ' . $i . '.</b> ';

                if (ctype_space($row->qty_minimum_regular_1) || $row->qty_minimum_regular_1 == null || $row->qty_minimum_regular_1 == '') {
                    echo '<b style="color:red">qty minimum grosir regular level 1 tidak boleh kosong</b><br />';
                } elseif (!is_numeric($row->qty_minimum_regular_1)) {
                    echo '<b style="color:red">qty minimum grosir regular level 1 harus angka.</b><br />';
                } else if (ctype_space($row->harga_satuan_regular_1) || $row->harga_satuan_regular_1 == null || $row->harga_satuan_regular_1 == '') {
                    echo '<b style="color:red">harga satuan grosir regular level 1 tidak boleh kosong</b><br />';
                } elseif (!is_numeric($row->harga_satuan_regular_1)) {
                    echo '<b style="color:red">harga satuan grosir regular level 1 harus angka.</b><br />';
                } else if (strlen($row->qty_minimum_regular_2) > 0) {
                    if (!is_numeric($row->qty_minimum_regular_2)) {
                        echo '<b style="color:red">qty minimum grosir regular level 2 harus angka.</b><br />';
                    } else if (ctype_space($row->harga_satuan_regular_2) || $row->harga_satuan_regular_2 == null || $row->harga_satuan_regular_2 == '') {
                        echo '<b style="color:red">harga satuan grosir regular level 2 harus diisi apabila harga minimum regular level 2 diisi.</b><br />';
                    } else if (!is_numeric($row->harga_satuan_regular_2)) {
                        echo '<b style="color:red">harga satuan grosir regular level 2 harus angka.</b><br />';
                    }
                }

                if (strlen($row->qty_minimum_regular_3) > 0) {
                    if (!is_numeric($row->qty_minimum_regular_3)) {
                        echo '<b style="color:red">qty minimum grosir regular level 3 harus angka.</b><br />';
                    } else if (ctype_space($row->harga_satuan_regular_3) || $row->harga_satuan_regular_3 == null || $row->harga_satuan_regular_3 == '') {
                        echo '<b style="color:red">harga satuan grosir regular level 3 harus diisi apabila harga minimum regular level 3 diisi.</b><br />';
                    } else if (!is_numeric($row->harga_satuan_regular_2)) {
                        echo '<b style="color:red">harga satuan grosir regular level 3 harus angka.</b><br />';
                    }
                }

                if (strlen($row->qty_minimum_reseller_1) > 0) {
                    if (!is_numeric($row->qty_minimum_reseller_1)) {
                        echo '<b style="color:red">qty minimum grosir reseller level 1 harus angka.</b><br />';
                    } else if (ctype_space($row->harga_satuan_reseller_1) || $row->harga_satuan_reseller_1 == null || $row->harga_satuan_reseller_1 == '') {
                        echo '<b style="color:red">harga satuan grosir reseller level 1 harus diisi apabila qty minimum reseller level 1 diisi.</b><br />';
                    } else if (!is_numeric($row->harga_satuan_reseller_1)) {
                        echo '<b style="color:red">harga satuan grosir reseller level 1 harus angka.</b><br />';
                    }
                }

                if (strlen($row->qty_minimum_reseller_2) > 0) {
                    if (!is_numeric($row->qty_minimum_reseller_2)) {
                        echo '<b style="color:red">qty minimum grosir reseller level 2 harus angka.</b><br />';
                    } else if (ctype_space($row->harga_satuan_reseller_2) || $row->harga_satuan_reseller_2 == null || $row->harga_satuan_reseller_2 == '') {
                        echo '<b style="color:red">harga satuan grosir reseller level 2 harus diisi apabila qty minimum reseller level 2 diisi.</b><br />';
                    } else if (!is_numeric($row->harga_satuan_reseller_2)) {
                        echo '<b style="color:red">harga satuan grosir reseller level 2 harus angka.</b><br />';
                    }
                }

                if (strlen($row->qty_minimum_reseller_3) > 0) {
                    if (!is_numeric($row->qty_minimum_reseller_3)) {
                        echo '<b style="color:red">qty minimum grosir reseller level 3 harus angka.</b><br />';
                    } else if (ctype_space($row->harga_satuan_reseller_3) || $row->harga_satuan_reseller_3 == null || $row->harga_satuan_reseller_3 == '') {
                        echo '<b style="color:red">harga satuan grosir reseller level 3 harus diisi apabila qty minimum reseller level 3 diisi.</b><br />';
                    } else if (!is_numeric($row->harga_satuan_reseller_3)) {
                        echo '<b style="color:red">harga satuan grosir reseller level 3 harus angka.</b><br />';
                    }
                }

                if (ctype_space($row->kode_produk) || $row->kode_produk == null || $row->kode_produk == '') {
                    echo '<b style="color:red">Kode Produk Harus Diisi</b><br />';
                } else {
                    $kode_produk = explode(",", $row->kode_produk);
                    $is_valid = 1;
                    //cek dulu apakah kode produk ada yg tidak terdaftar,
                    //kalau ada yang tidak terdaftar, tidak insert harga grosir baru.
                    foreach ($kode_produk as $kode) {
                        $kode = trim($kode);
                        $product = Product::where('product_code', 'like', $kode)->first();
                        if ($product != null) {
                            if ($product->currentprice == null) {
                                $err = '<b style="color:red">Kode Produk: ' . $kode . ' belum memiliki harga yang berlaku saat ini.</b><br />';
                                $is_valid = 0;
                                echo $err;
                            } else {
                                $err = '<b style="color:red">Kode Produk: ' . $kode . ' memiliki harga grosir lebih murah dari 50% harga normal</b><br />';
                                if ($product->currentprice->regular_price / 2 >= $row->harga_satuan_regular_1) {
                                    $is_valid = 0;
                                    echo $err;
                                }
                                if ($row->harga_satuan_regular_2 > 0) {
                                    if ($product->currentprice->regular_price / 2 >= $row->harga_satuan_regular_2) {
                                        $is_valid = 0;
                                        echo $err;
                                    }
                                }
                                if ($row->harga_satuan_regular_3 > 0) {
                                    if ($product->currentprice->regular_price / 2 >= $row->harga_satuan_regular_3) {
                                        $is_valid = 0;
                                        $is_valid = 0;
                                        echo $err;
                                    }
                                }
                                if ($row->harga_satuan_reseller_1 > 0) {
                                    if ($product->currentprice->reseller_2 / 2 >= $row->harga_satuan_reseller_1) {
                                        $is_valid = 0;
                                        echo $err;
                                    }
                                }
                                if ($row->harga_satuan_reseller_2 > 0) {
                                    if ($product->currentprice->reseller_2 / 2 >= $row->harga_satuan_reseller_2) {
                                        $is_valid = 0;
                                        echo $err;
                                    }
                                }
                                if ($row->harga_satuan_reseller_3 > 0) {
                                    if ($product->currentprice->reseller_2 / 2 >= $row->harga_satuan_reseller_3) {
                                        $is_valid = 0;
                                        echo $err;
                                    }
                                }
                            }
                        } else {
                            $is_valid = 0;
                            echo '<b style="color:red">Kode Produk: ' . $kode . ' Tidak Ditemukan. Batal membuat harga grosir baru..</b><br />';
                        }
                    }

                    if ($is_valid) {

                        //insert ke discountqty..
                        $discountqty1 = new Discountqty;
                        $discountqty1->min_qty = $row->qty_minimum_regular_1;
                        $discountqty1->price = $row->harga_satuan_regular_1;
                        $discountqty1->save();

                        if ($row->qty_minimum_regular_2 > 0 && $row->harga_satuan_regular_2 > 0) {
                            $discountqty2 = new Discountqty;
                            $discountqty2->min_qty = $row->qty_minimum_regular_2;
                            $discountqty2->price = $row->harga_satuan_regular_2;
                            $discountqty2->save();
                        }

                        if ($row->qty_minimum_regular_3 > 0 && $row->harga_satuan_regular_3 > 0) {
                            $discountqty3 = new Discountqty;
                            $discountqty3->min_qty = $row->qty_minimum_regular_3;
                            $discountqty3->price = $row->harga_satuan_regular_3;
                            $discountqty3->save();
                        }

                        if ($row->qty_minimum_reseller_1 > 0 && $row->harga_satuan_reseller_1 > 0) {
                            $discountqty4 = new Discountqty;
                            $discountqty4->min_qty = $row->qty_minimum_reseller_1;
                            $discountqty4->price = $row->harga_satuan_reseller_1;
                            $discountqty4->save();
                        }

                        if ($row->qty_minimum_reseller_2 > 0 && $row->harga_satuan_reseller_2 > 0) {
                            $discountqty5 = new Discountqty;
                            $discountqty5->min_qty = $row->qty_minimum_reseller_2;
                            $discountqty5->price = $row->harga_satuan_reseller_2;
                            $discountqty5->save();
                        }

                        if ($row->qty_minimum_reseller_3 > 0 && $row->harga_satuan_reseller_3 > 0) {
                            $discountqty6 = new Discountqty;
                            $discountqty6->min_qty = $row->qty_minimum_reseller_3;
                            $discountqty6->price = $row->harga_satuan_reseller_3;
                            $discountqty6->save();
                        }

                        foreach ($kode_produk as $kode) {
                            $kode = trim($kode);
                            $product = Product::where('product_code', 'like', $kode)->first();
                            //delete yang lama
                            $productclasses = Productclass::whereProduct_id($product->id)->select('discountqty_id')->get();
                            foreach ($productclasses as $productclass) {
                                $discountqty_delete = $productclass->discountqty;
                                if ($productclass != null) {
                                    $productclasses2 = Productclass::whereDiscountqty_id($productclass->discountqty_id)->get();

                                    foreach ($productclasses2 as $productclass2) {
                                        $productclass2->delete();
                                    }
                                }
                                if ($discountqty_delete != null) {
                                    $discountqty_delete->delete();
                                }
                            }

                            $productclass = new Productclass;
                            $productclass->product_id = $product->id;
                            $productclass->discountqty_id = $discountqty1->id;
                            $productclass->userstatus_id = 1;
                            $productclass->save();

                            if (isset($discountqty2)) {
                                $productclass = new Productclass;
                                $productclass->product_id = $product->id;
                                $productclass->discountqty_id = $discountqty2->id;
                                $productclass->userstatus_id = 1;
                                $productclass->save();
                            }

                            if (isset($discountqty3)) {
                                $productclass = new Productclass;
                                $productclass->product_id = $product->id;
                                $productclass->discountqty_id = $discountqty3->id;
                                $productclass->userstatus_id = 1;
                                $productclass->save();
                            }


                            //insert harga ke semua reseller
                            for ($i = 2; $i <= 4; $i++) {
                                if (isset($discountqty4)) {
                                    $productclass = new Productclass;
                                    $productclass->product_id = $product->id;
                                    $productclass->discountqty_id = $discountqty4->id;
                                    $productclass->userstatus_id = $i;
                                    $productclass->save();
                                }

                                if (isset($discountqty5)) {
                                    $productclass = new Productclass;
                                    $productclass->product_id = $product->id;
                                    $productclass->discountqty_id = $discountqty5->id;
                                    $productclass->userstatus_id = $i;
                                    $productclass->save();
                                }

                                if (isset($discountqty6)) {
                                    $productclass = new Productclass;
                                    $productclass->product_id = $product->id;
                                    $productclass->discountqty_id = $discountqty6->id;
                                    $productclass->userstatus_id = $i;
                                    $productclass->save();
                                }
                            }
                            echo $kode . ' berhasil ditambahkan ke dalam harga grosir.<br>';
                        }
                    }
                }
            });
        });

        echo '<br /><a href="' . url()->previous() . '">Kembali</a>';
    }

    public function deleteWholeSalePrice($id) {

        $discountqty = Discountqty::find($id);
        foreach ($discountqty->productclasses as $productclass) {
            $productclass->delete();
        }
        $discountqty->delete();

        return redirect('viewwholesaleprice')->with(array(
                    'msg' => 'Wholesaleprice has been removed..'
        ));
    }

    public function updateWholeSalePrice(Request $request) {

        $this->validate($request, [
            'discountqty_id' => 'required',
            'min_qty' => 'required|min:1',
            'harga_satuan' => 'required|min:1'
        ]);

        $discountqty = Discountqty::find($request['discountqty_id']);
        $discountqty->min_qty = $request['min_qty'];
        $discountqty->price = $request['harga_satuan'];
        $discountqty->save();

        return redirect('viewwholesaleprice')->with(array(
                    'msg' => 'Anda berhasil mengubah harga grosir'
        ));
    }

    public function searchWholesale(Request $request) {
        $productclasses = Productclass::join('products', 'products.id', '=', 'productclasses.product_id')
                ->where('product_name', 'like', '%' . $request['search'] . '%')
                ->select('productclasses.*')
                ->get();
        $discountqties[] = [];
        $products_id[] = [];
        $i = 0;
        foreach ($productclasses as $productclass) {
            if (sizeof(array_filter($products_id)) > 0) {
                $skip_product = false;
                foreach ($products_id as $product_id) {
                    if ($productclass->product_id == $product_id['id']) {
                        $skip_product = true;
                        break;
                    }
                }
                if ($skip_product) {
                    continue;
                }
            }

            $discountqties_id = Productclass::where('product_id', '=', $productclass->product_id)
                    ->distinct()
                    ->where('userstatus_id', '<>', 3)
                    ->select('userstatus_id', 'discountqty_id')
                    ->get();

            $min_qty = '';
            $product_ids = '';
            $product_code = '';
            $product_name = '';
            $price[] = [];
            $created_at = '';
            $status = '';
            $curr_status = '';
            $is_done = false;
            $j = 0;
            foreach ($discountqties_id as $discountqty_id) {
                $productclasses_products = Productclass::where('discountqty_id', '=', $discountqty_id->discountqty_id)
                        ->distinct('product_id')
                        ->select('product_id')
                        ->get();

                if (!$is_done) {
                    $k = 0;
                    foreach ($productclasses_products as $productclasses_product) {
                        $product = Product::where('id', '=', $productclasses_product->product_id)
                                ->withTrashed()->first();
                        $product_ids .= $product->id . ',';
                        $product_code .= $product->product_code . '<br>';
                        $product_name .= $product->product_name . '<br>';

                        $products_id[$k] = [
                            'id' => $product->id
                        ];
                        $k++;
                    }
                    $is_done = true;
                }

                if (strlen($curr_status) == 0) {
                    $curr_status = $discountqty_id->userstatus->status;
                    $status .= $curr_status . '<br>';
                } else {
                    if (strcmp($curr_status, $discountqty_id->userstatus->status) == 0) {
                        $status .= '<br>';
                    } else {
                        $curr_status = $discountqty_id->userstatus->status;
                        $status .= $curr_status . '<br>';
                    }
                }
                $discountqty = Discountqty::find($discountqty_id->discountqty_id);
                $min_qty .= '<b>' . $discountqty->min_qty . '</b><br>';
                $created_at = date('d F Y', strtotime($discountqty->created_at));

                $price[$j] = [
                    'price' => 'Rp. ' . number_format($discountqty->price, 2, ',', '.'),
                    'id' => $discountqty_id['discountqty_id']
                ];
                $j++;
            }

            $discountqties[$i] = [
                'product_ids' => $product_ids,
                'product_code' => $product_code,
                'product_name' => $product_name,
                'status' => $status,
                'min_qty' => $min_qty,
                'price' => array_filter($price),
                'created_at' => $created_at
            ];
            
            $i++;
            $is_done = false;
        }

        $discountqties = array_filter($discountqties);

        return view('pages.admin-side.modules.wholesale.viewwholesaleprice')->with(array(
                    'discountqties' => $discountqties
        ));
    }
    
    public function deleteAllWholesale(Request $request){
        $product_ids = substr($request->product_ids, 0, strlen($request->product_ids) - 1);
        $product_ids = explode(",", $product_ids);
        
        $product_classes = Productclass::whereIn('product_id', $product_ids)
                ->get();
        $product_names = '';
        $curr_productname = '';
        foreach($product_classes as $productclass){
            if($curr_productname == '' || strcmp($curr_productname, $productclass->product->product_name) != 0){
                $curr_productname = $productclass->product->product_name;
                $product_names .= $curr_productname . '<br>';
            }
            $productclass->delete();
        }

        return back()->with([
            'err' => 'Harga Grosir untuk produk berikut sudah berhasil dihapus : <br>' . $product_names
        ]);
    }

}
