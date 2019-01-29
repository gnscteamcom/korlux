<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Discountcoupon;
use App\Discountcouponhistory;
use App\Tablestatus;

class DiscountCouponController extends Controller {

    
    public function viewCouponDiscount(){
    
        $discount_coupons = Discountcoupon::orderBy('valid_date', 'desc')->get();
        
        return view('pages.admin-side.modules.discountcoupon.viewdiscountcoupon')->with(array(
            'discount_coupons' => $discount_coupons
        ));
        
    }
    

    public function viewAddCouponDiscount(){
        
        $statuses = Tablestatus::whereBetween('id', [1,4])
                ->get();
        
        return view('pages.admin-side.modules.discountcoupon.adddiscountcoupon')->with([
            'statuses' => $statuses
        ]);
        
    }
    
    
    public function viewEditCouponDiscount($id){
        
        $discount_coupon = Discountcoupon::find($id);
        $statuses = Tablestatus::whereBetween('id', [1,4])
                ->get();
        
        return view('pages.admin-side.modules.discountcoupon.editdiscountcoupon')->with(array(
            'discount_coupon' => $discount_coupon,
            'statuses' => $statuses
        ));
        
    }
    
    
    public function insertCouponDiscount(Request $request){
    
        $this->validate($request,[
            'kode_kupon' => 'required|min:3|max:32',
            'tanggal_berlaku' => 'required',
            'tanggal_berakhir' => 'required',
            'jumlah_berlaku' => 'required',
            'nominal_diskon' => 'required',
            'untuk_pengguna' => 'required'
        ]);
        
        $discount_coupon = DiscountCoupon::whereCoupon_code($request['kode_kupon'])->first();
        if($discount_coupon != null){
            return back()->with(array(
               'err' => 'Kode kupon sudah terdaftar, silahkan gunakan kode yang lain.' 
            ));
        }
        
            
        //Simpan kupon baru
        $discount_coupon = new Discountcoupon;
        $discount_coupon->coupon_code = $request['kode_kupon'];
        $discount_coupon->valid_date = $request['tanggal_berlaku'];
        $discount_coupon->expired_date = $request['tanggal_berakhir'];
        $discount_coupon->available_count = $request['jumlah_berlaku'];
        $discount_coupon->available_for_status = $request['untuk_pengguna'];
        if($request['persentase'] != null){
            if($request['nominal_diskon'] > 100){
                return back()->with(array(
                    'err' => 'Persentase diskon tidak boleh lebih besar dari 100.'
                ));
            }
            $discount_coupon->percentage_discount = $request['nominal_diskon'];
            $discount_coupon->nominal_discount = 0;
        }
        else{
            $discount_coupon->percentage_discount = 0;
            $discount_coupon->nominal_discount = $request['nominal_diskon'];
        }
        $discount_coupon->save();
        
        $discountcouponhistory = new Discountcouponhistory;
        $discountcouponhistory->discountcoupon_id = $discount_coupon->id;
        $discountcouponhistory->user_id = auth()->user()->id;
        $discountcouponhistory->initial_available_count = 0;
        $discountcouponhistory->change_available_count = $request['jumlah_berlaku'];
        $discountcouponhistory->save();

        return redirect('viewdiscountcoupon')->with('msg', 'Berhasil menambah kupon diskon baru..');
            
    }
    
    
    public function deleteCouponDiscount($id){
        
        $discount_coupon = Discountcoupon::find($id);
        
        $discount_coupon->delete();
        
        return back()->with(array('msg' => 'Berhasil menghapus kupon diskon..'));
        
    }
    
    
    public function updateCouponDiscount(Request $request){
    
        $this->validate($request, [
            'nominal_diskon' => 'min:1'
        ]);
            
        $discount_coupon = Discountcoupon::find($request['discountcoupon_id']);
        if(strlen($request['untuk_pengguna']) > 0){
            $discount_coupon->available_for_status = $request['untuk_pengguna'];
        }
        if(strlen($request['tanggal_berlaku']) > 0){
            $discount_coupon->valid_date = $request['tanggal_berlaku'];
        }
        if(strlen($request['tanggal_berakhir']) > 0){
            $discount_coupon->expired_date = $request['tanggal_berakhir'];
        }
        if(strlen($request['jumlah_berlaku']) > 0){
            //masukkin perubahan jumlah berlaku nya ke history
            $discountcouponhistory = new Discountcouponhistory;
            $discountcouponhistory->discountcoupon_id = $discount_coupon->id;
            $discountcouponhistory->user_id = auth()->user()->id;
            $discountcouponhistory->initial_available_count = $discount_coupon->available_count;
            $discountcouponhistory->change_available_count = $request['jumlah_berlaku'];
            $discountcouponhistory->save();
            
            $discount_coupon->available_count = $request['jumlah_berlaku'];
        }
        if(strlen($request['nominal_diskon']) > 0){
            if($request['persentase'] != null){
                if($request['nominal_diskon'] > 100){
                    return back()->with(array(
                        'err' => 'Persentase diskon tidak boleh lebih besar dari 100.'
                    ));
                }
                $discount_coupon->percentage_discount = $request['nominal_diskon'];
                $discount_coupon->nominal_discount = 0;
            }
            else{
                $discount_coupon->percentage_discount = 0;
                $discount_coupon->nominal_discount = $request['nominal_diskon'];
            }
        }
        $discount_coupon->save();
        
        return redirect('viewdiscountcoupon')->with('msg', 'Berhasil memperbarui kupon diskon..');
            
    }
    
}
