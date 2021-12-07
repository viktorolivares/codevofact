<?php

namespace Modules\Item\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Item\Models\Color;
use Modules\Item\Http\Resources\ColorCollection;
use Modules\Item\Http\Resources\ColorResource;
use Modules\Item\Http\Requests\ColorRequest;

class ColorController extends Controller
{

    public function index()
    {
        return view('item::colors.index');
    }


    public function columns()
    {
        return [
            'name' => 'Nombre',
        ];
    }

    public function records(Request $request)
    {
        $records = Color::where($request->column, 'like', "%{$request->value}%")->orderBy('name', 'asc');

        return new ColorCollection($records->paginate(config('tenant.items_per_page')));
    }


    public function record($id)
    {
        $record = Color::findOrFail($id);

        return $record;
    }

    public function store(ColorRequest $request)
    {
        $id = $request->input('id');
        $color = Color::firstOrNew(['id' => $id]);
        $color->fill($request->all());
        $color->save();


        return [
            'success' => true,
            'message' => ($id)?'Color editado con éxito':'Color registrado con éxito',
            'data' => $color
        ];

    }

    public function destroy($id)
    {
        try {

            $color = Color::findOrFail($id);
            $color->delete();

            return [
                'success' => true,
                'message' => 'Color eliminado con éxito'
            ];

        } catch (Throwable $e) {

            return ($e->getCode() == '23000') ? ['success' => false,'message' => "El color esta siendo usado por otros registros, no puede eliminar"] : ['success' => false,'message' => "Error inesperado, no se pudo eliminar el Color"];

        }

    }




}
