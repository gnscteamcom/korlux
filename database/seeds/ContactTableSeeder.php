<?php

use Illuminate\Database\Seeder;
use App\Contact;

class ContactTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Contact::truncate();

        DB::table('contacts')->insert([
            'owner_name' => 'Koreanluxury',
            'email' => 'koreanluxury@koreanluxury.com',
            'whatsapp' => '08123456789',
            'line' => '@koreanluxury',
            'info' => 'Test Info Buka jam xxxx : xxxx..'
        ]);

    }
}
