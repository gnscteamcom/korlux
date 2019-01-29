<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Product;

class ExportController extends Controller {

    public function viewExportList() {
        $products = Product::orderBy('product_name')
                ->get();

        return view('pages.admin-side.modules.report.viewexportlist')->with([
                    'products' => $products
        ]);
    }

    public function exportList(Request $request) {

        $filter_by = $request['filter_by'];

        if ($filter_by == 1) {
            $this->exportCapital();
        } else if ($filter_by == 2) {
            $this->exportCustomerPoint();
        } else if ($filter_by == 3) {
            $this->exportOrderheader($request['date_start'], $request['date_end']);
        } else if ($filter_by == 4) {
            $this->exportOrderdetail($request['date_start'], $request['date_end']);
        } else if ($filter_by == 5) {
            $this->exportPointHistories($request['date_start'], $request['date_end']);
        } else if ($filter_by == 6) {
            $this->exportPrice($request['date_start'], $request['date_end']);
        } else if ($filter_by == 7) {
            $this->exportProduct($request['date_start'], $request['date_end']);
        } else if ($filter_by == 8) {
            $this->exportStockIn($request['date_start'], $request['date_end']);
        } else if ($filter_by == 9) {
            $this->exportUser($request['date_start'], $request['date_end']);
        } else if ($filter_by == 10) {
            $this->exportLaporanOrder($request['date_one']);
        } else if ($filter_by == 11) {
            $this->exportPricelist();
        } else if ($filter_by == 12) {
            $this->exportHistoryBooking($request->product);
        }
    }

    private function exportCapital() {

        Custom\ExportFunction::exportMasterCapital();
    }

    private function exportCustomerPoint() {

        Custom\ExportFunction::exportMasterPoint();
    }

    private function exportOrderheader($date_start, $date_end) {

        Custom\ExportFunction::exportMasterOrderheader($date_start, $date_end);
    }

    private function exportOrderdetail($date_start, $date_end) {

        Custom\ExportFunction::exportMasterOrderdetail($date_start, $date_end);
    }

    private function exportPointHistories($date_start, $date_end) {

        Custom\ExportFunction::exportMasterPointhistories($date_start, $date_end);
    }

    private function exportPrice($date_start, $date_end) {

        Custom\ExportFunction::exportMasterPrice($date_start, $date_end);
    }

    private function exportProduct() {

        Custom\ExportFunction::exportMasterProduct();
    }

    private function exportStockIn($date_start, $date_end) {

        Custom\ExportFunction::exportMasterStockin($date_start, $date_end);
    }

    private function exportHistoryBooking($product_id) {
        Custom\ExportFunction::exportHistoryBooking($product_id);
    }

    private function exportUser() {

        Custom\ExportFunction::exportMasterUser();
    }

    private function exportLaporanOrder($date_one) {
        Custom\ExportFunction::exportLaporanOrder($date_one);
    }

    private function exportPricelist() {
        Custom\ExportFunction::exportPricelist();
    }

}
