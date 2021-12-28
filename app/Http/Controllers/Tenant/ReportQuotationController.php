<?php

namespace App\Http\Controllers\Tenant;

use App\Models\Tenant\Catalogs\DocumentType;
use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade as PDF;
use App\Exports\QuotationExport;
use Illuminate\Http\Request;
use App\Traits\ReportTrait;
use App\Models\Tenant\Establishment;
use App\Models\Tenant\Quotation;
use App\Models\Tenant\Company;
use Carbon\Carbon;
use App\Http\Resources\Tenant\QuotationCollection;


class ReportQuotationController extends Controller
{
    use ReportTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {

        return view('tenant.reports.quotations.index');
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

            $reports = Quotation::whereBetween('date_of_issue', [$d, $a])->latest();
        }
        else {

            $reports = Quotation::latest();
        }

        $reports = $reports->paginate(config('tenant.items_per_page'));

        return view('tenant.reports.quotations.index', compact('reports', 'a', 'd'));
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

            $reports = Quotation::whereBetween('date_of_issue', [$d, $a])->latest()->get();

        }
        else {

            $reports = Quotation::latest()->get();

        }

        $pdf = PDF::loadView('tenant.reports.quotations.report_pdf', compact("reports", "company"));
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

              $records = Quotation::whereBetween('date_of_issue', [$d, $a])->latest()->get();
        }
        else {
             $records = Quotation::latest()->get();
        }

        return (new QuotationExport)
                ->records($records)
                ->company($company)
                ->download('ReporteCotiz'.Carbon::now().'.xlsx');
    }
}
