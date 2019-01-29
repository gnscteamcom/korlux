<?php

namespace App\Http\Controllers\Custom;

use App\Freesample;

class FreeSampleFunction {

    public static function getFreeSampleMinimumNominal() {
        $freesample = Freesample::first();
        if (!$freesample) {
            return 0;
        }

        $is_active = FreeSampleFunction::isActiveFreeSample();

        if ($is_active) {
            switch (auth()->user()->usersetting->status_id) {
                case 1: return $freesample->regular_minimum_nominal;
                case 2: return $freesample->silver_minimum_nominal;
                case 3: return $freesample->gold_minimum_nominal;
                case 4: return $freesample->platinum_minimum_nominal;
                default: return 0;
            }
        } else {
            return 0;
        }
    }

    public static function isActiveFreeSample() {
        $freesample = Freesample::first();
        $is_active = 0;
        if (!$freesample) {
            return $is_active;
        }
        switch (auth()->user()->usersetting->status_id) {
            case 1: $is_active = $freesample->active_regular;
                break;
            case 2: $is_active = $freesample->active_silver;
                break;
            case 3: $is_active = $freesample->active_gold;
                break;
            case 4: $is_active = $freesample->active_platinum;
                break;
        }
        return $is_active;
    }

    public static function isFreeSampleAccumulative() {
        $freesample = Freesample::first();
        if (!$freesample) {
            return 0;
        }

        switch (auth()->user()->usersetting->status_id) {
            case 1: return $freesample->regular_accumulative;
            case 2: return $freesample->silver_accumulative;
            case 3: return $freesample->gold_accumulative;
            case 4: return $freesample->platinum_accumulative;
            default: return 0;
        }
    }

    public static function countFreeSample($minimum_nominal, $is_accumulative, $is_active, $item_total) {
        $free_sample_count = 0;
        if(!$is_active){
            return $free_sample_count;
        }
        
        if ($minimum_nominal != 0) {
            $free_sample_count = $item_total / $minimum_nominal;
        }
        if (!$is_accumulative) {
            if ($free_sample_count > 1) {
                $free_sample_count = 1;
            }
        }
        return floor($free_sample_count);
    }

}
