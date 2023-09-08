<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StoreGroupTourRequest extends FormRequest
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
            'name'  => 'required',
            'price' => 'required',
            'description' => 'required|string|max:225',
            'cover_image' => 'required|image|max:2048',
            'city_ids' => 'required|array',
            'tag_ids' => 'required|array',
            'destination_ids' => 'required|array',
            'sku_code' => 'required|' . Rule::unique('group_tours'),
        ];
    }

    public function messages()
    {
        return [
            'sku_code.unique' => 'SKU Code is already exist, please try another one',
            'cover_image.required' => 'Cover Image is required',
            'tag_ids.required' => 'Tags is required',
            'destination_ids.required' => 'Destinations is required',
            'city_ids.required' => 'Cities is required',
            'price.required' => 'Price is required',
        ];
    }
}
