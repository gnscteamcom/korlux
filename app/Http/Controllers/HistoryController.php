<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Orderheader;

class HistoryController extends Controller {

    
    public function viewOrderHistory(){
        
        $user_id = auth()->user()->id;
        
        //Ambil list order
        if($user_id == 1){
            $orders = Orderheader::orderBy('created_at', 'desc')
                    ->select('id', 'invoicenumber', 'status_id', 'created_at', 'shipment_date',
                            'shipment_invoice')
                    ->paginate(100);
        }
        else{
            $orders = Orderheader::whereUser_id($user_id)
                    ->orderBy('created_at', 'desc')
                    ->select('id', 'invoicenumber', 'status_id', 'created_at', 'shipment_date',
                            'shipment_invoice')
                    ->paginate(50);
        }
        
        
        return view('pages.front-end.history')->with(array(
            'orders' => $orders
        ));
        
    }
    
    
    public function viewOrderDetail($id){
        
        if(auth()->user()->id == 1){
            $order = Orderheader::whereId($id)
                ->first();
        }
        else{
            $order = Orderheader::whereId($id)
                ->whereUser_id(auth()->user()->id)
                ->first();
        }
        
        if($order == null){
            return redirect('home');
        }
        
        return view('pages.front-end.orderdetail')->with(array(
            'order' => $order
        ));
        
        
    }
    
    
}
