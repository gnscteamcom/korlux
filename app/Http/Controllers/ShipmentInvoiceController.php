<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Orderheader;
use App\Customerpoint;
use App\Pointhistory;
use App\Pointconfig;
use Excel;

class ShipmentInvoiceController extends Controller {
    
    
    public function viewInputShipment(){
        
        $orders = Orderheader::join('paymentconfirmations', 'paymentconfirmations.orderheader_id', '=', 'orderheaders.id')
                ->where('orderheaders.status_id', '=', 14)
                ->orderBy('paymentconfirmations.created_at', 'asc')
                ->select('orderheaders.id', 'orderheaders.user_id', 'orderheaders.customeraddress_id', 'orderheaders.created_at', 'orderheaders.invoicenumber',
                        'orderheaders.total_weight', 'orderheaders.shipment_cost')
                ->paginate(50);
        
        return view('pages.admin-side.modules.shipmentinvoice.viewshipment')->with(array(
            'orders' => $orders
        ));
        
    }
    
    
    public function viewImportShipmentInvoice(){
        
        return view('pages.admin-side.modules.shipmentinvoice.viewimportshipment');
        
    }
    
    
    public function downloadShipmentInvoiceFormat(){
        
        Custom\ExportFunction::exportForImportShipmentInvoice();
        
    }
    
    public function downloadTodayShipment(){
        Custom\ExportFunction::exportForTodayShipment();
    }
    
    
    public function shipmentInvoice(Request $request){
        
        $this->validate($request, [
            'resi' => 'required|max:32'
        ]);
        
        $order = Orderheader::find($request['order_id']);
        
        if($order->status_id == 14){
            $order->status_id = 15;
            $order->shipment_date = date('Y-m-d');
            $order->shipment_invoice = $request['resi'];
            $order->save();
        }
        
//        $this->setAvailablePoint($order);
        $this->increaseTotalBuy($order);
        
//        $email = $order->user->usersetting->email;
//        Custom\OrderFunction::shipmentEmail($email, $order->shipment_invoice);
        
        return back()->with(array(
            'msg' => 'Nomor Resi telah disimpan.. Pesanan telah dikirimkan..'
        ));
        
    }
    
    
    public function importShipmentInvoice(Request $request){
        
        $this->validate($request, [
            'file' => 'required'
        ]);
            
        //inisialisasi data
        $file = $request['file'];
        $filesize = $file->getSize(); //hasil dalam satuan bytes..

        //Validasi ukuran file
        //Ukuran file > 0B
        if($filesize <= 0){
            return back()->with('err', 'File tidak ada data..');
        }

        //Validasi extension file
        //Extension file harus .xls atau .xlsx
        if($file->getClientOriginalExtension() != 'xls' && $file->getClientOriginalExtension() != 'xlsx'  ){
            return back()->with('err', 'File harus .xls atau .xlss');
        }


        echo '<h1>Daftar Import yang Gagal :</h1>';

        Excel::selectSheets('Sheet1')->load($file, function($reader){

            //Baca smua sheets
            $reader->each(function($row){
                
                if(strlen($row->nomor) > 0){
                
                    if(ctype_space($row->nomor_resi) || $row->nomor_resi == null || $row->nomor_resi == ''){
                        echo '<b style="color:red">' . $row->nomor . ' => nomor resi kosong</b><br />';
                    }
                    else{

                        $orderheader = Orderheader::where('invoicenumber', 'like', $row->nomor)
                                ->whereNull('shipment_invoice')->first();

                        if($orderheader != null){
                            if($orderheader->status_id == 14){
                                $orderheader->status_id = 15;
                                $orderheader->shipment_date = date('Y-m-d');
                                $orderheader->shipment_invoice = $row->nomor_resi;
                                $orderheader->save();
        
                                $this->setAvailablePoint($orderheader);
                                $this->increaseTotalBuy($orderheader);
                            }
                            
                            echo '<b>' . $row->nomor . ' => BERHASIL</b><br />';
                        }
                        else{
                            echo '<b style="color:red">' . $row->nomor . ' => Tidak ada nomor order / Salah nomor order</b><br />';
                        }
                        
                    }
                    
                }

            });

        });
        
        echo '<br /><a href="' . url()->previous() . '">Kembali</a>';
        
    }
    
    
    public function searchShipment(Request $request){
        
        $counter = Orderheader::where('status_id', '=', 14)
                ->where('invoicenumber', 'like', '%' . $request['search'] . '%')
                ->count();
        $orders = Orderheader::join('paymentconfirmations', 'paymentconfirmations.orderheader_id', '=', 'orderheaders.id')
                ->where('orderheaders.status_id', '=', 14)
                ->where('orderheaders.invoicenumber', 'like', '%' . $request['search'] . '%')
                ->orderBy('paymentconfirmations.created_at', 'asc')
                ->select('orderheaders.id', 'orderheaders.user_id', 'orderheaders.customeraddress_id', 'orderheaders.created_at', 'orderheaders.invoicenumber',
                        'orderheaders.total_weight', 'orderheaders.shipment_cost')
                ->paginate($counter);
        
        return view('pages.admin-side.modules.shipmentinvoice.viewshipment')->with(array(
            'orders' => $orders
        ));
        
    }
    
    
    private function setAvailablePoint($order){
        $config = Pointconfig::first();
        if(!$config->is_active){
            return ;
        }
        
        //kalau sudah diinput resi, available date ditambah 5 hari
        //dan expired date pointnya direset
        if($order->user->is_admin == 0){
            
            if($order->discount_coupon == 0 && $order->user->usersetting->status_id == 1){
                $available_date = \Carbon\Carbon::now()->addDays(5);
                $expired_date = \Carbon\Carbon::now()->addDays(90);
                $point_added = Custom\PointFunction::calculatePoint($order->grand_total);

                $point_history = new Pointhistory;
                $point_history->user_id = $order->user_id;
                $point_history->point_added = $point_added;
                $point_history->point_used = 0;
                $point_history->orderheader_id = $order->id;
                $point_history->available_date = $available_date;
                $point_history->isCalculate = 0;
                $point_history->save();

                $customerpoint = Customerpoint::whereUser_id($order->user_id)->first();
                if($customerpoint == null){
                    $customerpoint = new Customerpoint;
                    $customerpoint->user_id = $order->user_id;
                    $customerpoint->total_point = 0;
                }
                $customerpoint->expired_date = $expired_date;
                $customerpoint->save();
                
            }
            
        }
        
    }
    
    
    private function increaseTotalBuy($orderheader){
        
        foreach($orderheader->orderdetails as $orderdetail){
            $product = $orderdetail->product;
            $product->total_buy = $product->total_buy + $orderdetail->qty;
            $product->save();
        }
        
    }

    
    
}
