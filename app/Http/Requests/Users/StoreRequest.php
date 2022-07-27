<?php

namespace App\Http\Requests\Users;

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
        return [
            'name' => 'required|max:255',
            'email' => 'required|unique:users|max:255',
            'password' => 'required|max:255',
            'image_url' => 'max:255',
            'profile_id' => 'required|exists:profiles,id',
            'subject' => 'max:255',
            'project_id' => 'required|exists:projects,id'
        ];
    }
}
