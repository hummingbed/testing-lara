<?php

namespace App\Services;

use App\Repositories\UserRepository;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Exceptions\UnprocessableEntityException;
use App\Helpers\ResponseMessages;


class UserService extends BaseService
{
    public function __construct(UserRepository $repository)
    {
        $this->repo = $repository;
    }

    public function registerUser($request)
    {
        return $this->repo->insert([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password //password is hashed in the user model
        ]);
    }

    public function loginUser($request)
    {
        $credentials = $request->only('email', 'password');
        $token = JWTAuth::attempt($credentials);

        throw_unless($token, new UnprocessableEntityException(
            ResponseMessages::unprocessableEntityMessage('Invalid login credentials')
        ));
        
        return $token;
    }

    public function respondWithToken($token)
    {
        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ];
    }
}