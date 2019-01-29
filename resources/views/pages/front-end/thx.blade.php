@extends('layouts.front-end.layouts')


@section('content')


<div class="container">
    <div class="row"> 
        <div class="col-md-12">
            <div class="row">
                <div class="col-sm-6 col-sm-offset-3">
                    <h2 class="text-center heading">Berhasil Melakukan Pemesanan</h2>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-10 col-md-offset-1 text-center">
            <?php $banks = App\Bank::all(); ?>
            <p>
                Terima kasih telah berbelanja di www.koreanluxury.com. <br><br>
                Jumlah yang harus Anda transfer adalah : <b>{!! 'Rp. ' . number_format($total_cart, 2, ',', '.') !!}</b> ke bank yang tersedia di bawah ini<br><br>
                Mohon lakukan pembayaran ke :<br><br>
                @foreach($banks as $bank)
                {!! $bank->bank_name . ' - ' . $bank->bank_account . ' a.n ' . $bank->bank_account_name . "<br />" !!}
                @endforeach
                <br>Sertakan nomor invoice <b>{!! $order->invoicenumber !!}</b> dalam catatan saat melakukan pembayaran.<br>
                <br>Jika dalam 24 jam, pembayaran tidak dilakukan, maka kami akan membatalkan pesanan anda.<br>
                Jika anda sudah melakukan pembayaran, maka anda wajib melakukan konfirmasi pembayaran anda di <a href="{{ URL::to('paymentconfirmation') }}">konfirmasi pembayaran</a><br>.
                Jika anda ingin melakukan pembelian lainnya, anda bisa menuju ke <a href="{{ URL::to('home') }}">halaman utama</a><br>.
            </p>
        </div>
    </div>
</div>

@stop