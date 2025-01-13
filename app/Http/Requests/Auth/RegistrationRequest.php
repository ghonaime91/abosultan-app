<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Exceptions\HttpResponseException;

class RegistrationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return void
     *
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     */
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

            'first_name' => 'required|string|max:60',

            'last_name'  => 'required|string|max:60',  

            'email' => [

                'required',

                'string',

                'unique:users,email',

                'regex:/^[\w\.-]+@[\w\.-]+\.[a-zA-Z]{2,10}$/'

            ],

            'phone' => [

                'nullable',

                'string',

                'unique:users,phone',

                'regex:/^\+?[1-9]\d{1,20}$/',

            ],

            'password' => [

                'required',

                'string',

                'confirmed',

                'min:6',

                'regex:/[A-Z]/',

                'regex:/[@$!%*?&#]/',

            ],
        ];
    }

    /**
     * Get custom error messages for validation rules.
     */
    public function messages(): array
    {
        return [
            'first_name.required' => __('custom_validation.first_name.required'),
            'first_name.string'   => __('custom_validation.first_name.string'),
            'first_name.max'      => __('custom_validation.first_name.max'),
    
            'last_name.required' => __('custom_validation.last_name.required'),
            'last_name.string'   => __('custom_validation.last_name.string'),
            'last_name.max'      => __('custom_validation.last_name.max'),
    
            'email.required' => __('custom_validation.email.required'),
            'email.string'   => __('custom_validation.email.string'),
            'email.unique'   => __('custom_validation.email.unique'),
            'email.regex'    => __('custom_validation.email.regex'),
    
            'phone.string' => __('custom_validation.phone.string'),
            'phone.unique' => __('custom_validation.phone.unique'),
            'phone.regex'  => __('custom_validation.phone.regex'),
    
            'password.required'  => __('custom_validation.password.required'),
            'password.string'    => __('custom_validation.password.string'),
            'password.confirmed' => __('custom_validation.password.confirmed'),
            'password.min'       => __('custom_validation.password.min'),
            'password.regex'     => __('custom_validation.password.regex'),
        ];
    }
}
