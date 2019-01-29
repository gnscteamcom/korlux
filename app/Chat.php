<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'chats';
    
    
    public function user(){
        
        return $this->belongsTo('App\User');
        
    }
    
    
    public function conversation(){
        
        return $this->belongsTo('App\Conversation');
        
    }
}
