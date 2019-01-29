<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract
{
    
    use SoftDeletes;
    use Authenticatable, CanResetPassword;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';
    
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'email', 'password'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];
    
    
    public function usersetting(){
        
        return $this->hasOne('App\Usersetting');
        
    }
    
    
    public function customeraddresses(){
        
        return $this->hasMany('App\Customeraddress')->orderBy('address_name');
        
    }
    
    
    public function dropships(){
        
        return $this->hasMany('App\Dropship')->orderBy('dropship_name');
        
    }
    
    
    public function customerpoint(){
        
        return $this->hasOne('App\Customerpoint');
        
    }
    
    
    public function pointhistories(){
        
        return $this->hasMany('App\Pointhistory')->orderBy('created_at', 'desc');
        
    }
    
}
