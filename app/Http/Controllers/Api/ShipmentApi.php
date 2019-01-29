<?php

namespace App\Http\Controllers\Api;

use App\Kecamatan;
use App\Kota;
use App\Shipcost;
use App\Shipmethod;

class ShipmentApi {

    public static function kecamatans() {
        $kecamatans = Kecamatan::join('kotas', 'kotas.id', '=', 'kecamatans.kota_id')
                ->orderBy('kecamatans.kecamatan')
                ->orderBy('kotas.kota')
                ->select('kecamatans.id', 'kecamatans.kecamatan', 'kotas.kota')
                ->get();

        $kecamatan_data[] = null;
        $i = 0;
        foreach ($kecamatans as $data) {
            $kecamatan_data[$i] = [
                'id' => $data->id,
                'kecamatan' => $data->kecamatan . ', ' . $data->kota
            ];
            $i++;
        }

        $response = [
            'code' => 000,
            'msg' => 'Success retrieving data.',
            'count' => $kecamatans->count(),
            'data' => $kecamatan_data
        ];
        
        return $response;
    }

    public static function methods($kecamatan_id) {
        $shipmethods = Shipmethod::join('shipcosts', 'shipcosts.shipmethod_id', '=', 'shipmethods.id')
                ->join('kecamatans', 'kecamatans.id', '=', 'shipcosts.kecamatan_id')
                ->where('shipcosts.kecamatan_id', '=', $kecamatan_id)
                ->where('shipcosts.price', '>', 0)
                ->where('shipmethods.is_active', '=', 1)
                ->select('shipmethods.id', 'shipmethods.shipmethod_name', 'shipmethods.shipmethod_type')
                ->orderBy('shipmethods.shipmethod_name')
                ->orderBy('shipmethods.shipmethod_type')
                ->get();

        $methods_data[] = null;
        $i = 0;
        foreach ($shipmethods as $method) {
            $methods_data[$i] = [
                'id' => $method->id,
                'ship_method' => $method->shipmethod_name . ' - ' . $method->shipmethod_type
            ];
            $i++;
        }

        $response = [
            'code' => 000,
            'msg' => 'Success retrieving data.',
            'count' => $shipmethods->count(),
            'data' => $methods_data,
            'no_method_en' => 'No Shipping Method Available.',
            'no_method_id' => 'Tidak Ada Metode Pengiriman.',
        ];

        return $response;
    }

    public static function shipcosts($kecamatan_id, $ship_method) {
        $ship_cost = Shipcost::where('kecamatan_id', '=', $kecamatan_id)
                ->where('shipmethod_id', '=', $ship_method)
                ->first();

        $cost = 0;
        if ($ship_cost) {
            $cost = $ship_cost->price;
        }

        $response = [
            'code' => 000,
            'msg' => 'Success retrieving data.',
            'data' => $cost,
        ];
        
        return json_encode($response);
    }
    
    public static function insurance($ship_method, $total) {
        switch($ship_method){
            case 1:
            case 2:
            case 3:
                $insurance = intval(5000 + (0.2 * intval($total) / 100));
                break;
            case 4:
            case 5:
                $insurance = intval(5000 + (0.2 * intval($total) / 100));
                break;
            default:
                $insurance = 0;
                break;
        }
        
        $response = [
            'code' => 000,
            'msg' => 'Success retrieving data.',
            'data' => $insurance,
        ];

        return json_encode($response);
    }

}
