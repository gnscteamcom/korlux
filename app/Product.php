<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{

    use SoftDeletes;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'products';
    
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];
    
    public function scopeSets($query, $id){
        return Productset::where('set_id', '=', $id)->get();
    }
    
    public function brand(){
        
        return $this->belongsTo('App\Brand')->withTrashed();
        
    }
    
    
    public function category(){
        
        return $this->belongsTo('App\Category')->withTrashed();
        
    }
    
    
    public function subcategory(){
        
        return $this->belongsTo('App\Subcategory')->withTrashed();
        
    }
    
    
    public function prices(){
        
        return $this->hasMany('App\Price');
        
    }
    
    
    public function capital(){
        
        return $this->hasOne('App\Capital');
        
    }
    
    
    public function stockins(){
        
        return $this->hasMany('App\Stockin');
        
    }
    
    
    public function productimages(){
        
        return $this->hasMany('App\Productimage');
        
    }
    
    
    public function productclasses(){
        
        if(auth()->check()){
            return $this->hasMany('App\Productclass')->where('productclasses.userstatus_id', '=', auth()->user()->usersetting->status_id);
        }
        return $this->hasMany('App\Productclass')->where('productclasses.userstatus_id', '=', 1);
        
    }
    
    public function currentprice(){
        return $this->belongsTo('App\Price')->withTrashed();
    }
    
    
    public function scopeCurrentprice(){
        
        if(auth()->check()){
            $user_status = auth()->user()->usersetting->status_id;
        }
        else{
            $user_status = 1;
        }
        
        $query = $this->hasMany('App\Price')
                ->orderBy('created_at', 'desc');
        
        switch($user_status){
            case 1:
                $query = $query->select('regular_price as price')->withTrashed()->first();
                break;
            case 2:
                $query = $query->select('reseller_1 as price')->withTrashed()->first();
                break;
            case 3:
                $query = $query->select('reseller_2 as price')->withTrashed()->first();
                break;
            default:
                $query = $query->select('regular_price as price')->withTrashed()->first();
                break;
        }
        
        if($query != null){
            $result = 0;
        }
        else{
            $result = $query->price;
        }
        
        return $result;
    }
    
}