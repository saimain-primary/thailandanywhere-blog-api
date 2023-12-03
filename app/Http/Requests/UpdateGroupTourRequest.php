<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateGroupTourRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name'  => 'sometimes',
            'price'  => 'sometimes',
            'sku_code' => 'sometimes|' . Rule::unique('group_tours')->ignore($this->group_tour),
        ];
    }

    public function messages()
    {
        return [
            'sku_code.unique' => 'SKU Code is already exist, please try another one',
        ];
    }
}
