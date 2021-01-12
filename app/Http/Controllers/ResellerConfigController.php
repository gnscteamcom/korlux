<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\ResellerConfig;

class ResellerConfigController extends Controller {


  public function viewResellerConfig(){

    $resellerconfig = ResellerConfig::first();

    //menampilkan halaman daftar kategori
    return view('pages.admin-side.modules.resellerconfigs.resellerconfigs')->with('resellerconfig', $resellerconfig);
  }


  public function updateResellerConfig(Request $request){

    $this->validate($request, [
      'silver_upgrade_days' => 'required|min:0|numeric',
      'silver_downgrade_days' => 'required|min:0|numeric',
      'silver_min_upgrade' => 'required|min:0|numeric',
      'silver_min_downgrade' => 'required|min:0|numeric',
      'gold_upgrade_days' => 'required|min:0|numeric',
      'gold_downgrade_days' => 'required|min:0|numeric',
      'gold_min_upgrade' => 'required|min:0|numeric',
      'gold_min_downgrade' => 'required|min:0|numeric',
      'platinum_upgrade_days' => 'required|min:0|numeric',
      'platinum_downgrade_days' => 'required|min:0|numeric',
      'platinum_min_upgrade' => 'required|min:0|numeric',
      'platinum_min_downgrade' => 'required|min:0|numeric',
    ]);

    //Simpan bank baru
    $resellerconfig = ResellerConfig::first();
    $resellerconfig->silver_upgrade_days = $request['silver_upgrade_days'];
    $resellerconfig->silver_downgrade_days = $request['silver_downgrade_days'];
    $resellerconfig->silver_min_upgrade = $request['silver_min_upgrade'];
    $resellerconfig->silver_min_downgrade = $request['silver_min_downgrade'];
    $resellerconfig->gold_upgrade_days = $request['gold_upgrade_days'];
    $resellerconfig->gold_downgrade_days = $request['gold_downgrade_days'];
    $resellerconfig->gold_min_upgrade = $request['gold_min_upgrade'];
    $resellerconfig->gold_min_downgrade = $request['gold_min_downgrade'];
    $resellerconfig->platinum_upgrade_days = $request['platinum_upgrade_days'];
    $resellerconfig->platinum_downgrade_days = $request['platinum_downgrade_days'];
    $resellerconfig->platinum_min_upgrade = $request['platinum_min_upgrade'];
    $resellerconfig->platinum_min_downgrade = $request['platinum_min_downgrade'];
    $resellerconfig->save();

    return redirect('resellerconfig')->with('msg', 'Berhasil mengubah pengaturan reseller.');
  }

}
