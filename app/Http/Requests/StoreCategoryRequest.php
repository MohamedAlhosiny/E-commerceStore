<?php




namespace App\Http\Requests;

use App\Traits\ApiResponseTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreCategoryRequest extends FormRequest
{
    use ApiResponseTrait;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', 'unique:categories,name'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Category name is required.',
            'name.unique'   => 'This category name already exists.',
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

