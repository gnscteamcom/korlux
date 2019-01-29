<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Point;
use App\Pointconfig;
use App\Pointhistory;
use App\Customerpoint;

class DiscountPointController extends Controller {

    public function viewDiscountPoint() {
        $config = Pointconfig::first();

        $points = Point::select('id', 'minimal_amount', 'maximal_amount', 'point_percentage')
                ->orderBy('minimal_amount')
                ->get();

        $total_user = Customerpoint::where('total_point', '>', 0)
                ->count();

        return view('pages.admin-side.modules.discountpoint.viewdiscountpoint')->with(array(
                    'points' => $points,
                    'config' => $config,
                    'total_user' => $total_user,
        ));
    }

    public function toggleIsActivate(Request $request) {
        $config = Pointconfig::first();
        $config->is_active = !$config->is_active;
        $config->save();

        $msg = 'nonaktifkan';
        if ($config->is_active) {
            $msg = 'aktifkan';
        }

        return back()->with([
                    'msg' => 'Berhasil di ' . $msg
        ]);
    }

    public function refreshPoint(Request $request) {
        $users = Customerpoint::where('total_point', '>', 0)
                ->update([
            'total_point' => 0
        ]);

        $point_histories = Pointhistory::whereNull('deleted_at')
                ->update([
            'deleted_at' => \Carbon\Carbon::now()->toDateTimeString()
        ]);

        return back()->with([
                    'msg' => 'Seluruh poin customer telah dikosongkan.'
        ]);
    }

    public function addPoint() {
        return view('pages.admin-side.modules.discountpoint.adddiscountpoint');
    }

    public function editPoint($id) {
        $point = Point::find($id);

        return view('pages.admin-side.modules.discountpoint.editdiscountpoint')->with([
                    'point' => $point
        ]);
    }

    public function insertPoint(Request $request) {
        $this->validate($request, [
            'nominal_minimal' => 'required',
            'nominal_maksimal' => 'required',
            'persentase_poin' => 'required',
        ]);

        $point = new Point;
        $point->minimal_amount = $request->nominal_minimal;
        $point->maximal_amount = $request->nominal_maksimal;
        $point->point_percentage = $request->persentase_poin;
        $point->save();

        return redirect('viewdiscountpoint')->with([
                    'msg' => 'Berhasil menambahkan poin baru.'
        ]);
    }

    public function updatePoint(Request $request) {
        $this->validate($request, [
            'nominal_minimal' => 'required',
            'nominal_maksimal' => 'required',
            'persentase_poin' => 'required',
        ]);

        $point = Point::find($request->point_id);
        $point->minimal_amount = $request->nominal_minimal;
        $point->maximal_amount = $request->nominal_maksimal;
        $point->point_percentage = $request->persentase_poin;
        $point->save();

        return redirect('viewdiscountpoint')->with([
                    'msg' => 'Berhasil mengubah data poin.'
        ]);
    }

    public function deletePoint($id) {
        $point = Point::find($id);
        if ($point) {
            $point->delete();
        }
        return back()->with([
                    'msg' => 'Point berhasil dihapus.'
        ]);
    }

}
