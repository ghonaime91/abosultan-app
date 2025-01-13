<?php
declare(strict_types=1);

return [
    'first_name' => [
        'required' => 'The first name is required.',
        'string'   => 'The first name must be a string.',
        'max'      => 'The first name must not exceed 60 characters.',
    ],

    'last_name' => [
        'required' => 'The last name is required.',
        'string'   => 'The last name must be a string.',
        'max'      => 'The last name must not exceed 60 characters.',
    ],

    'email' => [
        'required' => 'The email address is required.',
        'string'   => 'The email address must be a string.',
        'unique'   => 'Please choose another email address.',
        'regex'    => 'The email address format is invalid.',
    ],

    'phone' => [
        'string' => 'The phone number must be a string.',
        'unique' => 'The phone number is already registered.',
        'regex'  => 'The phone number format is invalid.',
    ],

    'password' => [
        'required'  => 'The password is required.',
        'string'    => 'The password must be a string.',
        'confirmed' => 'The password confirmation does not match.',
        'min'       => 'The password must be at least 6 characters long.',
        'regex'     => 'The password must contain at least one uppercase letter and one special character such as @, $, %, &.',
    ],
];
