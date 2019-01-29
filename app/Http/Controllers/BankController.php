<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Bank;

class BankController extends Controller {

    
    public function viewBank(){
    
        $banks = Bank::orderBy('bank_name')->get();
        
        //menampilkan halaman daftar kategori
        return view('pages.admin-side.modules.bank.viewbank')->with('banks', $banks);
        
    }
    

    public function viewAddBank(){
        
        //menampilkan halaman untuk menambahkan kategori
        return view('pages.admin-side.modules.bank.addbank');
        
    }
    
    
    public function editBank($id){
        
        $bank = Bank::find($id);
        
        return view('pages.admin-side.modules.bank.editbank')->with(array(
            'bank' => $bank
        ));
        
    }
    
    
    public function insertBank(Request $request){
    
        $this->validate($request,[
            'nama_bank' => 'required|min:3|max:20',
            'rekening_bank' => 'required|min:3|max:20',
            'nama_rekening' => 'required|min:3|max:20'
        ]);
        
        //Simpan bank baru
        $bank = new Bank;
        $bank->bank_name = $request['nama_bank'];
        $bank->bank_account = $request['rekening_bank'];
        $bank->bank_account_name = $request['nama_rekening'];
        $bank->save();

        return redirect('viewbank')->with('msg', 'Berhasil menambah bank baru..');
            
    }
    
    
    public function deleteBank($id){
        
        //Hapus bank
        $bank = Bank::find($id);
        
        $bank->delete();
        
        return redirect('viewbank')->with(array('msg' => 'Berhasil menghapus deskripsi bank..'));
        
    }
    
    
    public function updateBank(Request $request){
    
        $this->validate($request, [
            'nama_bank' => 'min:3|max:24',
            'rekening_bank' => 'min:3|max:20',
            'nama_rekening' => 'min:3|max:48'
        ]);
            
        $bank = Bank::find($request['bank_id']);
        $bank->bank_name = $request['nama_bank'];
        $bank->bank_account = $request['rekening_bank'];
        $bank->bank_account_name = $request['nama_rekening'];
        $bank->save();
        
        return redirect('viewbank')->with('msg', 'Berhasil memperbarui deskripsi bank..');
        
    }
    
}
