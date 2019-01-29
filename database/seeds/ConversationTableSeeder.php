<?php

use Illuminate\Database\Seeder;
use App\Conversation;

class ConversationTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Conversation::truncate();
    }
}
