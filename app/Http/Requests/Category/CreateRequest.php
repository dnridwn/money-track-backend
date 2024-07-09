<?php

namespace App\Http\Requests\Category;

use App\Models\Category;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;

class CreateRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'max:100',
            ],
            'description' => [
                'max:300'
            ],
            'type' => [
                'required',
                Rule::in([Category::TYPE_INCOME, Category::TYPE_EXPENSE])
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Please fill name',
            'name.max' => 'Name too long. Maximum 100 characters',
            'description.max' => 'Description too long. Maximum 300 characters',
            'type.required' => 'Please choose account type',
            'type.in' => 'Invalid type'
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->failed($validator->errors()->first()), JsonResponse::HTTP_UNPROCESSABLE_ENTITY
        );
    }
}
