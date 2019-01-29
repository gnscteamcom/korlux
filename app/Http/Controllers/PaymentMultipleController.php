<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Paymentconfirmation;

class PaymentMultipleController extends Controller {

    public function acceptPayment($id) {
        #ambil seluruh payment yang multiple
        $payments = Paymentconfirmation::where('paymentconfirmation_id', '=', $id)
                ->get();

        $invoices = '';
        foreach ($payments as $payment) {
            $order = $payment->orderheader;
            $invoices .= $order->invoicenumber . ', ';
            if ($order->status_id == 12) {
                $order->status_id = 13;
                $order->payment_date = date('Y-m-d');
                $order->accept_time = \Carbon\Carbon::now()->toDateTimeString();
                $order->accept_by = auth()->user()->id;
                $order->save();

//                if ($order->user->usersetting != null) {
//                    $email = $order->user->usersetting->email;
//                    if (strlen($email) > 10) {
//                        Custom\OrderFunction::paymentAcceptedEmail($email);
//                    }
//                }
            }
        }

        return back()->with(array(
                    'msg' => 'Pembayaran telah berhasil diterima untuk invoice : ' . $invoices
        ));
    }

    public function rejectPayment($id) {
        #ambil seluruh payment yang multiple
        $payments = Paymentconfirmation::where('paymentconfirmation_id', '=', $id)
                ->get();

        $invoices = '';
        foreach ($payments as $payment) {
            $order = $payment->orderheader;
            $invoices .= $order->invoicenumber . ', ';
            if ($order->status_id == 12) {
                $order->status_id = 11;
                $order->save();
                $payment->delete();

                if (strcmp($order->invoicenumber[0], '#') != 0) {
                    Custom\StockFunction::returnManualSalesStock($order);
                } else {
                    if ($order->user->usersetting != null) {
                        Custom\OrderFunction::paymentRejectEmail($order->user->usersetting->email, $order->id);
                    }
                }
            }
        }

        return back()->with(array(
                    'err' => 'Pembayaran telah ditolak untuk invoice : ' . $invoices
        ));
    }

}
