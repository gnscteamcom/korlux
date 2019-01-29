<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Freesample;

class FreeSampleController extends Controller {

    public function viewFreeSample() {
        $freesample = Freesample::first();
        return view('pages.admin-side.modules.websettings.freesample')->with([
                    'freesample' => $freesample
        ]);
    }

    public function updateFreeSample(Request $request) {
        $this->validate($request, [
            'regular_minimum_nominal' => 'required|min:0',
            'silver_minimum_nominal' => 'required|min:0',
            'gold_minimum_nominal' => 'required|min:0',
            'platinum_minimum_nominal' => 'required|min:0'
        ]);

        $freesample = Freesample::first();
        if (!$freesample) {
            $freesample = new Freesample;
        }
        $freesample->regular_minimum_nominal = $request->regular_minimum_nominal;
        $freesample->silver_minimum_nominal = $request->silver_minimum_nominal;
        $freesample->gold_minimum_nominal = $request->gold_minimum_nominal;
        $freesample->platinum_minimum_nominal = $request->platinum_minimum_nominal;
        if (strcmp($request->tick_regular, "on") == 0) {
            $freesample->regular_accumulative = 1;
        } else {
            $freesample->regular_accumulative = 0;
        }
        if (strcmp($request->tick_silver, "on") == 0) {
            $freesample->silver_accumulative = 1;
        } else {
            $freesample->silver_accumulative = 0;
        }
        if (strcmp($request->tick_gold, "on") == 0) {
            $freesample->gold_accumulative = 1;
        } else {
            $freesample->gold_accumulative = 0;
        }
        if (strcmp($request->tick_platinum, "on") == 0) {
            $freesample->platinum_accumulative = 1;
        } else {
            $freesample->platinum_accumulative = 0;
        }
        
        if (strcmp($request->active_regular, "on") == 0) {
            $freesample->active_regular = 1;
        } else {
            $freesample->active_regular = 0;
        }
        if (strcmp($request->active_silver, "on") == 0) {
            $freesample->active_silver = 1;
        } else {
            $freesample->active_silver = 0;
        }
        if (strcmp($request->active_gold, "on") == 0) {
            $freesample->active_gold = 1;
        } else {
            $freesample->active_gold = 0;
        }
        if (strcmp($request->active_platinum, "on") == 0) {
            $freesample->active_platinum = 1;
        } else {
            $freesample->active_platinum = 0;
        }
        $freesample->save();

        return back()->with([
                    'msg' => 'Konfigurasi Free Sampel berhasil diperbarui. Silahkan cek kembali.'
        ]);
    }

}
