<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Custom\StockFunction;
use App\Http\Controllers\Custom\RefundFunction;
use App\Orderheader;
use App\Refundrequest;
use App\Refundrequestdetail;
use App\Discountcoupon;

class RefundController extends Controller {

    public function viewRequestRefund() {
        $orders = Orderheader::where('user_id', '=', auth()->user()->id)
                ->whereIn('status_id', [13, 15])
                ->where('updated_at', '>=', \Carbon\Carbon::now()->subDays(30)->toDateTimeString())
                ->orderBy('created_at', 'desc')
                ->get();

        $i = 0;
        $orders_data[] = [];
        foreach ($orders as $order) {
            $refund_request = Refundrequest::where('order_id', '=', $order->id)
                    ->whereIn('status_id', [1, 2])
                    ->first();
            if (!$refund_request) {
                $orders_data[$i] = [
                    'id' => $order->id,
                    'invoicenumber' => $order->invoicenumber
                ];
                $i++;
            }
        }

        return view('pages.front-end.requestrefund')->with([
                    'orders' => array_filter($orders_data),
        ]);
    }

    public function viewRefund() {
        $orders = Refundrequest::orderBy('created_at', 'desc')
                ->paginate(50);
        return view('pages.admin-side.modules.refund.refunds')->with([
                    'orders' => $orders
        ]);
    }

    public function requestRefund(Request $request) {
        $this->validate($request, [
            'nomor_order' => 'required',
            'alasan' => 'required',
            'nama_bank' => 'required_without:refund_voucher',
            'nama_rekening' => 'required_without:refund_voucher',
            'nomor_rekening' => 'required_without:refund_voucher',
        ]);

        #cek kalau tidak ada nominal yang direfund sama sekali
        if ($request->total_refund <= 0) {
            return back()->with([
                        'err' => 'Tidak ada data yang direfund. Silahkan ulangi dan pilih jumlah produk yang ingin direfund.'
            ]);
        }

        #cek full refund bukan
        $is_full_refund = 0;
        if ($request->refund_semua != null) {
            $is_full_refund = 1;
        }

        #cek refund ke voucher
        $is_refund_voucher = 0;
        if ($request->refund_voucher != null) {
            $is_refund_voucher = 1;
        }

        #cek apakah ada order tersebut
        $order = Orderheader::find($request->nomor_order);
        if (!$order) {
            return back()->with([
                        'err' => 'Tidak ada order yang ditemukan.'
                    ])->withInput();
        }

        #cek status order
        if (!in_array($order->status_id, [13, 15])) {
            return back()->with([
                        'err' => 'Hanya order dengan status pembayaran yang sudah diverifikasi dan sudah dikirim dalam waktu 7 hari yang dapat di-refund.'
                    ])->withInput();
        }

        #cari apakah sudah pernah request refund
        $refund_request = Refundrequest::where('order_id', '=', $order->id)
                ->where('status_id', '=', 1)
                ->first();
        if ($refund_request) {
            return back()->with([
                        'err' => 'Anda sudah melakukan request pada order ini, mohon menunggu respon dari kami.'
                    ])->withInput();
        }

        $refund_request = new Refundrequest;
        $refund_request->order_id = $order->id;
        $refund_request->status_id = 1;
        $refund_request->refund_reason = $request->alasan;
        $refund_request->total_refund = $request->total_refund;
        $refund_request->is_full_refund = $is_full_refund;
        $refund_request->is_refund_voucher = $is_refund_voucher;
        #kalau tidak pakai voucher
        if (!$is_refund_voucher) {
            $refund_request->bank_name = $request->nama_bank;
            $refund_request->account_name = $request->nama_rekening;
            $refund_request->account_number = $request->nomor_rekening;
        }
        $refund_request->save();

        #kalau partial refund
        if (!$is_full_refund) {
            $i = 0;
            foreach ($request->orderdetail_id as $orderdetail_id) {
                #save ke DB kalau ada refund
                if ($request->new_qty[$i] != 0) {
                    $refund_detail = new Refundrequestdetail;
                    $refund_detail->refund_id = $refund_request->id;
                    $refund_detail->orderdetail_id = $orderdetail_id;
                    $refund_detail->initial_qty = $request->initialqty[$i];
                    $refund_detail->current_qty = $request->initialqty[$i] - $request->new_qty[$i];
                    $refund_detail->refund_qty = $request->new_qty[$i];
                    $refund_detail->price = $request->price[$i];
                    $refund_detail->total_refund = $refund_detail->price * $refund_detail->refund_qty;
                    $refund_detail->save();
                    ;
                }

                $i++;
            }
        }

        return back()->with([
                    'msg' => 'Request refund Anda telah kami terima. Mohon menunggu respon dari kami.'
        ]);
    }

