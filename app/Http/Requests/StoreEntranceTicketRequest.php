<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEntranceTicketRequest extends FormRequest
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
            'provider' => 'required',
            'description' => 'required|string|max:225',
            'cover_image' => 'required|image|max:2048',
            'city_ids' => 'required|array',
            'category_ids' => 'required|array',
            'tag_ids' => 'required|array',
            'variations' => 'required|array',
            'images' => 'required|array',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Name is required',
            'provider.required' => 'Provider is required',
            'variations.required' => 'Variations is required',
            'cover_image.required' => 'Cover Image is required',
            'tag_ids.required' => 'Tags is required',
            'city_ids.required' => 'Cities is required',
            'category_ids.required' => 'Category is required',
        ];
    }
}
