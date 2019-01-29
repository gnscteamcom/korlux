<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Socialmedia;
use Illuminate\Http\Request;

class OtherSettingsController extends Controller {

    
    public function viewSettings(){
        
        //buat list social media untuk check box
        $social_media_lists = array();
        $key = array();
        $key['id'] = '1';
        $key['name'] = 'LINE';
        $key['base'] = "line.me/ti/p/";
        $key['icon'] = 'line';
        array_push($social_media_lists, $key);
        $key = array();
        $key['id'] = '2';
        $key['name'] = 'Instagram';
        $key['base'] = 'instagram.com';
        $key['icon'] = 'instagram';
        array_push($social_media_lists, $key);
        
        
        //Ambil social_id yang tersimpan
        $social_medias = Socialmedia::all();
        $social_id = array();
        foreach($social_medias as $social_media){
            array_push($social_id, $social_media->social_id);
        }
        
        
        return view('pages.admin-side.modules.websettings.othersettings')->with(array(
            'social_media_lists' => $social_media_lists,
            'social_id' => $social_id,
            'social_media' => $social_medias
        ));
        
    }
    
    
    public function updateSocialMedia(Request $request){
        
        
        //Delete seluruh isi tabel socialmedia
        Socialmedia::truncate();
        
        //Insert seluruh yang dicentang
        if($request['social_media']){
            $i = 0;
            foreach($request['social_media'] as $social_media_id){
                $temp = explode('/', $social_media_id);
                $social_media = new Socialmedia;
                $social_media->social_id = $temp[0];
                $social_media->social_name = $temp[1];
                $social_media->social_base_link = $temp[2];
                $social_media->social_additional_link = $request['additional_link'][$i];
                $social_media->social_icon = $temp[3];
                $social_media->save();
                $i++;
            }
        }
        
        return back()->with(array('msg' => 'Berhasil memperbarui Jejaring Sosial..'));
        
        
    }
    
    
}