    public function rejectRefund(Request $request) {
        $this->validate($request, [
            'refund_id' => 'required',
            'notes' => 'min:1'
        ]);

        $refund = Refundrequest::find($request->refund_id);
        $refund->status_id = 3;
        $refund->reject_reason = $request->notes;
        $refund->save();

        #kirim email ke customer
        $email = '';
        if ($refund->order->user->usersetting) {
            $email = $refund->order->user->usersetting->email;
        }
        if (strlen($email) > 0) {
            RefundFunction::sendRefundRejectEmail($refund, $email);
        }

        return back()->with([
                    'msg' => 'Pengajuan refund untuk order dengan invoice : ' . $refund->order->invoicenumber . ' berhasil ditolak.'
        ]);
    }

    public function acceptRefund(Request $request) {
        $this->validate($request, [
            'refund_id' => 'required',
            'notes' => 'min:1'
        ]);
        $refund = Refundrequest::find($request->refund_id);

        #kalau full refund
        if ($refund->is_full_refund) {
            #kalau full refund
            #batalin order, balikin stok
            StockFunction::returnManualSalesStock($refund->order);
        } else {
            #kalau tidak full refund
            #terusin order, ubah histori order, tidak balikin stok
            RefundFunction::refundPartialOrder($refund);
        }

        $refund->status_id = 2;
        $refund->approve_reason = $request->notes;

        #kalau dia refund ke voucher
        $voucher_code = '';
        if ($refund->is_refund_voucher) {
            $voucher_code = str_random(24);

            $discount_coupon = new Discountcoupon;
            $discount_coupon->coupon_code = $voucher_code;
            $discount_coupon->valid_date = \Carbon\Carbon::now()->toDateString();
            $discount_coupon->expired_date = \Carbon\Carbon::now()->addDays(30)->toDateString();
            $discount_coupon->available_count = 1;
            $discount_coupon->nominal_discount = $refund->total_refund;
            $discount_coupon->only_for_user = $refund->order->user_id;
            $discount_coupon->available_for_status = $refund->order->user->usersetting->status_id;
            $discount_coupon->save();

            $refund->voucher_code = $voucher_code;
            $refund->status_id = 4;
        }

        $refund->save();

        #kirim email ke admin
        RefundFunction::sendRefundRequestEmail($refund);

        #kirim email ke customer
        $email = '';
        if ($refund->order->user->usersetting) {
            $email = $refund->order->user->usersetting->email;
        }

        $expired_date = null;
        if (isset($discount_coupon)) {
            $expired_date = $discount_coupon->expired_date;
        }
        if (strlen($email) > 0) {
            RefundFunction::sendRefundVoucherEmail($refund, $expired_date, $refund->order->user->usersetting->email);
        }

        return back()->with([
                    'msg' => 'Pengajuan refund untuk order dengan invoice : ' . $refund->order->invoicenumber . ' berhasil diproses.'
        ]);
    }

    public function finishRefund($id) {
        $refund = Refundrequest::find($id);
        $refund->status_id = 4;
        $refund->save();

        return back()->with([
                    'msg' => 'Refund telah selesai diproses.'
        ]);
    }
    
    public function searchRefund(Request $request){
        if (strlen($request->search) <= 0) {
            return back();
        }
        
        $refunds = Refundrequest::join('orderheaders', 'orderheaders.id', '=', 'refundrequests.order_id')
                ->where('orderheaders.invoicenumber', 'like', '%' . $request->search . '%')
                ->orderBy('orderheaders.updated_at', 'desc')
                ->select('refundrequests.*')
                ->paginate(50);

        return view('pages.admin-side.modules.refund.refunds')->with(array(
                    'orders' => $refunds
        ));
        
    }

}
