<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePrivateVanTourRequest extends FormRequest
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
            'description' => 'sometimes|string|max:225',
            'cover_image' => 'sometimes|image|max:2048',
            'city_ids' => 'sometimes|array',
            'car_ids' => 'sometimes|array',
            'prices' => ['sometimes', 'array', function ($attribute, $value, $fail) {
                if (count($value) !== count($this->input('car_ids'))) {
                    $fail($attribute . ' and cars must have the same number of elements.');
                }
            }],
            'agent_prices' => ['sometimes', 'array', function ($attribute, $value, $fail) {
                if (count($value) !== count($this->input('car_ids'))) {
                    $fail($attribute . ' and cars must have the same number of elements.');
                }
            }],
            'tag_ids' => 'sometimes|array',
            'destination_ids' => 'sometimes|array',
            'sku_code' => 'sometimes|' . Rule::unique('private_van_tours')->ignore($this->private_van_tour),
        ];
    }

    public function messages()
    {
        return [
            'sku_code.unique' => 'SKU Code is already exist, please try another one',
            'car_ids.array' => 'Cars must be an array',
            'prices.array' => 'Prices must be an array',
            'agent_prices.array' => 'Agent Prices must be an array',
            'prices.Mismatched count' => 'Cars and Prices must have the same number of elements.',
            'agent_price.Mismatched count' => 'Cars and Agent Prices must have the same number of elements.',
        ];
    }
}