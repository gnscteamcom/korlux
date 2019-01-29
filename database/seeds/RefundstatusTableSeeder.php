<?php

use Illuminate\Database\Seeder;
use App\Refundstatus;

class RefundstatusTableSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        Refundstatus::truncate();

        $data = [
            [
                'status' => 'PENGAJUAN REFUND'
            ],
            [
                'status' => 'SEDANG DIPROSES'
            ],
            [
                'status' => 'DITOLAK'
            ],
            [
                'status' => 'SUDAH DIREFUND'
            ],
        ];

        Refundstatus::insert($data);
    }

}
