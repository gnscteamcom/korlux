<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\ShipmentApi;
use App\Customeraddress;
use App\Orderheader;
use App\Orderdetail;
use App\Stockin;
use App\Product;
use App\User;
use App\Dropship;
use App\Pointhistory;
use App\Customerpoint;
use App\Contact;
use App\Subcategory;
use App\Pointconfig;

use App\Http\Controllers\Custom\StockFunction;


class AjaxController extends Controller {
    
    
    public function getProduct(Request $request){
        
        $product_id = $request['product_id'];
        
        $product = Product::find($product_id);
        
        return $product;
        
    }
    
    
    public function getSubcategory(Request $request){
        
        $subcategories = Subcategory::whereCategory_id($request['kategori'])
                ->orderBy('subcategory')
                ->select('id', 'subcategory')
                ->get();
        
        return $subcategories;
        
    }
    
    
    public function getSiteAddress(Request $request){
        
        $contact = Contact::first();
        return $contact;
        
    }
    
    
    public function getMyAddress(Request $request){
        
        return auth()->user()->usersetting;
        
    }
    
    
    public function getCustomerAddress(Request $request){
        
        $user = User::find($request['user_id']);
        
        return $user->usersetting;
        
    }
    
    
    public function getMyCustomerAddress(Request $request){
        
        return Customeraddress::find($request['customeraddress_id']);
        
    }
    
    
    public function getMyCustomerDropship(Request $request){
        
        return Dropship::find($request['dropship_id']);
        
    }
    
    
    public function getInvoiceValue(Request $request) {
        $order_header = Orderheader::find($request['orderheader_id']);
        return $order_header->total_paid;
    }

    public function cancelExpiredOrder(){
        
        $order_headers = Orderheader::whereStatus_id(11)
            ->where('updated_at', '<', \Carbon\Carbon::now()->subHours(30))
            ->get();
        
        
        foreach($order_headers as $order_header){
            
            #kalau ada shopee sales
            if($order_header->shopeesales){
                $sales = $order_header->shopeesales;
                $sales->delete();
            }
            
            //balikin diskon kupon
            if($order_header->discountcoupon_id > 0){
                $discount_coupon = $order_header->discountcoupon;
                if($discount_coupon){
                    $discount_coupon->available_count += 1;
                    $discount_coupon->save();
                }
            }
            
            $order_header->status_id = 17;
            $order_header->save();
        
            if($order_header->user->usersetting != null){
                $email = $order_header->user->usersetting->email;
                Custom\OrderFunction::cancelEmail($email);
            }

            $order_details = Orderdetail::whereOrderheader_id($order_header->id)->get();

            //balikin stok kalau order di cancelled...
            foreach($order_details as $order_detail){
                $product = Product::find($order_detail->product_id);
                $return_stock = $order_detail->qty;
                
                if($product->is_set){
                    foreach($product->sets($product->id) as $set){
                        StockFunction::returnStock($set->product->id, $return_stock, $order_header);
                    }
                }
                StockFunction::returnStock($product->id, $return_stock, $order_header);

            }
        }
        
    }
    
    
    public function addPoint(){
        
        $config = Pointconfig::first();
        
        if(!$config->is_active){
            return;
        }
        
        $now = \Carbon\Carbon::now()->toDateString();
        
        $pointhistories = Pointhistory::whereNotNull('available_date')
                ->where('available_date', '<=', $now)
                ->whereIscalculate(0)
                ->select('id', 'user_id', 'point_added' , 'available_date', 'isCalculate')->get();
        
        foreach($pointhistories as $pointhistory){
            
            $customerpoint = Customerpoint::whereUser_id($pointhistory->user_id)->first();
            if($customerpoint == null){
                $customerpoint = new Customerpoint;
                $customerpoint->total_point = $pointhistory->point_added;
                $customerpoint->user_id = $pointhistory->user_id;
            }
            else{
                $customerpoint->total_point += $pointhistory->point_added;
            }
            $customerpoint->save();

            $pointhistory->isCalculate = 1;
            $pointhistory->save();
        }

    }
    
    
    public function countExpiredPoint(){
        
        $now = \Carbon\Carbon::now()->toDateTimeString();
        
        $customerpoints = Customerpoint::whereNotNull('expired_date')
                ->where('expired_date', '<', $now)
                ->where('total_point', '>', 0)
                ->select('id', 'total_point', 'expired_date')
                ->get();
        
        foreach($customerpoints as $customerpoint){
            $customerpoint->total_point = 0;
            $customerpoint->expired_date = NULL;
            $customerpoint->save();
        }
        
    }
    
    
    public function getAddToList(){
        
        $add_tos = Customeraddress::whereUser_id(auth()->user()->id)
                ->select('id', 'address_name')
                ->orderBy('address_name')
                ->get();
        
        return $add_tos;
        
    }
    
    
    public function getAddToData(Request $request){
        $customeraddress = Customeraddress::find($request['customeraddress_id']);
        
        return $customeraddress;
        
    }
    
    
    public function getAddFromList(){
        
        $add_froms = Dropship::whereUser_id(auth()->user()->id)
                ->select('id', 'dropship_name')
                ->orderBy('dropship_name')
                ->get();
        
        return $add_froms;
        
    }
    
    
    public function getAddFromData(Request $request){
        
        $dropship = Dropship::find($request['dropship_id']);
        
        return $dropship;
        
    }
    
    
    public function processUser(){
        $user = auth()->user();
        $user->is_processed = 1;
        $user->save();
    }
    
    public function getShipmentMethod(Request $request){
        $kecamatan_id = $request->kecamatan_id;
        $result = ShipmentApi::methods($kecamatan_id);
        
        return json_encode($result);
    }
    
    public function getShipmentCost(Request $request){
        $kecamatan_id = $request->kecamatan_id;
        $ship_method = $request->ship_method;
        $result = ShipmentApi::shipcosts($kecamatan_id, $ship_method);
        
        return $result;
    }
    
    public function getInsuranceFee(Request $request){
        $ship_method = $request->ship_method;
        $total = $request->total;
        $result = ShipmentApi::insurance($ship_method, $total);
        
        return $result;
    }
    
    public function paymentTotal(Request $request) {
        #orders yang dicentang
        $orders = $request->orders;

        #kalau ada yang dicentang, hitung totalnya
        $total_payment = 0;
        if ($orders) {
            foreach ($orders as $order_id) {
                #hitung per order
                $order_header = Orderheader::find($order_id);
                #jumlahkan semua
                $total_payment += $order_header->total_paid;
            }
        }

        return $total_payment;
    }

}
