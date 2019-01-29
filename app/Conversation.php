<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'conversations';
    
    
    public function user(){
        
        return $this->belongsTo('App\User');
        
    }
    
    
    public function chats(){
        
        return $this->hasMany('App\Chat');
        
    }
}
