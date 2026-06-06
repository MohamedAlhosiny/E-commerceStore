<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'sometimes|string|min:3',
            'description' => 'sometimes|string|min:5',
            'price' => 'sometimes|numeric',
            'category_id' => 'sometimes|exists:categories,id',
        ];
    }
}
