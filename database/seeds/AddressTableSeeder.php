<?php

use Illuminate\Database\Seeder;
use App\Address;

class AddressTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Address::truncate();
        
        DB::table('addresses')->insert([
            'address_1' => 'Jalan Lorem Ipsum',
            'address_2' => 'Nomor 999',
            'address_3' => 'Kecamatan Lorem',
            'address_4' => 'Lorem Ipsum, Dolor sit amet.. 99999',
        ]);
        
    }
}
