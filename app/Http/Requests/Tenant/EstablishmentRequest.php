<?php

namespace App\Http\Requests\Tenant;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EstablishmentRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $id = $this->get('id');
        return [
            'description' => [
                'required',
                Rule::unique('tenant.establishments')->ignore($id),
            ],
            'number' => [
                'required',
                Rule::unique('tenant.establishments')->ignore($id),
            ],
            'identity_document_type_id' => [
                'required',
            ],
            'department_id' => [
                'required',
            ],
            'province_id' => [
                'required',
            ],
            'district_id' => [
                'required',
            ],
            'address' => [
                'required',
            ],
            'code' => [
                'required',
            ],
        ];
    }
}
