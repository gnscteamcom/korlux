<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Orderheader;
use App\Orderdetail;
use App\Usersetting;
use App\Customeraddress;
use App\Contact;
use PDF;


class ReportController extends Controller {
    
    #SET BARCODE STYLE
    private static $barcode_style = array(
        'position' => '',
        'align' => 'C',
        'stretch' => false,
        'fitwidth' => true,
        'cellfitalign' => '',
        'hpadding' => 'auto',
        'vpadding' => 'auto',
        'fgcolor' => array(0, 0, 0),
        'bgcolor' => false, //array(255,255,255),
        'stretchtext' => 10
    );

    public function viewFilterReport(){
        
        return view('pages.admin-side.modules.report.viewfilterreport');
        
    }
    
    
    public function generateReport(Request $request){
        
        $filter_by = $request['filter_by'];
        
        
        if($filter_by == 1){
            $this->generateSalesReport($request['date_start'], $request['date_end']);
        }
        else if($filter_by == 2){
            $this->generateWeeklySalesReport($request['year']);
        }
        else if($filter_by == 3){
            $this->generateMonthlySalesReport($request['year']);
        }
        else if($filter_by == 4){
            $this->generateCurrentStockReport();
        }
        else if($filter_by == 5){
            $this->generateProfitReport($request['date_start'], $request['date_end']);
        }
        else if($filter_by == 6){
            $this->generateCurrentStockValueReport();
        }
        
    }    
    
    
    
    public function PrintDO(Request $request){
        
        $this->validate($request, [
            'print' => 'required'
        ]);
        
        //Generate Judul Page
        PDF::SetPageOrientation('L', true, 20);
        PDF::SetMargins(0, 0, 0, true);
        PDF::SetTitle('DO');
        
        
        $i = 1;
        $invoice = "<br />&nbsp;&nbsp;&nbsp;Order yang sudah diprint <br /><br />";

        $print_arrays = $request['print'];
        sort($print_arrays);
        
        foreach(array_chunk($print_arrays, 4) as $four_array){
            PDF::AddPage();

            //Generate isi report
            $tbl = 
                    '<table cellspacing="20">'
                    . '<tr>'
                        . '<td>';
                
            foreach(array_chunk($four_array, 2) as $two_array){
                $tbl2 = '<table style="width:100%;">'
                        . '<tr>';
                
                foreach($two_array as $order_id){
                    
                    $tbl2 .= '<td style="width:50%;">';
            
                    //Ambil data
                    $order_header = Orderheader::find($order_id);
                    $tbl2 .= $this->generateTableInvoice($order_header, $i++);
                    $tbl2 .= '</td>';
                                        
                    #Simpan nomor invoicenumber buat ditampilkan di terakhir.
                    $invoice .= '&nbsp;&nbsp;&nbsp;' . $order_header->invoicenumber . '<br />';
                }
                $tbl2 .= '</tr>'
                        . '</table>';
                PDF::SetFontSize(13, true);
                PDF::writeHTML($tbl2, true, false, false, false, 'C');
            }
            $tbl .= '</td>'
                    . '</tr>'
                    . '</table>';
            PDF::SetFontSize(13, true);
            PDF::writeHTML($tbl, true, false, false, false, 'C');
        }


        $invoice = '<div style="margin:20px; text-align:left; color:#757575;">' . $invoice . "</div>";
        PDF::AddPage();
        PDF::SetFontSize(13, true);
        PDF::writeHTML($invoice, true, false, false, false, 'C');

        //Close dan tampilkan hasilnya
        PDF::Output('PrintDO.pdf');
        
        
    }
    
    
    
