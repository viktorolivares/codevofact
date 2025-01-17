<?php

namespace Modules\Item\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SizeRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {

        $id = $this->input('id');
        return [

            'name' => [
                'required',
            ]
        ];

    }
}
