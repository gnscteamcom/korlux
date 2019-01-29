<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

class LoginController extends Controller {
    
    
    public function viewLogin(){
        
        if(auth()->check()){
            if(auth()->user()->is_admin){
                return redirect('websettings');
            }
            
            return redirect('home');
        }
        else{
            //menampilkan halaman login
            return view('pages.admin-side.login.login');
        }
        
    }
    
}
