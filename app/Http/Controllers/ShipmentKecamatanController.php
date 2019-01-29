<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Custom\StringFunction;
use App\Kecamatan;
use App\Kota;
use App\Shipcost;
use App\Shipmethod;
use Excel;

class ShipmentKecamatanController extends Controller {

    public function kecamatans() {
        $kecamatans = Kecamatan::join('kotas', 'kotas.id', '=', 'kecamatans.kota_id')
                ->orderBy('kotas.kota')
                ->select('kecamatans.*')
                ->get();

        #hitung total data
        $grab_count = [];
        $total_data = $kecamatans->count();
        $multiplier = 500;
        array_push($grab_count, 'Download Template Saja');
        for($i = 0; $i < ceil($kecamatans->count() / $multiplier); $i++){
            array_push($grab_count, ($i * $multiplier) . '-' . (($i+1) * $multiplier));
        }

        return view('pages.admin-side.modules.kecamatans.kecamatans')->with([
                    'kecamatans' => $kecamatans,
                    'grabs' => $grab_count,
        ]);
    }

    public function addKecamatans() {
        $kotas = Kota::orderBy('kota')
                ->get();
        $methods = Shipmethod::where('is_active', '=', 1)->orderBy('shipmethod_name')->get();
        return view('pages.admin-side.modules.kecamatans.addkecamatan')->with([
            'kotas' => $kotas,
            'methods' => $methods,
        ]);
    }

    public function saveKecamatans(Request $request) {
        $this->validate($request, [
            'kota' => 'required',
            'kecamatan' => 'required'
        ]);

        $kecamatan = Kecamatan::where('kota_id', '=', $request->kota)
                ->where('kecamatan', 'like', $request->kecamatan)
                ->first();
        if ($kecamatan) {
            return back()->with([
                        'err' => 'Kecamatan sudah terdaftar di kota tersebut.'
            ]);
        }

        $kecamatan = new Kecamatan;
        $kecamatan->kota_id = $request->kota;
        $kecamatan->kecamatan = $request->kecamatan;
        $kecamatan->save();

        #simpan ongkirnya
        $i = 0;
        foreach($request->metode as $metode){
            $shipcost = new Shipcost;
            $shipcost->kecamatan_id = $kecamatan->id;
            $shipcost->shipmethod_id = $metode;
            $shipcost->eta = 1;
            $shipcost->price = $request->ongkir[$i++];
            $shipcost->save();
        }

        return redirect('kecamatans')->with([
                    'msg' => 'Kecamatan baru telah tersimpan.'
        ]);
    }

    public function editKecamatans($id) {
        $kotas = Kota::orderBy('kota')
                ->get();
        $kecamatan = Kecamatan::find($id);
        if (!$kecamatan) {
            return back()->with([
                        'err' => 'Tidak ada kecamatan yang ditemukan.'
            ]);
        }

        $methods = Shipmethod::where('is_active', '=', 1)->orderBy('shipmethod_name')->get();
        $methods_data = [];
        foreach($methods as $method){
            $shipcost = Shipcost::where('kecamatan_id', '=', $id)
            ->where('shipmethod_id', '=', $method->id)
            ->first();

            $cost = 0;
            if($shipcost){
                $cost = $shipcost->price;
            }

            $data = [
                'id' => $shipcost->id,
                'name' => $method->shipmethod_name . ' - ' . $method->shipmethod_type,
                'price' => $cost,
            ];
            array_push($methods_data, $data);
        }

        return view('pages.admin-side.modules.kecamatans.editkecamatan')->with([
                    'kotas' => $kotas,
                    'kecamatan' => $kecamatan,
                    'methods' => $methods_data
        ]);
    }

    public function updateKecamatans(Request $request) {
        $this->validate($request, [
            'kecamatan' => 'required',
            'kota' => 'required'
        ]);

        $kecamatan = Kecamatan::find($request->kecamatan_id);
        $kecamatan->kota_id = $request->kota;
        $kecamatan->kecamatan = $request->kecamatan;
        $kecamatan->save();

        #update ongkirnya
        $i = 0;
        foreach($request->metode as $metode){
            $shipcost = Shipcost::find($metode);
            $shipcost->price = $request->ongkir[$i++];
            $shipcost->save();
        }

        return redirect('kecamatans')->with([
                    'msg' => 'Kecamatan berhasil diubah.'
        ]);
    }

