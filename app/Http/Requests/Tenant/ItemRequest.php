<?php

namespace App\Http\Requests\Tenant;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ItemRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $id = $this->input('id');

        return [
            'internal_id' => [
                'nullable',
                Rule::unique('tenant.items')->ignore($id),
            ],
            'description' => [
                'required', 'max:600'
            ],
            'name' => [
                'max:600'
            ],
            'second_name' => [
                'max:600'
            ],
            'unit_type_id' => [
                'required',
            ],
            'currency_type_id' => [
                'required'
            ],
            'sale_unit_price' => [
                'required',
                'numeric',
                'gt:0'
            ],
            'purchase_unit_price' => [
                'required', 'numeric'
            ],
            'stock' => [
                'required',
            ],
            'stock_min' => [
                'required',
            ],
            'sale_affectation_igv_type_id' => [
                'required'
            ],
            'purchase_affectation_igv_type_id' => [
                'required'
            ],
            'model' => 'max:100'

        ];
    }

    public function messages()
    {
        return [
            'description.required' => 'El campo nombre es obligatorio.',
            'name.max' => 'La descripción debe ser inferior a 600 caracteres.',
            'sale_unit_price.gt' => 'El precio unitario de venta debe ser mayor que 0.',
        ];

        //Update
    }
}
