<?php

namespace App\Http\Controllers\Custom;

use App\Point;

class PointFunction {

    public static function calculatePoint($grand_total) {

            $point_added = 0;

            $point = Point::where('minimal_amount', '<=', $grand_total)
                    ->where('maximal_amount', '>=', $grand_total)
                    ->select('point_percentage')
                    ->first();

            $point_added = $grand_total * $point->point_percentage / 100;

            return $point_added;
    }

}
