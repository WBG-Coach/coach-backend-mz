<?php

namespace App\Http\Requests\Users;

use Illuminate\Foundation\Http\FormRequest;

class LastApplicationRequest extends FormRequest
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
            'coach_id' => 'required|exists:users,id',
            'school_id' => 'required|exists:schools,id'
        ];
    }
}
