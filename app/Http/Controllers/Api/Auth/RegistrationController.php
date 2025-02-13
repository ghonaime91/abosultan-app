<?php

namespace App\Http\Controllers\Api\Auth;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Registered;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\Auth\RegistrationRequest;
use App\Notifications\EmailVerificationNotification;

class RegistrationController extends Controller
{
        // Register a new user
        public function register(RegistrationRequest $request)
        {
            try {
                // Validate the input data with form request
                $fields = $request->validated();

                // Create the user
                $user = User::create([
                'first_name' => $fields['first_name'],
                'last_name'  => $fields['last_name'],
                'email'      => $fields['email'],
                'phone'      => $fields['phone'] ?? null, 
                'password'   => bcrypt($fields['password']),               
                'role'       => 'user'                
                ]);
    
                
                
                // Create the sanctum token
                $token = $user->createToken(
                    $request->header('User-Agent')
                    )->plainTextToken;

                // Send email verification notification
                $user->notify(new EmailVerificationNotification()); 
                   
                return response()->json([    
                    'success' => true,    
                    'message' => __('messages.account_created'),    
                    'user'    => $user,    
                    'token'   => $token
                ])->setStatusCode(Response::HTTP_CREATED);
            }

            catch (\Exception $e) {    
                // In case of any other error, return a general error response
                return response()->json([    
                    'success' => false,   
                    'message' => __('exceptions.internal_server_error'),  
                    'error'   => $e->getMessage()    
                ])->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        } 

}
