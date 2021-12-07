<?php

namespace Modules\Item\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Item\Models\Size;
use Modules\Item\Http\Resources\SizeCollection;
use Modules\Item\Http\Resources\SizeResource;
use Modules\Item\Http\Requests\SizeRequest;

class SizeController extends Controller
{

    public function index()
    {
        return view('item::sizes.index');
    }


    public function columns()
    {
        return [
            'name' => 'Nombre',
        ];
    }

    public function records(Request $request)
    {
        $records = Size::where($request->column, 'like', "%{$request->value}%");

        return new SizeCollection($records->paginate(config('tenant.items_per_page')));
    }


    public function record($id)
    {
        $record = Size::findOrFail($id);

        return $record;
    }

    public function store(SizeRequest $request)
    {
        $id = $request->input('id');
        $size = Size::firstOrNew(['id' => $id]);
        $size->fill($request->all());
        $size->save();


        return [
            'success' => true,
            'message' => ($id)?'Talla editada con éxito':'Talla registrada con éxito',
            'data' => $size
        ];

    }

    public function destroy($id)
    {
        try {

            $size = Size::findOrFail($id);
            $size->delete();

            return [
                'success' => true,
                'message' => 'Talla eliminada con éxito'
            ];

        } catch (Throwable $e) {

            return ($e->getCode() == '23000') ? ['success' => false,'message' => "La Talla esta siendo usado por otros registros, no puede eliminar"] : ['success' => false,'message' => "Error inesperado, no se pudo eliminar la Talla"];

        }

    }




}
