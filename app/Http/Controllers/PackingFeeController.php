<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Packingfee;
use App\Packingfeecargo;

class PackingFeeController extends Controller {

    public function viewPackingFee(Request $request) {
        $packing_fee = Packingfee::first();
        $packing_fee_cargo = Packingfeecargo::first();

        return view('pages.admin-side.modules.websettings.packingfee')->with([
                    'packing_fee' => $packing_fee,
                    'packing_fee_cargo' => $packing_fee_cargo
        ]);
    }

    public function updatePackingFee(Request $request) {
        $this->validate($request, [
            'minimal_nominal' => 'required|min:0',
            'packing_fee' => 'required|min:0'
        ]);

        $packing_fee = Packingfee::first();
        $packing_fee->minimal_nominal = $request->minimal_nominal;
        $packing_fee->packing_fee = $request->packing_fee;
        if ($request->is_active) {
            $packing_fee->is_active = $request->is_active;
        } else {
            $packing_fee->is_active = 0;
        }
        $packing_fee->save();

        return back()->with([
                    'msg' => 'Berhasil memperbarui data packing fee.'
        ]);
    }

    public function updatePackingFeeCargo(Request $request) {
        $this->validate($request, [
            'packing_fee_cargo' => 'required|min:0'
        ]);

        $packing_fee_cargo = Packingfeecargo::first();
        if(!$packing_fee_cargo){
            $packing_fee_cargo = new Packingfeecargo;
        }
        $packing_fee_cargo->packing_fee = $request->packing_fee_cargo;
        $packing_fee_cargo->save();

        return back()->with([
                    'msg' => 'Berhasil memperbarui data packing fee kargo.'
        ]);
    }

}
