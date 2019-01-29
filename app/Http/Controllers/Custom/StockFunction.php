<?php

namespace App\Http\Controllers\Custom;

use App\Orderheader;
use App\Product;
use App\Productset;
use App\Stockin;
use App\Reservedstockhistory;
use Cart;
use DB;

class StockFunction {
    
    public static function checkStockForRevise($orderheader, $list_of_qty) {
        $validation = false;

        $i = 0;
        foreach ($orderheader->orderdetails as $orderdetail) {
            if ($orderdetail->product->qty + $orderdetail->qty < $list_of_qty[$i]) {
                $validation = true;
            }
            $i++;
        }

        return $validation;
    }

    public static function returnManualSalesStock($order_header, $reject_reason = '') {
        #kalau ada shopee sales
        if($order_header->shopeesales){
            $sales = $order_header->shopeesales;
            $sales->delete();
        }
        
        $order_header->status_id = 16;
        if(strlen($reject_reason) > 0){
            $order_header->cancel_reason = $reject_reason;
        }
        $order_header->save();

        //balikin stok kalau order di cancelled...
        foreach ($order_header->orderdetails as $order_detail) {
            $product = Product::find($order_detail->product_id);
            if ($product->is_set) {
                foreach ($product->sets($product->id) as $set) {
                    StockFunction::returnStock($set->product->id, $order_detail->qty, $order_header);
                }
            }
            StockFunction::returnStock($order_detail->product_id, $order_detail->qty, $order_header);
        }
    }

    //fungsi untuk memotong stok
    public static function decreaseStock($order, $revise_status) {

        //save status dulu
        $order->status_id = $revise_status;
        $order->save();
        
        #cek apakah ada orderan shopee
        if($order->shopeesales){
            $sales = $order->shopeesales;
            $sales->restore();
        }

        //loop seluruh isi order detail dan potong stok
        foreach ($order->orderdetails as $orderdetail) {
            $stockin_qty = $orderdetail->qty;

            $product = $orderdetail->product;
            if ($product->is_set) {
                StockFunction::decreaseSetStock($product->id, $stockin_qty, $order);
            }
            StockBalanceFunction::addBalance($product->id, 0, $stockin_qty, 0, 'Revert order cancel ke baru: ' . $order->invoicenumber);
            $product->qty -= $stockin_qty;
            $product->save();

            $stockins = Stockin::whereProduct_id($orderdetail->product_id)->where('qty', '<>', 0)->orderBy('created_at')->get();

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
        }
    }

    public static function checkStock($cart_name, $is_manual_sales = 0) {
        //TODO
        //tambah validasi kalau produk paket ada produk yang sama dengan produk non paket yang diorder.

        $validation = false;

        foreach (Cart::instance($cart_name)->content() as $cart) {

            $product = Product::find($cart->id);
            
            #cek dulu stoknya saat ini
            $check_qty = $product->qty;
            
            #cek apakah dari manual sales
            if($is_manual_sales){
                $check_qty = $product->qty + $product->reserved_qty;
            }
            
            if ($check_qty < $cart->qty) {
                #update stoknya jadi stok maksimal kalau tidak cukup stoknya
                Cart::instance('main')->update($cart->rowid, array(
                    'qty' => $check_qty,
                    'options' => [
                        'max' => $check_qty
                    ]
                ));
                StockFunction::updateWholesalePrice($cart->options->discountqty_id, $cart->id);
                StockFunction::updateTotal();
                $validation = true;
            }
            
            #cek untuk produk yang set
            if ($product->is_set) {
                $curr_qty = $product->qty;
                
                #ambil seluruh produk yang satu paket
                #ambil qty terkecil
                $smallest_qty = $curr_qty;
                foreach ($product->sets($product->id) as $set_product) {
                    if($smallest_qty > $set_product->product->qty){
                        $smallest_qty = $set_product->product->qty;
                    }
                }
                
                #kalau qty terkecil lebih kecil daripada qty yg dibeli, update
                if($smallest_qty < $curr_qty) {
                    #update stoknya jadi stok maksimal kalau tidak cukup stoknya
                    Cart::instance($cart_name)->update($cart->rowid, array(
                        'qty' => $smallest_qty,
                        'options' => [
                            'max' => $smallest_qty
                        ]
                    ));
                    StockFunction::updateWholesalePrice($cart->options->discountqty_id, $cart->id);
                    StockFunction::updateTotal();
                    $validation = true;

                    $product->qty = $smallest_qty;
                    $product->save();
                }
            }
        }

        return $validation;
    }

    public static function checkRevertStock($orderdetails) {
        $validation = false;
        foreach ($orderdetails as $orderdetail) {
            if (!$validation) {
                $product = Product::find($orderdetail->product_id);
                if ($product->is_set) {
                    foreach ($product->sets($product->id) as $set_product) {
                        //validasi kalau ada produk sejenis selain produk set
                        //TODO
                        $result = \App\Orderdetail::where('orderheader_id', '=', $orderdetail->orderheader_id)
                                ->where('product_id', '=', $product->id)
                                ->first();
                        $add_qty = 0;
                        if ($result) {
                            $add_qty = $result->qty;
                        }
                        $total_qty = $result->qty + $add_qty;

                        if ($set_product->product->qty < $total_qty) {
                            $validation = true;
                            break;
                        }
                        
                        if ($set_product->product->qty < $orderdetail->qty) {
                            $validation = true;
                            break;
                        }
                    }
                } else {
                    if ($product->qty < $orderdetail->qty) {
                        $validation = true;
                        break;
                    }
                }
            }
        }
        return $validation;
    }

