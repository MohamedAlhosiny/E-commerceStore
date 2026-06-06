<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|min:3',
            'description' => 'nullable|min:5|string',
            'price' => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
        ];
    }
}
