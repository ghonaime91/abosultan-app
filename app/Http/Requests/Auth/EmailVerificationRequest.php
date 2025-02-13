<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Exceptions\HttpResponseException;

class EmailVerificationRequest extends FormRequest
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
            'regex:/^[\w\.-]+@[\w\.-]+\.[a-zA-Z]{2,10}$/',
            'exists:users,email'
        ],
            'otp'   => ['required', 'digits:6']
        ];
    }

    
    public function messages(): array
    {
        return [
            'email.required' => __('custom_validation.email.required'),
            'email.string'   => __('custom_validation.email.string'),
            'email.regex'    => __('custom_validation.email.regex'),
            'email.exists'   => __('custom_validation.email.exists'),
            'otp.required'   => __('custom_validation.otp.required'),
            'otp.digits'     => __('custom_validation.otp.digits'), 
        ];
    }
    
}
