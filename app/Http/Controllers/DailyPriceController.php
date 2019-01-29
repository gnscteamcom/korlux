<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

class DailyPriceController extends Controller {

    public function updateDailyPrice() {
        Custom\PriceFunction::updateDailyPrice();

        return back();
    }

}
