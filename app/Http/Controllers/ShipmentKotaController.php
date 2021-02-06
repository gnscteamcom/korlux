<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Kota;
use Excel;

class ShipmentKotaController extends Controller {

    public function kotas() {
        $kotas = Kota::get();

        return view('pages.admin-side.modules.kotas.kotas')->with([
                    'kotas' => $kotas
        ]);
    }

    public function addKotas() {
        return view('pages.admin-side.modules.kotas.addkota');
    }

    public function saveKotas(Request $request) {
        $this->validate($request, [
            'kota' => 'required',
        ]);

        $kota = Kota::where('kota', 'like', $request->kota)
                ->first();
        if ($kota) {
            return back()->with([
                        'err' => 'Kota sudah terdaftar.'
            ]);
        }

        $kota = new Kota;
        $kota->kota = $request->kota;
        $kota->save();

        return redirect('kotas')->with([
                    'msg' => 'Kota baru telah tersimpan.'
        ]);
    }

    public function editKotas($id) {
        $kota = Kota::find($id);
        if (!$kota) {
            return back()->with([
                        'err' => 'Tidak ada kota yang ditemukan.'
            ]);
        }

        return view('pages.admin-side.modules.kotas.editkota')->with([
                    'kota' => $kota
        ]);
    }

    public function updateKotas(Request $request) {
        $this->validate($request, [
            'kota' => 'required',
        ]);

        $kota = Kota::find($request->kota_id);
        $kota->kota = $request->kota;
        $kota->save();

        return redirect('kotas')->with([
                    'msg' => 'Kota berhasil diubah.'
        ]);
    }

    public function deleteKotas($id) {
        $kota = Kota::find($id);
        if (!$kota) {
            return redirect('kotas')->with([
                        'err' => 'Tidak ada kota yang ditemukan.'
            ]);
        }

        $kota->delete();

        return redirect('kotas')->with([
                    'msg' => 'Kota berhasil dihapus.'
        ]);
    }

    public function download() {
        Custom\ExportFunction::exportShipmentKota();
    }


    public function import(Request $request){

        $this->validate($request, [
            'file' => 'required'
        ]);

        //inisialisasi data
        $file = $request['file'];
        $filesize = $file->getSize(); //hasil dalam satuan bytes..

        //Validasi ukuran file
        //Ukuran file > 0B
        if($filesize <= 0){
            return back()->with('err', 'File tidak ada data..');
        }

        //Validasi extension file
        //Extension file harus .xls atau .xlsx
        if($file->getClientOriginalExtension() != 'xls' && $file->getClientOriginalExtension() != 'xlsx'  ){
            return back()->with('err', 'File harus .xls atau .xlss');
        }


        echo '<h1>Daftar Import yang Gagal :</h1>';

        Excel::selectSheets('Sheet1')->load($file, function($reader){

            //Baca smua sheets
            $reader->each(function($row){

                if(ctype_space($row->kota) || $row->kota == null || $row->kota == ''){
                    echo '<b style="color:red">Kota Harus Diisi</b><br />';
                }
                else{

                    #kalau ada ID artinya update
                    if($row->id){
                        $kota = Kota::find($row->id);
                        if(!$kota){
                            echo '<b style="color:red">ID ' . $row->id . ' tidak ditemukan</b><br />';
                        }
                    }else{
                        #kalau tidak ada ID, artinya buat baru
                        $kota = new Kota;
                    }

                    #kalau ada shipcost
                    if($kota){
                        $kota->kota = $row->kota;
                        $kota->save();

                        echo '<b style="color:blue">Berhasil mengubah / menambahkan : ' . $row->kota . '</b><br />';
                    }

                }

            });

        });

        echo '<br /><a href="' . url()->previous() . '">Kembali</a>';

    }

}
