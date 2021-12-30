<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tenant\Material;
use App\Http\Resources\Tenant\MaterialCollection;
use App\Http\Requests\Tenant\MaterialRequest;

class MaterialController extends Controller
{

    public function index()
    {
        return view('tenant.materials.index');
    }


    public function columns()
    {
        return [
            'name' => 'Nombre',
            'description' => 'Descripción',
        ];
    }

    public function records(Request $request)
    {
        $records = Material::where($request->column, 'like', "%{$request->value}%");

        return new MaterialCollection($records->paginate(config('tenant.items_per_page')));
    }


    public function record($id)
    {
        $record = Material::findOrFail($id);

        return $record;
    }

    public function store(MaterialRequest $request)
    {
        $id = $request->input('id');
        $material = Material::firstOrNew(['id' => $id]);
        $material->fill($request->all());
        $material->save();


        return [
            'success' => true,
            'message' => ($id)?'Material editado con éxito':'Material registrado con éxito',
            'data' => $material
        ];

    }

    public function destroy($id)
    {
        try {

            $material = Material::findOrFail($id);
            $material->delete();

            return [
                'success' => true,
                'message' => 'Material eliminado con éxito'
            ];

        } catch (Throwable $e) {

            return ($e->getCode() == '23000') ? ['success' => false,'message' => "El material esta siendo usado por otros registros, no puede eliminar"] : ['success' => false,'message' => "Error inesperado, no se pudo eliminar el Material"];

        }

    }




}
