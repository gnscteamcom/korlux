<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Shipcost;
use App\Kecamatan;
use Excel;

class ShipmentCostController extends Controller {

    public function costs() {
        $kecamatans = Kecamatan::join('kotas', 'kotas.id', '=', 'kecamatans.kota_id')
                ->orderBy('kotas.kota')
                ->select('kecamatans.*')
                ->get();
        return view('pages.admin-side.modules.costs.costs')->with([
            'kecamatans' => $kecamatans,
        ]);
    }

    public function download($id) {
        Custom\ExportFunction::exportShipmentCost($id);
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

                if(ctype_space($row->metode_id) || $row->metode_id == null || $row->metode_id == ''){
                    echo '<b style="color:red">ID Metode Harus Diisi</b><br />';
                }
                else if(!is_numeric($row->metode_id)){
                    echo '<b style="color:red">ID Metode Harus Angka</b><br />';
                }
                else if(!is_numeric($row->ongkos_kirim)){
                    echo '<b style="color:red">Ongkos Kirim Harus Angka</b><br />';
                }
                else{

                    #kalau ada ID artinya update
                    if($row->id){
                        $shipcost = Shipcost::find($row->id);
                        if(!$shipcost){
                            echo '<b style="color:red">ID ' . $row->id . ' tidak ditemukan</b><br />';
                        }
                    }else{
                        #kalau tidak ada ID, artinya buat baru
                        $shipcost = new Shipcost;
                        $shipcost->kecamatan_id = $row->kecamatan_id;
                        $shipcost->shipmethod_id = $row->metode_id;
                    }

                    #kalau ada shipcost
                    if($shipcost){
                        $shipcost->price = $row->ongkos_kirim;
                        $shipcost->eta = 0;
                        $shipcost->save();

                        echo '<b style="color:blue">Berhasil mengubah / menambahkan : ' . $row->kecamatan . ' - ' . $row->metode . ' seharga: ' . $row->ongkos_kirim . '</b><br />';
                    }

                }

            });

        });

        echo '<br /><a href="' . url()->previous() . '">Kembali</a>';

    }

}