    public function printAllShippedDO(Request $request){
        

        //Generate Judul Page
        PDF::SetPageOrientation('L', true, 20);
        PDF::SetMargins(0, 0, 0, true);
        PDF::SetTitle('DO');
        
        #SET BARCODE STYLE
        $barcode_style = array(
            'position' => '',
            'align' => 'C',
            'stretch' => false,
            'fitwidth' => true,
            'cellfitalign' => '',
            'hpadding' => 'auto',
            'vpadding' => 'auto',
            'fgcolor' => array(0, 0, 0),
            'bgcolor' => false, //array(255,255,255),
            'stretchtext' => 4
        );
        
        
        $i = 1;
        $counter = 1;
        $invoice = "<br />&nbsp;&nbsp;&nbsp;Order yang sudah diprint <br /><br />";

        $orderheaders = Orderheader::whereStatus_id(13)
                ->whereNull('shipment_invoice')
                ->orderBy('created_at')
                ->get();
        
        foreach($orderheaders->chunk(4) as $order_header_chunk){
            PDF::AddPage();

            //Generate isi report
            $tbl = 
                    '<table cellspacing="20">'
                    . '<tr>'
                        . '<td>';
            
            foreach($order_header_chunk->chunk(2) as $order_header_chunk_two){
                $tbl2 = '<table style="width:100%;">'
                        . '<tr>';
                
                foreach($order_header_chunk_two as $order_header){
                    
                    $tbl2 .= '<td style="width:50%;">';
            
                    //Ambil data
                    $tbl2 .= $this->generateTableInvoice($order_header, $i++);
                    $tbl2 .= '</td>';
                                        
                    #Simpan nomor invoicenumber buat ditampilkan di terakhir.
                    $invoice .= '&nbsp;&nbsp;&nbsp;' . $order_header->invoicenumber . '<br />';
                }
                $tbl2 .= '</tr>'
                        . '</table>';
                PDF::SetFontSize(13, true);
                PDF::writeHTML($tbl2, true, false, false, false, 'C');
            }
            $tbl .= '</td>'
                    . '</tr>'
                    . '</table>';
            PDF::SetFontSize(13, true);
            PDF::writeHTML($tbl, true, false, false, false, 'C');
        }


        $invoice = '<div style="margin:20px; text-align:left; color:#757575;">' . $invoice . "</div>";
        PDF::AddPage();
        PDF::SetFontSize(13, true);
        PDF::writeHTML($invoice, true, false, false, false, 'C');

        //Close dan tampilkan hasilnya
        PDF::Output('PrintDO.pdf');
        
        
    }
    