    private static function updateTotal() {

        Cart::instance('total')->destroy();
        $total = Cart::instance('main')->total() - Cart::instance('discountcoupon')->total() - Cart::instance('discountpoint')->total();
        if ($total < 0) {
            $total = 0;
        }
        $total = $total + Cart::instance('shipcost')->total() + Cart::instance('insurancecost')->total() + Cart::instance('unique')->total();
        Cart::instance('total')->add('1', 'total', 1, $total);
    }

    public static function updateWholesalePrice($discountqty_id, $product_id) {

        if ($discountqty_id != '') {
            $rows = Cart::instance('main')->search(array('options' => array('discountqty_id' => $discountqty_id)));
            $qty = 0;
            if ($rows != false) {
                foreach ($rows as $row_id) {
                    $qty += Cart::instance('main')->get($row_id)->qty;
                }

                $price = Custom\PriceFunction::getCurrentPrice($product_id);

                $productclasses = Productclass::join('discountqties', 'discountqties.id', '=', 'productclasses.discountqty_id')
                                ->where('productclasses.product_id', '=', $product_id)
                                ->where('productclasses.userstatus_id', '=', auth()->user()->usersetting->status_id)
                                ->orderBy('discountqties.min_qty')
                                ->select('discountqties.min_qty', 'discountqties.price')->get();
                foreach ($productclasses as $productclass) {
                    if ($qty >= $productclass->min_qty) {
                        $price = $productclass->price;
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

    public static function getStockBooked($product_id) {

        $stock_booked = Orderheader::join('orderdetails', 'orderdetails.orderheader_id', '=', 'orderheaders.id')
                ->where('orderdetails.product_id', '=', $product_id)
                ->where('orderheaders.status_id', '<', 14)
                ->sum('orderdetails.qty');
        
        if(!$stock_booked){
            return 0;
        }

        return $stock_booked;
    }

    public static function getStockSold($product_id, $count_days = 30) {
        $stock_sold = Orderheader::join('orderdetails', 'orderdetails.orderheader_id', '=', 'orderheaders.id')
                ->where('orderdetails.product_id', '=', $product_id)
                ->where('orderheaders.status_id', '>=', 13)
                ->where('orderheaders.created_at', '>=', \Carbon\Carbon::now()->subDays($count_days)->toDateTimeString())
                ->sum('orderdetails.qty');

        if(!$stock_sold){
            return 0;
        }
        return $stock_sold;
    }

    public static function decreaseSetStock($set_id, $qty_decrease, $order) {
        $buy_qty = $qty_decrease;
        $productsets = Productset::where('set_id', '=', $set_id)
                ->get();
        foreach ($productsets as $set) {
            $stockins = Stockin::whereProduct_id($set->product_id)->where('qty', '>', 0)->orderBy('created_at')->get();
            foreach ($stockins as $stockin) {
                if ($stockin->remaining_qty - $qty_decrease <= 0) {
                    $qty_decrease -= $stockin->remaining_qty;
                    $stockin->remaining_qty = 0;
                    $stockin->save();
                } else {
                    $stockin->remaining_qty -= $qty_decrease;
                    $qty_decrease = 0;
                    $stockin->save();
                }
            }

            $product = Product::find($set->product_id);
            $product->qty -= $buy_qty;
            StockBalanceFunction::addBalance($set->product_id, 0, $buy_qty, 0, 'Revert order cancel ke baru: ' . $order->invoicenumber);
            $product->save();
        }
    }

    public static function returnStock($product_id, $return_stock, $order) {
        $product = Product::find($product_id);
        StockBalanceFunction::addBalance($product_id, $return_stock, 0, 0, 'Return stok order: ' . $order->invoicenumber);
        $product->qty += $return_stock;
        $product->save();

        #hitung stock booked dan stock soldnya
        $stock_booked = StockFunction::getStockBooked($product->id);
        $stock_sold = StockFunction::getStockSold($product->id);
        $product->stock_booked = $stock_booked;
        $product->stock_sold_30_days = $stock_sold;
        $product->save();

        $stockins = Stockin::whereProduct_id($product_id)
                        ->where('qty', '<>', 'remaining_qty')
                        ->orderBy('created_at', 'desc')->get();
        foreach ($stockins as $stockin) {
            if ($return_stock > 0) {
                if ($stockin->remaining_qty + $return_stock > $stockin->qty) {
                    $return_stock -= ($stockin->qty - $stockin->remaining_qty);
                    $stockin->remaining_qty = $stockin->qty;
                } else {
                    $stockin->remaining_qty += $return_stock;
                    $return_stock = 0;
                }

                $stockin->save();
            } else {
                continue;
            }
        }
    }
    
    public static function saveReservedStockHistory($initial_reserved_qty, $used_reserved_qty, $orderheader_id){
        $history = new Reservedstockhistory;
        $history->orderheader_id = $orderheader_id;
        $history->initial_qty = $initial_reserved_qty;
        $history->current_qty = $initial_reserved_qty - $used_reserved_qty;
        $history->change_qty = $used_reserved_qty;
        $history->save();
    }

}
