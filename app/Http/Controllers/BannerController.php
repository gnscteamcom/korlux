<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Banner;
use Illuminate\Http\Request;
use Image;

class BannerController extends Controller {
    

    public function viewBanner(){
        
        $banners = Banner::all();
        
        return view('pages.admin-side.modules.banner.viewbanner')->with(array(
            'banners' => $banners
        ));
        
    }
    
    
    public function editBanner($id){
        
        $banner = Banner::find($id);
        return view('pages.admin-side.modules.banner.editbanner')->with('banner', $banner);
        
    }
    

    public function viewAddBanner(){

        $banners = Banner::count();
        
        if($banners >= 5){
            return redirect('viewbanner')->with(array(
                'msg' => 'Jumlah banner sudah maksimum'
            ));
        }
        else{
            return view('pages.admin-side.modules.banner.addbanner');
        }
        
    }
    
    
    public function deleteBanner($id){
        
        $banner = Banner::find($id);
        
        if(file_exists($banner->image_path)){
            unlink($banner->image_path);
        }
        $banner->delete();
        
        return back()->with(array(
            'err' => 'Berhasil menghapus banner'
        ));
        
    }
    
    
    public function uploadBanner(Request $request){
        
        $this->validate($request, [
            'banner' => 'image',
            'tautan' => 'required'
        ]);
        
        $banner = $request->file('banner');
        
        $file_name = date('ymdhis');
        
        if($request->hasFile('banner')){
            
            if($this->validateImage($banner)){
                return redirect('addbanner')->with(array(
                    'err' => 'Ukuran banner harus di bawah 500KB dengan file .jpg .png .gif'
                ));
            }
            else{
                $banner->move('storage/upload/banners/', $file_name . '.' . $banner->getClientOriginalExtension());
            }
            
            $this->resizeImage($file_name, $banner);
            
            $new_banner = new Banner;
            $new_banner->image_path = 'storage/upload/banners/' . $file_name . '.' . $banner->getClientOriginalExtension();
            $new_banner->redirect_link = $request['tautan'];
            $new_banner->save();
            
        }
        
        return redirect('viewbanner')->with(array(
            'msg' => 'Berhasil menambah banner'
        ));
        
    }
    
    
    
    private function validateImage($foto){

        $size = $foto->getClientSize();

        //max file size adalah 200 KB
        if($size > 512000){
            return true;
        }
        
        $ext = $foto->getClientOriginalExtension();
        
        if($ext != "jpg" && $ext != "gif" && $ext != "png" ){
            return true;
        }
        
        return false;
        
    }
    
    
    private function resizeImage($file_name, $file){
        $image = Image::make('storage/upload/banners/' . $file_name . '.' . $file->getClientOriginalExtension())->resize(450,450);
        $image->save('storage/upload/banners/' . $file_name . '.' . $file->getClientOriginalExtension());
    }
    
    
    public function updateBanner(Request $request){

        $this->validate($request, [
            'tautan' => 'required'
        ]);

        $banner = Banner::find($request['banner_id']);
        $banner->redirect_link = $request['tautan'];
        $banner->save();
        
        return redirect('viewbanner')->with(array(
            'msg' => 'Berhasil memperbarui banner'
        ));
        
    }
    
}
