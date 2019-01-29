<?php

namespace App\Http\Controllers\Command;

use App\Http\Controllers\Custom\StockFunction;
use App\Http\Controllers\Custom\OrderFunction;
use App\Customerpoint;
use App\Orderheader;
use App\Orderdetail;
use App\Pointhistory;
use App\Product;
use App\Pointconfig;

class OrderCommand {

    public static function cancelExpiredOrder() {
        $order_headers = Orderheader::whereStatus_id(11)
                ->where('updated_at', '<', \Carbon\Carbon::now()->subHours(30))
                ->get();


        foreach ($order_headers as $order_header) {

            #kalau ada shopee sales
            if ($order_header->shopeesales) {
                $sales = $order_header->shopeesales;
                $sales->delete();
            }

            //balikin diskon kupon
            if ($order_header->discountcoupon_id > 0) {
                $discount_coupon = $order_header->discountcoupon;
                if ($discount_coupon) {
                    $discount_coupon->available_count += 1;
                    $discount_coupon->save();
                }
            }

            $order_header->status_id = 17;
            $order_header->save();

            $order_details = Orderdetail::whereOrderheader_id($order_header->id)->get();

            //balikin stok kalau order di cancelled...
            foreach ($order_details as $order_detail) {
                $product = Product::find($order_detail->product_id);
                $return_stock = $order_detail->qty;

                if ($product->is_set) {
                    foreach ($product->sets($product->id) as $set) {
                        StockFunction::returnStock($set->product->id, $return_stock, $order_header);
                    }
                }
                StockFunction::returnStock($product->id, $return_stock, $order_header);
            }

            if ($order_header->user->usersetting != null) {
                $email = $order_header->user->usersetting->email;
                OrderFunction::cancelEmail($email);
            }
        }
    }

    public static function addPoint() {

        $config = Pointconfig::first();

        if (!$config->is_active) {
            return;
        }

        $now = \Carbon\Carbon::now()->toDateString();

        $pointhistories = Pointhistory::whereNotNull('available_date')
                        ->where('available_date', '<=', $now)
                        ->whereIscalculate(0)
                        ->select('id', 'user_id', 'point_added', 'available_date', 'isCalculate')->get();

        foreach ($pointhistories as $pointhistory) {

            $customerpoint = Customerpoint::whereUser_id($pointhistory->user_id)->first();
            if ($customerpoint == null) {
                $customerpoint = new Customerpoint;
                $customerpoint->total_point = $pointhistory->point_added;
                $customerpoint->user_id = $pointhistory->user_id;
            } else {
                $customerpoint->total_point += $pointhistory->point_added;
            }
            $customerpoint->save();

            $pointhistory->isCalculate = 1;
            $pointhistory->save();
        }
    }

    public static function countExpiredPoint() {

        $now = \Carbon\Carbon::now()->toDateTimeString();

        $customerpoints = Customerpoint::whereNotNull('expired_date')
                ->where('expired_date', '<', $now)
                ->where('total_point', '>', 0)
                ->select('id', 'total_point', 'expired_date')
                ->get();

        foreach ($customerpoints as $customerpoint) {
            $customerpoint->total_point = 0;
            $customerpoint->expired_date = NULL;
            $customerpoint->save();
        }
    }

}
