<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Shipmethod;

class ShipmentMethodController extends Controller {

    public function shipmethods() {
        $shipmethods = Shipmethod::orderBy('shipmethod_name')
                ->get();

        return view('pages.admin-side.modules.shipmethods.shipmethods')->with([
                    'shipmethods' => $shipmethods
        ]);
    }

    public function addMethods() {
        return view('pages.admin-side.modules.shipmethods.addshipmethod');
    }

    public function saveMethods(Request $request) {
        $this->validate($request, [
            'tipe_metode' => 'required',
            'nama_metode' => 'required',
        ]);

        $shipmethod = Shipmethod::where('shipmethod_name', '=', $request->nama_metode)
                ->where('shipmethod_type', 'like', $request->tipe_metode)
                ->first();
        if ($shipmethod) {
            return back()->with([
                        'err' => 'Metode sudah terdaftar di kota tersebut.'
            ]);
        }

        $shipmethod = new Shipmethod;
        $shipmethod->shipmethod_name = $request->nama_metode;
        $shipmethod->shipmethod_type = $request->tipe_metode;
        $shipmethod->save();

        return redirect('shipmethods')->with([
                    'msg' => 'Metode baru telah tersimpan.'
        ]);
    }

    public function editMethods($id) {
        $shipmethod = Shipmethod::find($id);
        if (!$shipmethod) {
            return back()->with([
                        'err' => 'Tidak ada metode yang ditemukan.'
            ]);
        }

        return view('pages.admin-side.modules.shipmethods.editshipmethod')->with([
                    'shipmethod' => $shipmethod
        ]);
    }

    public function updateMethods(Request $request) {
        $this->validate($request, [
            'tipe_metode' => 'required',
            'nama_metode' => 'required',
        ]);

        $shipmethod = Shipmethod::find($request->shipmethod_id);
        $shipmethod->shipmethod_name = $request->nama_metode;
        $shipmethod->shipmethod_type = $request->tipe_metode;
        $shipmethod->save();

        return redirect('shipmethods')->with([
                    'msg' => 'Metode berhasil diubah.'
        ]);
    }

    public function deleteMethods($id) {
        $shipmethod = Shipmethod::find($id);
        if (!$shipmethod) {
            return redirect('shipmethods')->with([
                        'err' => 'Tidak ada metode yang ditemukan.'
            ]);
        }

        #hapus metode maka hapus seluruh ongkos kirim yang terdaftar
        foreach ($shipmethod->shipcosts as $cost) {
            $cost->delete();
        }

        $shipmethod->delete();

        return redirect('shipmethods')->with([
                    'msg' => 'Method berhasil dihapus.'
        ]);
    }

    public function activateMethod(Request $request) {
        $method_id = $request->method_id;

        $method = Shipmethod::find($method_id);
        $method->is_active = $request->status;
        $method->save();

        $active_state = 'aktifkan';
        if ($request->status == 0) {
            $active_state = 'non' . $active_state;
        }

        return back()->with([
                    'msg' => 'Metode berhasil di ' . $active_state,
        ]);
    }

}
