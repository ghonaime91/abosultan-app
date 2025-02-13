<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Exceptions\HttpResponseException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }


    protected function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => __('exceptions.validation_error'),
            'errors' => $validator->errors()->toArray(),            
        ])->setStatusCode(Response::HTTP_UNPROCESSABLE_ENTITY));
    }


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => [
                'required',
                'string',
                'regex:/^[\w\.-]+@[\w\.-]+\.[a-zA-Z]{2,10}$/'
            ],
            'password' => [
                'required',
                'string',
                'min:6',
                'regex:/[A-Z]/',
                'regex:/[@$!%*?&#]/',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => __('custom_validation.email.required'),
            'email.string'   => __('custom_validation.email.string'),
            'email.regex'    => __('custom_validation.email.regex'),
            'password.required'  => __('custom_validation.password.required'),
            'password.string'    => __('custom_validation.password.string'),
            'password.min'       => __('custom_validation.password.min'),
            'password.regex'     => __('custom_validation.password.regex'),
        ];
    }
}
