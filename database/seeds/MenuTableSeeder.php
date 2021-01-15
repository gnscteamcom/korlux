<?php

use Illuminate\Database\Seeder;
use App\Menu;

class MenuTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Menu::truncate();

        $data = [
            [
                'menu' => 'Konfigurasi',
                'menu_link' => '#',
                'menu_icon' => 'wrench',
                'position' => 1,
            ],
            [
                'menu' => 'Ekspor Data',
                'menu_link' => 'viewexportlist',
                'menu_icon' => 'chart-line',
                'position' => 2,
            ],
            [
                'menu' => 'Harga & Diskon',
                'menu_link' => '#',
                'menu_icon' => 'money-bill-wave',
                'position' => 3,
            ],
            [
                'menu' => 'Kategorisasi',
                'menu_link' => '#',
                'menu_icon' => 'object-group',
                'position' => 4,
            ],
            [
                'menu' => 'Produk',
                'menu_link' => '#',
                'menu_icon' => 'cubes',
                'position' => 5,
            ],
            [
                'menu' => 'Request Refund',
                'menu_link' => 'viewrefund',
                'menu_icon' => 'list',
                'position' => 6,
            ],
            [
                'menu' => 'Penjualan Lain',
                'menu_link' => '#',
                'menu_icon' => 'shopping-cart',
                'position' => 7,
            ],
            [
                'menu' => 'Pembayaran',
                'menu_link' => 'viewpayment',
                'menu_icon' => 'money-bill-alt',
                'position' => 8,
            ],
            [
                'menu' => 'Pesanan',
                'menu_link' => 'vieworder',
                'menu_icon' => 'list',
                'position' => 9,
            ],
            [
                'menu' => 'Proses Pesanan',
                'menu_link' => 'viewprocessorder',
                'menu_icon' => 'edit',
                'position' => 10,
            ],
            [
                'menu' => 'Kirim Pesanan',
                'menu_link' => 'viewinputshipment',
                'menu_icon' => 'edit',
                'position' => 11,
            ],
            [
                'menu' => 'Konfirmasi Pembayaran',
                'menu_link' => 'paymentconfirmationadmin',
                'menu_icon' => 'file-invoice',
                'position' => 12,
            ],
            [
                'menu' => 'Stok',
                'menu_link' => '#',
                'menu_icon' => 'exchange-alt',
                'position' => 13,
            ],
            [
                'menu' => 'Revisi Order',
                'menu_link' => 'viewreviseorder',
                'menu_icon' => 'pencil-alt',
                'position' => 14,
            ],
            [
                'menu' => 'Ongkos Kirim',
                'menu_link' => '#',
                'menu_icon' => 'truck',
                'position' => 15,
            ],
            [
                'menu' => 'Jastip',
                'menu_link' => '#',
                'menu_icon' => 'shopping-bag',
                'position' => 16,
            ],
        ];

        Menu::insert($data);
    }
}
