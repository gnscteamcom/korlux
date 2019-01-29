<?php

namespace App\Http\Controllers\Custom;

use App\Orderdetail;
use App\Discountcoupon;
use Mail;

class RefundFunction {

    public static function refundPartialOrder($refund){
        #update detail
        foreach($refund->refundrequestdetails as $detail){
            $orderdetail = Orderdetail::find($detail->orderdetail_id);
            $orderdetail->qty = $detail->current_qty;
            $orderdetail->save();
        }

        #update header
        $order = $refund->order;
        $order->grand_total = $order->grand_total - $refund->total_refund;
        $order->save();
    }

    public static function sendRefundRequestEmail($refund_request) {
        $refund_ke = $refund_request->bank_name . ' ' . $refund_request->account_number . ' a/n ' . $refund_request->account_name;
        if($refund_request->voucher_code){
            $refund_ke = "VOUCHER " . $refund_request->voucher_code;
        }
        $email_message = "Nomor order : " . $refund_request->order->invoicenumber . "\r\n"
                . "Jumlah refund : Rp. " . number_format($refund_request->total_refund, 0, ',', '.') . "\r\n"
                . "Refund ke : " . $refund_ke . "\r\n\r\n"
                . "Alasan : " . $refund_request->refund_reason;

        $subject = "REFUND " . $refund_request->order->invoicenumber;

        RefundFunction::sendEmail($email_message, $subject);
    }

    public static function sendRefundVoucherEmail($refund_request, $expired_date, $email_destination) {
        #kalau refund ke voucher
        if(strlen($refund_request->voucher_code) > 0){
            $coupon_code = Discountcoupon::where('coupon_code', 'like', $refund_request->voucher_code)->first();
            $email_message = "Permintaan refund anda telah diterima dan berikut adalah informasi voucher anda.\r\n\r\n"
                    . "Kode voucher : " . $refund_request->voucher_code . "\r\n"
                    . "Nominal : Rp. " . number_format($refund_request->total_refund, 0, ',', '.') . "\r\n"
                    . "Berlaku sampai : " . date('d F Y', strtotime($expired_date)) . "\r\n\r\n"
                    . "Keterangan: [" . $refund_request->refund_reason . "]\r\n\r\n"
                    . "jika menginginkan bukti refund atau ada pertanyaaan lebih lanjut, silahkan hubungi koreanluxuryshop@gmail.com dengan menyertakan nomor order anda.";

            $subject = "REFUND " . $refund_request->order->invoicenumber . " TELAH DIPROSES";
        }
        #kalau refund tidak ke voucher
        else{
            $email_message = "Permintaan refund anda telah diterima dan akan diproses maksimal 3x24 jam hari kerja bank (tidak termasuk Sabtu/Minggu/hari libur).*\r\n"
                . "Silahkan lakukan pengecekan berkala pada saldo rekening Anda.\r\n\r\n"
                . "Jumlah Refund : Rp. " . number_format($refund_request->total_refund, 0, ',', '.') . "\r\n"
                . "Nomor Rekening : " . $refund_request->account_number . ' a/n ' . $refund_request->account_name . " (" . $refund_request->bank_name . ")\r\n\r\n"
                . "Note:\r\n"
                . "*) Selain bank BCA, MANDIRI, BNI akan dikenakan admin bank sebesar Rp 6,500 yang dipotong langsung dari jumlah refund\r\n"
                . "*) khusus untuk BRI akan dikenakan admin bank sebesar Rp 750 yang dipotong langsung dari jumlah refund.\r\n\r\n"
                . "Keterangan: [" . $refund_request->refund_reason . "]\r\n\r\n"
                . "jika menginginkan bukti refund atau ada pertanyaaan lebih lanjut, silahkan hubungi koreanluxuryshop@gmail.com dengan menyertakan nomor order anda.";

            $subject = "REFUND " . $refund_request->order->invoicenumber . " SEDANG DIPROSES";
        }

        RefundFunction::sendEmailtoCustomer($email_message, $subject, $email_destination);
    }

    public static function sendRefundRejectEmail($refund_request, $email_destination) {
        $email_message = "Pengajuan refund : " . $refund_request->order->invoicenumber . " sebesar Rp. " . number_format($refund_request->total_refund, 2, ',', '.') . ' DITOLAK.\r\nDengan alasan : ' . $refund_request->reject_reason . '.\r\n'
                . 'Jika ada pertanyaan lebih lanjut, silahkan hubungi koreanluxuryshop@gmail.com dengan menyertakan nomor order anda.';

        $subject = "REFUND " . $refund_request->order->invoicenumber . " DITOLAK";

        RefundFunction::sendEmailtoCustomer($email_message, $subject, $email_destination);
    }

    public static function sendEmail($email_message, $subject) {
        Mail::raw($email_message, function ($message) use ($subject) {
            $message->from('noreply@koreanluxury.com', 'Koreanluxury');
            $message->to('koreanluxuryshop@gmail.com', $name = null);
            $message->replyTo('noreply@koreanluxury.com', 'Koreanluxury');
            $message->subject('[Koreanluxury] ' . $subject . ' - Jangan Dibalas !!');
        });
    }

    public static function sendEmailtoCustomer($email_message, $subject, $email_destination) {
        Mail::raw($email_message, function ($message) use ($subject, $email_destination) {
            $message->from('noreply@koreanluxury.com', 'Koreanluxury');
            $message->to($email_destination, $name = null);
            $message->replyTo('noreply@koreanluxury.com', 'Koreanluxury');
            $message->subject('[Koreanluxury] ' . $subject . ' - Jangan Dibalas !!');
        });
    }

}
