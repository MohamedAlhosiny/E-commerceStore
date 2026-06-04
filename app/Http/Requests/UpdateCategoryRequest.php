<?php

namespace App\Http\Requests;

use App\Traits\ApiResponseTrait;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateCategoryRequest extends FormRequest
{
    use ApiResponseTrait;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $categoryId = $this->route('id'); // Get the category ID from the route parameter

        return [
                'name' => ['sometimes', 'string', 'max:255', 'unique:categories,name,' . $categoryId],
        ];
    }

    public function messages(): array
    {
        return [
            'name.max'      => 'Category name must not exceed 255 characters.',
        ];
    }


    protected function failedValidation(Validator $validator): void
    {
        // dd($validator->errors());
        throw new HttpResponseException(
            $this->errorResponse($validator->errors(), 'Validation failed', 422)
        );
    }

}
