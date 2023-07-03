<?php

namespace Modules\Inventory\Http\Controllers;

use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Http\Request;
use App\Models\Tenant\Establishment;
use App\Models\Tenant\Company;
use Modules\Inventory\Models\ItemWarehouse;
use Modules\Inventory\Exports\InventoryExport;
use Modules\Inventory\Models\Warehouse;
use Carbon\Carbon;
use Modules\Item\Models\Brand;

class ReportInventoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {


        if ($request->warehouse_id && $request->warehouse_id != 'all') {
            $reports = ItemWarehouse::with(['item', 'item.brand', 'item.color', 'item.size'])
                ->where('warehouse_id', $request->warehouse_id)
                ->whereHas('item', function ($q) use ($request) {
                    if ($request->brand_id && $request->brand_id != 'all') {
                        $q->where([['item_type_id', '01'], ['unit_type_id', '!=', 'ZZ'], ['brand_id', $request->brand_id]]);
                        $q->whereNotIsSet();
                    } else {
                        $q->where([['item_type_id', '01'], ['unit_type_id', '!=', 'ZZ']]);
                    }
                })->latest()->paginate(config('tenant.items_per_page'));
        } else {

            $reports = ItemWarehouse::with(['item', 'item.brand', 'item.color', 'item.size'])
                ->whereHas('item', function ($q) use ($request) {
                    if ($request->brand_id && $request->brand_id != 'all') {
                        $q->where([['item_type_id', '01'], ['unit_type_id', '!=', 'ZZ'], ['brand_id', $request->brand_id]]);
                        $q->whereNotIsSet();
                    } else {
                        $q->where([['item_type_id', '01'], ['unit_type_id', '!=', 'ZZ']]);
                    }
                })->latest()->paginate(config('tenant.items_per_page'));
        }

        $warehouses = Warehouse::select('id', 'description')->get();
        $brands = Brand::select('id', 'name')->get();

        return view('inventory::reports.inventory.index', compact('reports', 'warehouses', 'brands'));
    }

    /**
     * Search
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {

        $reports = ItemWarehouse::with(['item'])->whereHas('item', function ($q) {
            $q->where([['item_type_id', '01'], ['unit_type_id', '!=', 'ZZ']]);
            $q->whereNotIsSet();
        })->latest()->get();

        return view('inventory::reports.inventory.index', compact('reports'));
    }

    /**
     * PDF
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function pdf(Request $request)
    {

        $company = Company::first();
        $establishment = Establishment::first();
        ini_set('max_execution_time', 0);

        if ($request->warehouse_id && $request->warehouse_id != 'all') {
            $reports = ItemWarehouse::with(['item', 'item.brand', 'item.color', 'item.size'])->where('warehouse_id', $request->warehouse_id)->whereHas('item', function ($q) {
                $q->where([['item_type_id', '01'], ['unit_type_id', '!=', 'ZZ']]);
                $q->whereNotIsSet();
            })->latest()->get();
        } else {

            $reports = ItemWarehouse::with(['item', 'item.brand', 'item.color', 'item.size'])->whereHas('item', function ($q) {
                $q->where([['item_type_id', '01'], ['unit_type_id', '!=', 'ZZ']]);
                $q->whereNotIsSet();
            })->latest()->get();
        }

        $pdf = PDF::loadView('inventory::reports.inventory.report_pdf', compact("reports", "company", "establishment"));
        $pdf->setPaper('A4', 'landscape');
        $filename = 'Reporte_Inventario' . date('YmdHis');

        return $pdf->download($filename . '.pdf');
    }

    /**
     * Excel
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function excel(Request $request)
    {
        $company = Company::first();
        $establishment = Establishment::first();

        $query = ItemWarehouse::with(['item', 'item.brand', 'item.color', 'item.size'])
            ->whereHas('item', function ($q) use ($request) {
                $q->where('item_type_id', '01')
                    ->where('unit_type_id', '!=', 'ZZ');

                if ($request->brand_id && $request->brand_id != 'all') {
                    $q->where('brand_id', $request->brand_id);
                }
                $q->whereNotIsSet();
            });

        if ($request->warehouse_id && $request->warehouse_id != 'all') {
            $query->where('warehouse_id', $request->warehouse_id);
        }

        $records = $query->latest()->get();

        // Filtrar registros por marca (si se especifica)
        if ($request->brand_id && $request->brand_id != 'all') {
            $records = $records->filter(function ($record) use ($request) {
                return $record->item->brand_id == $request->brand_id;
            });
        }

        return (new InventoryExport)
            ->records($records)
            ->company($company)
            ->establishment($establishment)
            ->download('ReporteInv' . Carbon::now() . '.xlsx');
    }
}
