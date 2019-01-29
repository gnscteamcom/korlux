<?php

namespace App\Http\Controllers\Custom;

class StringFunction {


    public static function generateRandomString($length){

        $random_string = '';

        for($i = 0; $i < $length; $i++){

            $char_randomizer = rand(1,3);

            if($char_randomizer == 1){
                $random_string .= chr(rand(48,57));
            }
            else if($char_randomizer == 2){
                $random_string .= chr(rand(65,90));
            }
            else{
                $random_string .= chr(rand(97,122));
            }
        }

        return $random_string;

    }

    public static function clean($value){
        $value = str_replace('-', '', $value);
        return strtolower(preg_replace('/[^A-Za-z0-9\-]/', '', $value));
    }

}