    public function deleteKecamatans($id) {
        $kecamatan = Kecamatan::find($id);
        if (!$kecamatan) {
            return redirect('kecamatans')->with([
                        'err' => 'Tidak ada kecamatan yang ditemukan.'
            ]);
        }

        $kecamatan->delete();

        return redirect('kecamatans')->with([
                    'msg' => 'Kecamatan berhasil dihapus.'
        ]);
    }

    public function download($range = 0) {
        $skip = 0;
        if(strcasecmp($range, 'Download Template Saja') != 0){
            $range = explode('-', $range);
            $skip = $range[0];
            $range = $range[1] - $skip;
        }
        Custom\ExportFunction::exportShipmentKecamatan($skip, $range);
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

                $kota_id = $row->kota_id;
                if($kota_id == 0){

                    #cek dulu ada kotanya ga
                    $kota = Kota::where('kota', 'like', trim($row->kota))->first();
                    if(!$kota){
                        $kota = new Kota;
                        $kota->kota = $row->kota;
                        $kota->save();
                        echo '<b style="color:blue">Membuat Kota Baru: ' . $row->kota . '</b><br />';
                    }

                    $kota_id = $kota->id;
                }

                if($kota_id < 0){
                    echo '<b style="color:red">ID Kota Harus Diisi</b><br />';
                }
                else if(ctype_space($row->kecamatan) || $row->kecamatan == null || $row->kecamatan == ''){
                    echo '<b style="color:red">Kecamatan Harus Diisi</b><br />';
                }
                else{

                    #kalau ada ID artinya update
                    if($row->id){
                        $kecamatan = Kecamatan::find($row->id);
                        if(!$kecamatan){
                            echo '<b style="color:red">ID ' . $row->id . ' tidak ditemukan</b><br />';
                        }
                    }else{
                        #cek dulu kecamtan udah ada belum
                        $kecamatan = Kecamatan::where('kota_id', '=', $kota_id)
                                    ->where('kecamatan', 'like', trim($row->kecamatan))
                                    ->first();
                        if(!$kecamatan){
                            #kalau tidak ada ID, artinya buat baru
                            $kecamatan = new Kecamatan;
                            $kecamatan->kode = '';
                            $kecamatan->kota_id = $kota_id;
                                $kecamatan->kecamatan = $row->kecamatan;
                                $kecamatan->save();

                            echo '<b style="color:blue">Buat Kecamatan: ' . $row->kecamatan . ' baru</b><br />';
                        }

                    }

                    #tambahin shipcost kalau diinput
                    $methods = Shipmethod::where('is_active', '=', 1)->orderBy('shipmethod_name')->get();
                    foreach($methods as $method){
                        $column_name = StringFunction::clean($method->shipmethod_name . '_' . $method->shipmethod_type);
                        $cost = $row[$column_name];
                        $shipcost = Shipcost::where('kecamatan_id', '=', $kecamatan->id)
                                    ->where('shipmethod_id', '=', $method->id)
                                    ->first();

                        #kalau tidak ada, tambah baru
                        if(!$shipcost){
                            $shipcost = new Shipcost;
                            $shipcost->kecamatan_id = $kecamatan->id;
                            $shipcost->shipmethod_id = $method->id;
                            $shipcost->eta = 1;
                        }
                        $shipcost->price = $cost;
                        $shipcost->save();

                        echo '<b style="color:blue">Berhasil menambahkan ongkir: ' . $cost . ' untuk: ' . $shipcost->shipmethod->shipmethod_name . ' - ' . $shipcost->shipmethod->shipmethod_type . '</b><br />';
                    }

                    echo '<br>';
                }

            });

        });

        echo '<br /><a href="' . url()->previous() . '">Kembali</a>';

    }

}
