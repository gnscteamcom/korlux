<?php

namespace App\Http\Controllers\Custom;
use Mail;
use App\Orderheader;
use App\Bank;
use App\Product;
use App\Productclass;
use Cart;

class OrderFunction {

    public static function setInvoiceNumber($inisial = "#"){

        $invoice_number_start = date('y') . date('m') . date('d') . '-';
        $invoice_number_end = '';

        $orderheader_count = Orderheader::where('created_at', '>=' , \Carbon\Carbon::today() )
                ->where('created_at', '<=', \Carbon\Carbon::tomorrow())
                ->count();

        for($i = strlen($orderheader_count+1); $i < 4; $i++){
            $invoice_number_end .= '0';
        }

        return 'K' . $inisial . $invoice_number_start . $invoice_number_end . ($orderheader_count+1);

    }

    public static function setBarcode($invoicenumber){
        #bikin barcode dari invoicenumber, ambil angkanya saja.
        $barcode = substr(str_replace('-', '', $invoicenumber),1);
        return $barcode;
    }


    public static function calculateWeight($total_weight){

        //hitung total weight dalam kg
        if($total_weight % 1000 >= 1){
            $weight_kg = intval($total_weight / 1000) + 1;
        }else{
            $weight_kg = intval($total_weight / 1000);
        }


        if($weight_kg == 0){
            $weight_kg = 1;
        }

        return $weight_kg;

    }


    public static function newOrderEmail($destination_email, $orderheader_id){

        $order_header = Orderheader::find($orderheader_id);

        $banks = Bank::all();
        $text = "";
        foreach($banks as $bank){
            $text .= $bank->bank_name . " - " . $bank->bank_account . " a.n " . $bank->bank_account_name . "\r\n";
        }

        $total_value = $order_header->grand_total + $order_header->shipment_cost + $order_header->insurance_fee
                        + $order_header->unique_nominal - $order_header->discount_coupon - $order_header->discount_point;

        $email_message = "Pesanan Anda telah kami terima..\r\n"
                . "Nomor order anda adalah : " . $order_header->invoicenumber . "\r\n"
                . "Silahkan kirim pembayaran anda senilai Rp. " . number_format($total_value, 2, ",", ".") . " ke salah satu dari rekening di bawah ini:\r\n"
                . $text
                . "Kemudian silahkan konfirmasi pembayaran Anda dengan klik link di bawah ini..\r\n"
                . "http://www.koreanluxury.com/paymentconfirmation";
        $subject = "Pesanan Baru";

        OrderFunction::sendEmail($email_message, $subject, $destination_email);

    }


    public static function paymentAcceptedEmail($destination_email){

        $email_message = "Terima kasih atas konfirmasi pembayarannya.\r\n\r\n"
                . "Kami akan menerima verifikasi pembayaran anda setiap hari jam 10:00-15:00 kecuali hari minggu dan libur.\r\n\r\n"
                . "Silahkan cek status orderan anda di: http://www.koreanluxury.com/history \r\n\r\n"
                . "NOTE:\r\n"
                . "* Bagi yang SUKSES melakukan konfirmasi pembayaran SEBELUM JAM 15:00 wib, barang akan DIKIRIM PADA HARI YANG SAMA.\r\n"
                . "* Jika lewat, akan dikirim keesokan harinya. KECUALI MINGGU tidak ada pengiriman.\r\n"
                . "* Resi diupdate KEESOKAN HARI (jam 3-5sore) setelah barang dikirim. kecuali kiriman hari sabtu, resi diupdate senin";
        $subject = "Konfirmasi Pembayaran berhasil dikirim";

        OrderFunction::sendEmail($email_message, $subject, $destination_email);

    }


    public static function paymentRejectEmail($destination_email){

        $email_message = "Konfirmasi pembayaran Anda kami tolak..\r\n"
                . "Silahkan lakukan konfirmasi ulang..";
        $subject = "Konfirmasi Pembayaran Ditolak";

        OrderFunction::sendEmail($email_message, $subject, $destination_email);

    }


    public static function shipmentEmail($destination_email, $shipment_invoice){

        $email_message = "Pesanan Anda sudah kami kirim..\r\n"
                . "Nomor resi Anda adalah " . $shipment_invoice;
        $subject = "Pengiriman Pesanan";

        OrderFunction::sendEmail($email_message, $subject, $destination_email);

    }


    public static function cancelEmail($destination_email){

        $email_message = "Pesanan Anda kami batalkan karena tidak melakukan konfirmasi pembayaran..\r\n"
                . "Silahkan lakukan pemesanan ulang..\r\n"
                . "Terima kasih atas pesanan Anda..";
        $subject = "Pembatalan Pesanan";

        OrderFunction::sendEmail($email_message, $subject, $destination_email);

    }


    public static function sendVerifyEmail($destination_email, $invoicenumber){

        $email_message = "Kami tidak menerima konfirmasi pembayaran apapun atas pesanan dengan nomor order " . $invoicenumber . "..\r\n"
                . "Silahkan lakukan pembayaran sebelum kami batalkan pesanan Anda..\r\n"
                . "Terima kasih atas perhatian anda..";
        $subject = "Lakukan Konfirmasi Pembayaran";

        OrderFunction::sendEmail($email_message, $subject, $destination_email);

    }


    public static function sendEmail($email_message, $subject, $destination_email){
        Mail::raw($email_message, function ($message) use ($subject, $destination_email) {
            $message->from('noreply@koreanluxury.com', 'Koreanluxury');
            $message->to($destination_email, $name = null);
            $message->replyTo('noreply@koreanluxury.com', 'Koreanluxury');
            $message->subject('[Koreanluxury] ' . $subject . ' - Jangan Dibalas !!');
        });
    }

    public static function updateWholesalePrice($discountqty_id, $product_id, $cart_name, $status_id, $price = 0) {
        $product = Product::where('id', '=', $product_id)
                        ->select('currentprice_id')->first();
        if($price == 0){
            $price = PriceFunction::getCurrentPrice($product->currentprice_id);
        }

        if ($discountqty_id != '') {
            $rows = Cart::instance($cart_name)->search(array('options' => array('discountqty_id' => $discountqty_id)));
            $qty = 0;
            if ($rows != false) {
                foreach ($rows as $row_id) {
                    $qty += Cart::instance($cart_name)->get($row_id)->qty;
                }

                $productclasses = Productclass::join('discountqties', 'discountqties.id', '=', 'productclasses.discountqty_id')
                                ->where('productclasses.product_id', '=', $product_id)
                                ->where('productclasses.userstatus_id', '=', $status_id)
                                ->orderBy('discountqties.min_qty')
                                ->select('discountqties.min_qty', 'discountqties.price')->get();
                foreach ($productclasses as $productclass) {
                    if ($qty >= $productclass->min_qty) {
                        if ($price > $productclass->price) {
                            $price = $productclass->price;
                        }
                    }
                }

                foreach ($rows as $row_id) {
                    Cart::instance($cart_name)->update($row_id, array(
                        'price' => $price
                    ));
                }
            }
        }

        return $price;
    }

}
