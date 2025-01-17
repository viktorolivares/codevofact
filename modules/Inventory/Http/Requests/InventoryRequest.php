<?php

namespace Modules\Inventory\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class InventoryRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $id = $this->input('id');
        return [
            'item_id' => [
                'required',
            ],
            'warehouse_id' => [
                'required',
            ],
            'quantity' => [
                'required',
                'numeric',
            ],
            'type' => [
                'required',
            ],
        ];
    }
}
