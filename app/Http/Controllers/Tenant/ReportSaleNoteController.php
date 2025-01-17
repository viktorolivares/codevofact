<?php

namespace App\Http\Controllers\Tenant;

use App\Models\Tenant\Catalogs\DocumentType;
use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade as PDF;
use App\Exports\SaleNoteExport;
use Illuminate\Http\Request;
use App\Traits\ReportTrait;
use App\Models\Tenant\Establishment;
use App\Models\Tenant\SaleNote;
use App\Models\Tenant\Company;
use Carbon\Carbon;
use App\Http\Resources\Tenant\QuotationCollection;


class ReportSaleNoteController extends Controller
{
    use ReportTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        return view('tenant.reports.sale_notes.index');
    }

    /**
     * Search
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request) {
        $d = null;
        $a = null;


        if ($request->has('d') && $request->has('a')) {


            $d = $request->d;
            $a = $request->a;

            $reports = SaleNote::whereBetween('date_of_issue', [$d, $a])->latest();
        }
        else {

            $reports = SaleNote::latest();
        }

        $reports = $reports->paginate(config('tenant.items_per_page'));



        return view('tenant.reports.sale_notes.index', compact('reports', 'a', 'd'));
    }

    /**
     * PDF
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function pdf(Request $request) {
        $company = Company::first();

        if ($request->has('d') && $request->has('a') && ($request->d != null && $request->a != null)) {
            $d = $request->d;
            $a = $request->a;

            $reports = SaleNote::whereBetween('date_of_issue', [$d, $a])->latest()->get();

        }
        else {

            $reports = SaleNote::latest()->get();
        }


        $pdf = PDF::loadView('tenant.reports.sale_notes.report_pdf', compact("reports", "company"));
        $filename = 'Reporte_Cotizacion'.date('YmdHis');

        return $pdf->download($filename.'.pdf');
    }

    /**
     * Excel
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function excel(Request $request) {
        $company = Company::first();

        if ($request->has('d') && $request->has('a') && ($request->d != null && $request->a != null)) {
            $d = $request->d;
            $a = $request->a;

              $records = SaleNote::whereBetween('date_of_issue', [$d, $a])->latest()->get();

        }
        else {
             $records = SaleNote::latest()->get();
        }

        return (new SaleNoteExport)
                ->records($records)
                ->company($company)
                ->download('ReporteNotaVent'.Carbon::now().'.xlsx');
    }
}
