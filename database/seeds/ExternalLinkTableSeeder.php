<?php

use Illuminate\Database\Seeder;
use App\Externallink;

class ExternalLinkTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Externallink::truncate();
    }
}
