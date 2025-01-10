<?php

namespace App\Http\Controllers;


use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use App\Helpers\ResponseMessages;
use App\Traits\HttpResponses;
use App\Services\UserService;

/**
 * @group Authentication
 * 
 * API endpoints for user authentication.
 */

class AuthController extends Controller
{
    use HttpResponses;

    protected UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }


    /**
     * Register a new user.
     * 
     * Registers a new user and returns user data.
     * 
     * @bodyParam name string required The name of the user. Example: John Doe
     * @bodyParam email string required The email of the user. Example: john@example.com
     * @bodyParam password string required The password of the user. Example: secret123
     * @response 201 {
     *   "success": true,
     *   "message": "User created successfully",
     *   "data": {
     *     "id": 1,
     *     "name": "John Doe",
     *     "email": "john@example.com"
     *   }
     * }
     */

    public function register(RegisterRequest $request)
    {
        $user = $this->userService->registerUser($request);
        return $this->successHttpMessage(
            $user,
            ResponseMessages::getSuccessMessage('User Registered Successfully'),
            201
        );

    }



    /**
     * Login user and return token.
     * 
     * Logs in a user and returns a Bearer token for authentication.
     * 
     * @bodyParam email string required The email of the user. Example: john@example.com
     * @bodyParam password string required The password of the user. Example: secret123
     * @response 200 {
     *   "success": true,
     *   "message": "User logged in successfully",
     *   "data": {
     *     "access_token": "your_generated_token_here",
     *     "token_type": "bearer",
     *     "expires_in": 3600
     *   }
     * }
     */
    public function login(LoginRequest $request)
    {
        $token = $this->userService->loginUser($request);
        return $this->successHttpMessage(
            $token,
            ResponseMessages::getSuccessMessage('User Logged in successfully'),
            200
        );
    }


    /**
     * Logout user.
     * 
     * Logs the user out of the system, invalidating the current token.
     * 
     * @response 200 {
     *   "success": true,
     *   "message": "Successfully logged out",
     *   "data": null
     * }
     */
    public function logout()
    {
        auth()->logout();
        return $this->successHttpMessage(
            null,
            ResponseMessages::getSuccessMessage('Successfully logged out'),
            200
        );
    }

    /**
     * Refresh authentication token.
     * 
     * Refreshes the user's authentication token.
     * 
     * @response 200 {
     *   "success": true,
     *   "message": "Successfully logged out",
     *   "data": {
     *     "access_token": "new_generated_token_here",
     *     "token_type": "bearer",
     *     "expires_in": 3600
     *   }
     * }
     */
    public function refresh()
    {
        $success = $this->userService->respondWithToken(auth()->refresh());

        return $this->successHttpMessage(
            $success,
            ResponseMessages::getSuccessMessage('Successfully logged out'),
            200
        );
    }
}
