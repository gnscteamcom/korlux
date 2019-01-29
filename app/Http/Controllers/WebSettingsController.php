<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Contact;
use App\Address;
use App\Term;
use Illuminate\Http\Request;

class WebSettingsController extends Controller {

    
    public function viewSettings(){
        
        $contact = Contact::first();
        $address = Address::first();
        $term = Term::first();
        
        return view('pages.admin-side.modules.websettings.viewsettings')->with(array(
            'contact' => $contact,
            'address' => $address,
            'term' => $term
        ));
        
    }
    
    
    public function updateContact(Request $request){

        $this->validate($request, [
            'nama_pemilik' => 'max:64',
            'email' => 'email|max:48',
            'call_center' => 'max:24',
            'line' => 'max:32',
        ]);
        
        //Insert contact
        $contact = Contact::first();

        $new = new Contact;

        if(!$contact){
            $new->owner_name = $request['nama_pemilik'];
            $new->email = $request['email'];
            $new->whatsapp = $request['call_center'];
            $new->line = $request['line'];
            $new->info = $request['info'];
        }
        else{
            $new->owner_name = Custom\UpdateFunction::useOldOrNew($request['nama_pemilik'], $contact->owner_name);
            $new->email = Custom\UpdateFunction::useOldOrNew($request['email'], $contact->email);
            $new->whatsapp = Custom\UpdateFunction::useOldOrNew($request['call_center'], $contact->whatsapp);
            $new->line = Custom\UpdateFunction::useOldOrNew($request['line'], $contact->line);
            $new->info = Custom\UpdateFunction::useOldOrNew($request['info'], $contact->info);
        }

        $new->save();

        if($contact){
           $contact->delete(); 
        }

        return back()->with(array('msg' => 'Berhasil memperbarui informasi kontak...'));
            
    }
    
    
    public function updateAddress(Request $request){
        
        $this->validate($request, [
            'alamat1' => 'required|max:40',
            'alamat2' => 'max:40',
            'alamat3' => 'max:40',
            'alamat4' => 'max:40'
        ]);
        
        $address = Address::first();
        $new = new Address;

        if(!$address){
            $new->address_1 = $request['alamat1'];
            $new->address_2 = $request['alamat2'];
            $new->address_3 = $request['alamat3'];
            $new->address_4 = $request['alamat4'];
        }
        else{
            $new->address_1 = Custom\UpdateFunction::useOldOrNew($request['alamat1'], $address->address_1);
            $new->address_2 = Custom\UpdateFunction::useOldOrNew($request['alamat2'], $address->address_2);
            $new->address_3 = Custom\UpdateFunction::useOldOrNew($request['alamat3'], $address->address_3);
            $new->address_4 = Custom\UpdateFunction::useOldOrNew($request['alamat4'], $address->address_4);
        }

        $new->save();
        
        if($address){
            $address->delete();
        }

        return back()->with(array('msg' => 'Berhasil memperbarui alamat..'));
            
    }
    
    
    public function updateTerm(Request $request){
        
        $this->validate($request, [
            'kebijakan_harga' => 'min:1',
            'pembayaran' => 'min:1',
            'pemesanan' => 'min:1',
            'konfirmasi_pembayaran' => 'min:1',
            'pengiriman' => 'min:1',
            'pengembalian' => 'min:1',
            'faq' => 'min:1',
            'cara_membeli' => 'min:1',
            'reseller' => 'min:1'
        ]);
        
            
        //Insert apabila belum ada
        $term = Term::first();
        
        $new = new Term;
        
        if(!$term){
            $new->pricing_policy = $request['kebijakan_harga'];
            $new->order = $request['pemesanan'];
            $new->payment = $request['pembayaran'];
            $new->payment_confirmation = $request['konfirmasi_pembayaran'];
            $new->shipment = $request['pengiriman'];
            $new->return = $request['pengembalian'];
            $new->faq = $request['faq'];
            $new->howtobuy = $request['cara_membeli'];
            $new->reseller = $request['reseller'];
        }
        else{
            $new->pricing_policy = Custom\UpdateFunction::useOldOrNew($request['kebijakan_harga'], $term->pricing_policy);
            $new->order = Custom\UpdateFunction::useOldOrNew($request['pemesanan'], $term->order);
            $new->payment = Custom\UpdateFunction::useOldOrNew($request['pembayaran'], $term->payment);
            $new->payment_confirmation = Custom\UpdateFunction::useOldOrNew($request['konfirmasi_pembayaran'], $term->payment_confirmation);
            $new->shipment = Custom\UpdateFunction::useOldOrNew($request['pengiriman'], $term->shipment);
            $new->return = Custom\UpdateFunction::useOldOrNew($request['pengembalian'], $term->return);
            $new->faq = Custom\UpdateFunction::useOldOrNew($request['faq'], $term->faq);
            $new->howtobuy = Custom\UpdateFunction::useOldOrNew($request['cara_membeli'], $term->howtobuy);
            $new->reseller = Custom\UpdateFunction::useOldOrNew($request['reseller'], $term->reseller);
        }
        $new->save();
        
        if($term){
            $term->delete();
        }

        return back()->with(array('msg' => 'You have successfully update Terms and Conditions..'));
            
        
    }
    
}
