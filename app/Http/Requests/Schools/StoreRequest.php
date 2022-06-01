<?php

namespace App\Http\Requests\Schools;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [,
            'name' => 'required|max:255',
            'image_url' => 'max:255',
            'address' => 'max:255',
            'country' => 'max:255',
            'state' => 'max:255',
            'city' => 'max:255',
            'town' => 'max:255',
            'village' => 'max:255',
            'district' => 'max:255'
        ];
    }
}
