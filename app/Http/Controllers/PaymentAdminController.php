<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Orderheader;
use App\Bank;
use App\Paymentconfirmation;


class PaymentAdminController extends Controller {

    public function viewNewOrder(){
        $orders = Orderheader::where('status_id', '=', 11)
                ->orderBy('invoicenumber')
                ->get();
        return view('pages.admin-side.modules.paymentconfirmation.payment')->with([
            'orders' => $orders
        ]);
    }
    
    public function viewPaymentConfirmation($id){
        $banks = Bank::all();
        $order = Orderheader::find($id);
        return view('pages.admin-side.modules.paymentconfirmation.confirmpayment')->with([
            'order' => $order,
            'banks' => $banks
        ]);
    }
    
    public function confirmPayment(Request $request){
        $this->validate($request, [
            'tanggal_bayar' => 'required',
            'nama_rekening' => 'required|max:64',
            'bank' => 'required',
            'note' => 'min:1'
        ]);
        
        //hapus dulu konfirmasi sebelumnya
        $payment_confirmations = Paymentconfirmation::where('orderheader_id', '=', $request['order_id'])
                ->get();
        foreach($payment_confirmations as $payment_confirmation){
            $payment_confirmation->delete();
        }

        //Insert konfirmasi
        $payment_confirmation = new Paymentconfirmation;
        $payment_confirmation->user_id = auth()->user()->id;
        $payment_confirmation->orderheader_id = $request['order_id'];
        $payment_confirmation->account_name = $request['nama_rekening'];
        $payment_confirmation->payment_date = date('Y-m-d', strtotime($request['tanggal_bayar']));
        $payment_confirmation->bank_id = $request['bank'];
        $payment_confirmation->note = $request['note'];
        $payment_confirmation->save();
        
        //update status order nya jadi 12
        $order = Orderheader::find($request['order_id']);
        $order->status_id = 12;
        $order->save();
        
        return redirect('paymentconfirmationadmin')->with([
            'msg' => 'Invoice ' . $order->invoicenumber . ' berhasil dikonfirmasi oleh Admin.'
        ]);
        
    }
    
}
