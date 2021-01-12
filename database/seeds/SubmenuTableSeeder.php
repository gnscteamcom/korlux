<?php

use Illuminate\Database\Seeder;
use App\Submenu;

class SubmenuTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Submenu::truncate();

        $data = [
            [
                'menu_id' => 1,
                'submenu' => 'Packing Fee',
                'submenu_link' => 'packingfee',
                'submenu_icon' => '',
                'position' => 1
            ],
            [
                'menu_id' => 1,
                'submenu' => 'Free Sample',
                'submenu_link' => 'freesample',
                'submenu_icon' => '',
                'position' => 2
            ],
            [
                'menu_id' => 1,
                'submenu' => 'Konfigurasi Situs',
                'submenu_link' => 'websettings',
                'submenu_icon' => '',
                'position' => 3
            ],
            [
                'menu_id' => 1,
                'submenu' => 'Social Media',
                'submenu_link' => 'othersettings',
                'submenu_icon' => '',
                'position' => 4
            ],
            [
                'menu_id' => 1,
                'submenu' => 'Bank',
                'submenu_link' => 'viewbank',
                'submenu_icon' => '',
                'position' => 5
            ],
            [
                'menu_id' => 1,
                'submenu' => 'Banner',
                'submenu_link' => 'viewbanner',
                'submenu_icon' => '',
                'position' => 6
            ],
            [
                'menu_id' => 1,
                'submenu' => 'Pengguna',
                'submenu_link' => 'viewuser',
                'submenu_icon' => '',
                'position' => 7
            ],
            [
                'menu_id' => 3,
                'submenu' => 'Harga Satuan',
                'submenu_link' => 'viewprice',
                'submenu_icon' => '',
                'position' => 8
            ],
            [
                'menu_id' => 3,
                'submenu' => 'Harga Grosir',
                'submenu_link' => 'viewwholesaleprice',
                'submenu_icon' => '',
                'position' => 9
            ],
            [
                'menu_id' => 3,
                'submenu' => 'Diskon Kupon',
                'submenu_link' => 'viewdiscountcoupon',
                'submenu_icon' => '',
                'position' => 10
            ],
            [
                'menu_id' => 3,
                'submenu' => 'Poin Loyalty',
                'submenu_link' => 'viewdiscountpoint',
                'submenu_icon' => '',
                'position' => 11
            ],
            [
                'menu_id' => 4,
                'submenu' => 'Merk',
                'submenu_link' => 'viewbrand',
                'submenu_icon' => '',
                'position' => 12
            ],
            [
                'menu_id' => 4,
                'submenu' => 'Kategori',
                'submenu_link' => 'viewcategory',
                'submenu_icon' => '',
                'position' => 13
            ],
            [
                'menu_id' => 4,
                'submenu' => 'Sub Kategori',
                'submenu_link' => 'viewsubcategory',
                'submenu_icon' => '',
                'position' => 14
            ],
            [
                'menu_id' => 5,
                'submenu' => 'Lihat Produk',
                'submenu_link' => '#',
                'submenu_icon' => '',
                'position' => 15
            ],
            [
                'menu_id' => 5,
                'submenu' => 'Tambah Produk',
                'submenu_link' => 'addproduct',
                'submenu_icon' => '',
                'position' => 16
            ],
            [
                'menu_id' => 5,
                'submenu' => 'Impor Produk',
                'submenu_link' => 'viewimportproduct',
                'submenu_icon' => '',
                'position' => 17
            ],
            [
                'menu_id' => 5,
                'submenu' => 'Foto Produk',
                'submenu_link' => 'viewproductimage',
                'submenu_icon' => '',
                'position' => 18
            ],
            [
                'menu_id' => 5,
                'submenu' => 'Produk Set',
                'submenu_link' => 'productsets',
                'submenu_icon' => '',
                'position' => 19
            ],
            [
                'menu_id' => 7,
                'submenu' => 'Penjualan Chat',
                'submenu_link' => 'chatsales',
                'submenu_icon' => '',
                'position' => 20
            ],
            [
                'menu_id' => 7,
                'submenu' => 'Penjualan Shopee',
                'submenu_link' => 'shopeesales',
                'submenu_icon' => '',
                'position' => 21
            ],
            [
                'menu_id' => 7,
                'submenu' => 'Penjualan Marketplace',
                'submenu_link' => 'manualsales',
                'submenu_icon' => '',
                'position' => 22
            ],
            [
                'menu_id' => 7,
                'submenu' => 'Histori Penjualan Marketplace',
                'submenu_link' => 'manualsaleshistory',
                'submenu_icon' => '',
                'position' => 23
            ],
            [
                'menu_id' => 13,
                'submenu' => 'Stok Masuk',
                'submenu_link' => 'viewstockin',
                'submenu_icon' => '',
                'position' => 24
            ],
            [
                'menu_id' => 13,
                'submenu' => 'Stok Opname',
                'submenu_link' => 'stockopname',
                'submenu_icon' => '',
                'position' => 25
            ],
            [
                'menu_id' => 13,
                'submenu' => 'Adjustment Stok',
                'submenu_link' => 'stockcorrection',
                'submenu_icon' => '',
                'position' => 26
            ],
            [
                'menu_id' => 13,
                'submenu' => 'Stok Total',
                'submenu_link' => 'stocktotal',
                'submenu_icon' => '',
                'position' => 27
            ],
            [
                'menu_id' => 1,
                'submenu' => 'Link Tambahan',
                'submenu_link' => 'extlink',
                'submenu_icon' => '',
                'position' => 28
            ],
            [
                'menu_id' => 13,
                'submenu' => 'Transfer Stok',
                'submenu_link' => 'stocktransfer',
                'submenu_icon' => '',
                'position' => 29
            ],
            [
                'menu_id' => 13,
                'submenu' => 'Revisi Stok',
                'submenu_link' => 'stockrevise',
                'submenu_icon' => '',
                'position' => 30
            ],
            [
                'menu_id' => 13,
                'submenu' => 'Daftar Revisi Stok',
                'submenu_link' => 'stockreviselist',
                'submenu_icon' => '',
                'position' => 31
            ],
            [
                'menu_id' => 15,
                'submenu' => 'Kecamatan',
                'submenu_link' => 'kecamatans',
                'submenu_icon' => '',
                'position' => 1
            ],
            [
                'menu_id' => 15,
                'submenu' => 'Kota',
                'submenu_link' => 'kotas',
                'submenu_icon' => '',
                'position' => 2
            ],
            [
                'menu_id' => 15,
                'submenu' => 'Ongkos Kirim',
                'submenu_link' => 'shipcosts',
                'submenu_icon' => '',
                'position' => 3
            ],
            [
                'menu_id' => 15,
                'submenu' => 'Metode',
                'submenu_link' => 'shipmethods',
                'submenu_icon' => '',
                'position' => 4
            ],
            [
                'menu_id' => 13,
                'submenu' => 'Stok Balance',
                'submenu_link' => 'stockbalance',
                'submenu_icon' => '',
                'position' => 32
            ],
            [
                'menu_id' => 1,
                'submenu' => 'Reseller',
                'submenu_link' => 'resellerconfig',
                'submenu_icon' => '',
                'position' => 8
            ],
        ];

        Submenu::insert($data);
    }
}
