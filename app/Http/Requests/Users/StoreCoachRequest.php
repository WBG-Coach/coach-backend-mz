<?php

namespace App\Http\Requests\Users;

use Illuminate\Foundation\Http\FormRequest;

class StoreCoachRequest extends FormRequest
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
        return [
            'name' => 'required|max:255',
            'last_name' => 'max:255',
            'email' => 'required|unique:users|max:255',
            'password' => 'required|max:255',
            'image_url' => 'max:255',
            'subject' => 'max:255'
        ];
    }
}
