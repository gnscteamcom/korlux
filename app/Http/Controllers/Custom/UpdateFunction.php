<?php

namespace App\Http\Controllers\Custom;

class UpdateFunction {
    
    
    public static function useOldOrNew($new_value, $old_value){
        
        if(strlen(strip_tags($new_value)) > 0){
            return $new_value;
        }
        else{
            return $old_value;
        }
        
    }
    
}
