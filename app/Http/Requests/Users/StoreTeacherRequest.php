<?php

namespace App\Http\Requests\Users;

use Illuminate\Foundation\Http\FormRequest;

class StoreTeacherRequest extends FormRequest
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
            'last_name' => 'required|max:255',
            'subject' => 'required|max:255',
            'school_id' => 'required|exists:schools,id',
            'image_url' => 'max:255',
            'email' => 'unique:users|max:255',
            'password' => 'max:255',
        ];
    }
}
