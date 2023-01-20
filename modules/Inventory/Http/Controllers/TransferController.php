<?php

namespace Modules\Inventory\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Inventory\Http\Resources\TransferCollection;
use Modules\Inventory\Http\Resources\TransferResource;
use Modules\Inventory\Models\Inventory;
use Modules\Inventory\Traits\InventoryTrait;
use Modules\Inventory\Models\ItemWarehouse;
use Modules\Inventory\Models\InventoryTransfer;
use Modules\Inventory\Http\Requests\TransferRequest;

use Modules\Item\Models\ItemLot;

class TransferController extends Controller
{
    use InventoryTrait;

    public function index()
    {
        return view('inventory::transfers.index');
    }

    public function create()
    {
        return view('inventory::transfers.form');

    }

    public function columns()
    {
        return [
            'created_at' => 'Fecha de emisión',
        ];
    }

    public function records(Request $request)
    {
        if($request->column)
        {
            $records = InventoryTransfer::with(['warehouse','warehouse_destination', 'inventory'])->where('created_at', 'like', "%{$request->value}%")->latest();
        }
        else{
            $records = InventoryTransfer::with(['warehouse','warehouse_destination', 'inventory'])->latest();

        }


        return new TransferCollection($records->paginate(config('tenant.items_per_page')));
    }


    public function tables()
    {
        return [
            'warehouses' => $this->optionsWarehouse()
        ];
    }

    public function record($id)
    {
        $record = new TransferResource(Inventory::findOrFail($id));

        return $record;
    }


    public function destroy($id)
    {

        DB::connection('tenant')->transaction(function () use ($id) {

            $record = Inventory::findOrFail($id);

            $origin_inv_kardex = $record->inventory_kardex->first();
            $destination_inv_kardex = $record->inventory_kardex->last();

            $destination_item_warehouse = ItemWarehouse::where([['item_id',$destination_inv_kardex->item_id],['warehouse_id', $destination_inv_kardex->warehouse_id]])->first();
            $destination_item_warehouse->stock -= $record->quantity;
            $destination_item_warehouse->update();

            $origin_item_warehouse = ItemWarehouse::where([['item_id',$origin_inv_kardex->item_id],['warehouse_id', $origin_inv_kardex->warehouse_id]])->first();
            $origin_item_warehouse->stock += $record->quantity;
            $origin_item_warehouse->update();

            $record->inventory_kardex()->delete();
            $record->delete();

        });


        return [
            'success' => true,
            'message' => 'Traslado eliminado con éxito'
        ];



    }

    public function stock ($item_id, $warehouse_id)
    {

       $row = ItemWarehouse::where([['item_id', $item_id],['warehouse_id', $warehouse_id]])->first();

       return [
           'stock' => ($row) ? $row->stock : 0
       ];

    }

    public function store(TransferRequest $request)
    {
        $result = DB::connection('tenant')->transaction(function () use ($request) {

            $row = InventoryTransfer::create([
                'description' => $request->description,
                'warehouse_id' => $request->warehouse_id,
                'warehouse_destination_id' => $request->warehouse_destination_id,
                'quantity' =>  count( $request->items ),
            ]);

            foreach ($request->items as $it)
            {
                $inventory = new Inventory();
                $inventory->type = 2;
                $inventory->description = 'Traslado';
                $inventory->item_id = $it['id'];
                $inventory->warehouse_id = $request->warehouse_id;
                $inventory->warehouse_destination_id = $request->warehouse_destination_id;
                $inventory->quantity = $it['quantity'];
                $inventory->inventories_transfer_id = $row->id;

                $inventory->save();

                foreach ($it['lots'] as $lot){

                    if($lot['has_sale']){
                        $item_lot = ItemLot::findOrFail($lot['id']);
                        $item_lot->warehouse_id = $inventory->warehouse_destination_id;
                        $item_lot->update();
                    }

                }
            }

            return  [
                'success' => true,
                'message' => 'Traslado creado con éxito'
            ];
        });

        return $result;


    }


    public function items($warehouse_id)
    {
        return [
            'items' => $this->optionsItemWareHousexId($warehouse_id),
        ];
    }








}
