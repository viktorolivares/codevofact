<?php
namespace App\Http\Controllers\Tenant;

use App\Models\Tenant\Catalogs\Country;
use App\Models\Tenant\Catalogs\Department;
use App\Models\Tenant\Catalogs\District;
use App\Models\Tenant\Catalogs\Province;
use App\Models\Tenant\Establishment;
use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\EstablishmentRequest;
use App\Http\Resources\Tenant\EstablishmentResource;
use App\Http\Resources\Tenant\EstablishmentCollection;
use App\Models\Tenant\Catalogs\IdentityDocumentType;
use App\Models\Tenant\Warehouse;
use App\Models\System\Configuration;
use Illuminate\Http\Request;


class EstablishmentController extends Controller
{
    public function index()
    {
        return view('tenant.establishments.index');
    }

    public function create()
    {
        return view('tenant.establishments.form');
    }

    public function code()
    {
        $code = Establishment::max('code');
        $code = $code + 1;
        $code = str_pad($code,4,"0",STR_PAD_LEFT);

        return $code;
    }

    public function tables()
    {
        $countries = Country::whereActive()->orderByDescription()->get();
        $departments = Department::whereActive()->orderByDescription()->get();
        $provinces = Province::whereActive()->orderByDescription()->get();
        $districts = District::whereActive()->orderByDescription()->get();
        $identity_document_types = IdentityDocumentType::whereActive()->get();

        $configuration = Configuration::first();
        $api_service_token = $configuration->token_apiruc == 'false' ? config('configuration.api_service_token') : $configuration->token_apiruc;

        return compact('countries', 'departments', 'provinces', 'districts','api_service_token','identity_document_types');
    }

    public function record($id)
    {
        $record = new EstablishmentResource(Establishment::findOrFail($id));

        return $record;

    }

    public function store(EstablishmentRequest $request)
    {
        $id = $request->input('id');
        $establishment = Establishment::firstOrNew(['id' => $id]);

        if ($request->hasFile('file') && $request->file('file')->isValid()) {
            $request->validate(['file' => 'mimes:jpeg,png,jpg|max:1024']);
            $file = $request->file('file');
            $ext = $file->getClientOriginalExtension();
            $filename = time() . '.' . $ext;
            $file->storeAs('public/uploads/logos', $filename);
            $path = 'storage/uploads/logos/' . $filename;
            $request->merge(['logo' => $path]);
        }

        $establishment->fill($request->all());
        $establishment->save();

        if(!$id) {
            $warehouse = new Warehouse();
            $warehouse->establishment_id = $establishment->id;
            $warehouse->description = 'Almacén - '.$establishment->description;
            $warehouse->save();
        }

        return [
            'success' => true,
            'message' => ($id)?'Establecimiento actualizado':'Establecimiento registrado'
        ];
    }

    public function columns()
    {
        return [
            'description' => 'Descripción',
            'number' => 'RUC',
            'code' => 'Código'
        ];
    }

    public function records(Request $request)
    {
        //$records = Establishment::all();

        $records = Establishment::where($request->column, 'like', "%{$request->value}%");

        //return new EstablishmentCollection($records);

        return new EstablishmentCollection($records->paginate(config('tenant.items_per_page')));
    }

    public function destroy($id)
    {
        $establishment = Establishment::findOrFail($id);
        $establishment->delete();

        return [
            'success' => true,
            'message' => 'Establecimiento eliminado con éxito'
        ];
    }
}
