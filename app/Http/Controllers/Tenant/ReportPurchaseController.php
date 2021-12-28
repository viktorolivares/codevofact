<?php

namespace App\Http\Controllers\Tenant;

use App\Models\Tenant\Catalogs\DocumentType;
use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade as PDF;
use App\Exports\PurchaseExport;
use Illuminate\Http\Request;
use App\Traits\ReportTrait;
use App\Models\Tenant\Establishment;
use App\Models\Tenant\Purchase;
use App\Models\Tenant\Company;
use Carbon\Carbon;

class ReportPurchaseController extends Controller
{
    use ReportTrait;
}
