<?php

namespace App\Http\Controllers\Custom;

use App\Packingfee;
use App\Packingfeecargo;

class PackingFeeFunction {

    public static function getPackingFee($grand_total, $ship_method = 0) {
        if (!auth()->check()) {
            return 0;
        }

        #kalau sicepat kargo wajib pakai packing fee.
        if ($ship_method != 6) {
            #kalau yang request bukan reseller, tidak dikenakan packing fee
            $user = auth()->user();
            if ($user->usersetting) {
                if ($user->usersetting->status_id <= 1) {
                    return 0;
                }
            } else {
                return 0;
            }

            #cari packing fee yang is_active = 1 dan belanjanya lebih kecil dari minimal nominal
            $packing_fee = Packingfee::where('minimal_nominal', '>=', $grand_total)
                    ->where('is_active', '=', 1)
                    ->first();
            if (!$packing_fee) {
                return 0;
            }
        } else {
            $packing_fee_cargo = Packingfeecargo::first();
            return $packing_fee_cargo->packing_fee;
        }

        return 0;
    }

}