    private static function generateTableInvoice($order_header, $i) {
        $tbl2 = '';
        if (!$order_header->shipment_invoice) {

            $shipment_method = $order_header->shipment_method;


            //kalau 0 artinya ambil dari alamat yang ada di usersetting
            if ($order_header->customeraddress_id == 0) {
                $user_setting = Usersetting::whereUser_id($order_header->user_id)->first();
                $to = $user_setting->first_name . ' ' . $user_setting->last_name;
                $to_phone = $user_setting->hp;

                $to_address = $user_setting->alamat . '<br />'
                        . $user_setting->kecamatan . ', '
                        . $user_setting->kota . '<br />'
                        . $user_setting->kodepos;
            }
            //kalau bukan 0, ambil dari customeraddress
            else {
                $customer_address = Customeraddress::where('id', '=', $order_header->customeraddress_id)
                                ->withTrashed()->first();
                $to = $customer_address->first_name . ' ' . $customer_address->last_name;
                $to_phone = $customer_address->hp;

                $to_address = $customer_address->alamat . '<br />'
                        . $customer_address->kecamatan . ', '
                        . $customer_address->kota . '<br />'
                        . $customer_address->kodepos;
            }



            //set pengirimnya kalau dropship_id = 0, berarti tidak dropship
            if ($order_header->dropship_id == 0) {
                $contact = Contact::first();
                $from = $contact->owner_name;
                $from_phone = $contact->whatsapp;
            } else {
                $from = $order_header->dropship->name;
                $from_phone = $order_header->dropship->hp;
            }


            $product_name = '';
            $order_details = Orderdetail::whereOrderheader_id($order_header->id)->get();

            //print free sampel kalau ada
            if ($order_header->freesample_qty > 0) {
                $product_name .= $order_header->freesample_qty . ' x Free Sampel.<br /><br />';
            }

            foreach ($order_details as $order_detail) {
                $product = $order_detail->productDelete($order_detail->product_id);

                //cek kalau produk nya paketan, yang diprint adalah anaknya saja.
                if ($product->is_set) {
                    $product_sets = \App\Productset::where('set_id', '=', $product->id)->get();
                    foreach ($product_sets as $set) {
                        if ($order_detail->qty == 1)
                            $product_name .= $set->product->product_name . '<br />';
                        else
                            $product_name .= $order_detail->qty . ' x ' . $set->product->product_name . '<br />';
                    }
                }else {
                    if ($order_detail->qty == 1)
                        $product_name .= $product->product_name . '<br />';
                    else
                        $product_name .= $order_detail->qty . ' x ' . $product->product_name . '<br />';
                }
            }

            #kalau ada shopeesales, siapin kode resi shopee
            $resi_tambahan = '';
            if($order_header->resi_otomatis){
                $resi_tambahan = '<tr>'
                        . '<td>'
                        . $order_header->resi_otomatis
                        . '</td>'
                        . '</tr>';
            }
            if ($order_header->shopeesales) {
                $resi_tambahan = '<tr>'
                        . '<td>'
                        . $order_header->shopeesales->shopee_resi
                        . '</td>'
                        . '</tr>';
            }

            #BIKIN BARCODE
            if (strlen($order_header->barcode) > 0) {
                $barcode_y_position = PDF::getY();
                PDF::write1DBarcode($order_header->barcode, 'C128', 2 + ( 165 * ($i % 2 == 0 ? 1 : 0) ), 3 + $barcode_y_position, '', 14, 0.4, ReportController::$barcode_style, 1);
                PDF::SetAutoPageBreak(TRUE, 0);
            }
            
            #CEK apakah ada invoice marketplace
            $marketplace_invoice = '';
            if($order_header->ordermarketplace){
                $marketplace_invoice = '<tr><td style="font-size:9px; font-weight:bold;">' . $order_header->ordermarketplace->marketplace_invoice . '</td></tr>';
            }

            //bikin detail
            $tbl2 = $tbl2
                    . '<table align="left" width="100%" style="margin-top:20px; color:#757575;">'
                    . '<tr>'
                    . '<td width="39%" style="border:.1px dashed #757575; vertical-align:top; padding-top:10px;">'
                    . '<table border="0" style=" padding-left:10px;">'
                    . '<tr>'
                    . '<td>'
                    . '<strong>' . $shipment_method . '</strong>'
                    . '</td>'
                    . '</tr>'
                    . '<tr>'
                    . '<td>'
                    . '</td>'
                    . '</tr>'
                    . '<tr>'
                    . '<td>'
                    . '</td>'
                    . '</tr>'
                    . '<tr>'
                    . '<td style="font-size:9px; font-weight:bold;">'
                    . '</td>'
                    . '</tr>'
                    . '<tr>'
                    . '<td>'
                    . $order_header->invoicenumber
                    . '</td>'
                    . '</tr>'
                    . $marketplace_invoice
                    . '<tr>'
                    . '<td>'
                    . 'FROM: ' . $from . ''
                    . '</td>'
                    . '</tr>'
                    . '<tr>'
                    . '<td>'
                    . '' . $from_phone . ''
                    . '</td>'
                    . '</tr>'
                    . '<tr>'
                    . '<td style="font-size:9px; font-weight:bold;">'
                    . htmlspecialchars($order_header->note)
                    . '</td>'
                    . '</tr>';

            #kalau ada shopeesales
            if ($order_header->shopeesales) {
                $tbl2 = $tbl2 . '<tr>'
                        . '<td style="font-size:9px; font-weight:bold;">'
                        . $order_header->shopeesales->shopee_invoice_number
                        . '</td>'
                        . '</tr>'
                        . '<tr>'
                        . '<td style="font-size:9px; font-weight:bold;">'
                        . $order_header->shopeesales->send_before
                        . '</td>'
                        . '</tr>';
            }
            
            $tbl2 = $tbl2 . '</table>'
                    . '</td>'
                    . '<td width="59%" style="border:.1px dashed #757575; vertical-align:top; padding:10px;">'
                    . '<table style=" padding-left:10px;">'
                    . '<tr>'
                    . '<td>'
                    . '<strong>' . $shipment_method . '</strong>'
                    . '</td>'
                    . '</tr>'
                    . '<tr>'
                    . '<td>'
                    . '<strong>Rp. ' . number_format($order_header->shipment_cost, 0, ',', '.') . '</strong>'
                    . '</td>'
                    . '</tr>'
                    . $resi_tambahan
                    . '<tr>'
                    . '<td>'
                    . 'KEPADA:'
                    . '</td>'
                    . '</tr>'
                    . '<tr>'
                    . '<td>'
                    . '' . $to . ''
                    . '</td>'
                    . '</tr>'
                    . '<tr>'
                    . '<td>'
                    . 'Telp: ' . $to_phone . ''
                    . '</td>'
                    . '</tr>'
                    . '<tr>'
                    . '<td>'
                    . '' . $to_address . ''
                    . '</td>'
                    . '</tr>'
                    . '</table>'
                    . '</td>'
                    . '</tr>';

            $tbl2 .= '<tr>'
                    . '<td width="98%;" style="border:.1px dashed #757575; vertical-align:top;">'
                    . '<table style=" padding-left:10px;">'
                    . '<tr>'
                    . '<td>'
                    . $order_header->invoicenumber
                    . '</td>'
                    . '</tr>'
                    . '<tr>'
                    . '<td style="font-size:8px;">'
                    . '' . $product_name . ''
                    . '</td>'
                    . '</tr>'
                    . '</table>'
                    . '</td>'
                    . '</tr>'
                    . ' </table>';

            #jadikan dia sudah print
            $order_header->is_print = 1;
            $order_header->save();
        }
        
        return $tbl2;
    }

}
