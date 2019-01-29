<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

class ChatController extends Controller {

    
    public function viewChatAdmin(){
    
        return view('pages.admin-side.modules.chat.chat-admin');
        
    }
    
}
