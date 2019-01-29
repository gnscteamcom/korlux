<?php

use Illuminate\Database\Seeder;
use App\Tablestatus;

class TableStatusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Tablestatus::truncate();
        
        DB::table('tablestatuses')->insert([
            'id' => 1,
            'status' => 'Regular'
        ]);
        
        DB::table('tablestatuses')->insert([
            'id' => 2,
            'status' => 'Silver'
        ]);
        
        DB::table('tablestatuses')->insert([
            'id' => 3,
            'status' => 'Gold'
        ]);
        
        DB::table('tablestatuses')->insert([
            'id' => 4,
            'status' => 'Platinum'
        ]);
        
        DB::table('tablestatuses')->insert([
            'id' => 11,
            'status' => 'Belum Dibayar'
        ]);
        
        DB::table('tablestatuses')->insert([
            'id' => 12,
            'status' => 'Pembayaran Sedang Dicek'
        ]);
        
        DB::table('tablestatuses')->insert([
            'id' => 13,
            'status' => 'Pembayaran Diterima'
        ]);
        
        DB::table('tablestatuses')->insert([
            'id' => 14,
            'status' => 'Sedang Dikirim'
        ]);
        
        DB::table('tablestatuses')->insert([
            'id' => 15,
            'status' => 'Sudah Dikirim'
        ]);
        
        DB::table('tablestatuses')->insert([
            'id' => 16,
            'status' => 'Batal'
        ]);
        
        DB::table('tablestatuses')->insert([
            'id' => 17,
            'status' => 'Batal Otomatis dari Sistem'
        ]);
        
    }
}
