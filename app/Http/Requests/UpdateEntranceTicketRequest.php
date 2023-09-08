<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEntranceTicketRequest extends FormRequest
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
            'provider' => 'sometimes',
            'description' => 'sometimes|string|max:225',
            'cover_image' => 'sometimes|image|max:2048',
            'city_ids' => 'sometimes|array',
            'category_ids' => 'sometimes|array',
            'tag_ids' => 'sometimes|array',
            'variations' => 'sometimes|array',
        ];
    }
}
