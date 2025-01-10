<?php

namespace Tests\Unit;

// use PHPUnit\Framework\TestCase;
use App\Services\UserService;
use App\Repositories\UserRepository;
use App\Exceptions\UnprocessableEntityException;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;
use Mockery;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserServiceTest extends TestCase
{
    private $userService;
    private $userRepositoryMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->userRepositoryMock = Mockery::mock(UserRepository::class);
        $this->userService = new UserService($this->userRepositoryMock);
    }

    public function test_register_user_successfully()
    {
        // Arrange
        $data = [
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'password' => 'hashed_password',
        ];

        $request = new \Illuminate\Http\Request($data);

        $this->userRepositoryMock
            ->shouldReceive('insert')
            ->once()
            ->with($data)
            ->andReturn($data);

        // Act
        $result = $this->userService->registerUser($request);

        // Assert
        $this->assertEquals($data, $result);
    }

    public function test_login_user_successfully()
    {
        // Arrange
        $credentials = ['email' => 'johndoe@example.com', 'password' => 'password'];
        $request = new \Illuminate\Http\Request($credentials);
        $token = 'mocked_jwt_token';

        // Mock JWTAuth
        JWTAuth::shouldReceive('attempt')
            ->once()
            ->with($credentials)
            ->andReturn($token);

        // Act
        $result = $this->userService->loginUser($request);

        // Assert
        $this->assertEquals($token, $result);
    }

    public function test_login_user_with_invalid_credentials()
    {
        // Arrange
        $credentials = ['email' => 'johndoe@example.com', 'password' => 'wrong_password'];
        $request = new \Illuminate\Http\Request($credentials);

        // Mock JWTAuth
        JWTAuth::shouldReceive('attempt')
            ->once()
            ->with($credentials)
            ->andReturn(false);

        $this->expectException(UnprocessableEntityException::class);
        $this->expectExceptionMessage('Invalid login credentials');

        // Act
        $this->userService->loginUser($request);
    }

    public function test_respond_with_token()
    {
        // Arrange
        $token = 'mocked_jwt_token';
        Auth::shouldReceive('factory->getTTL')
            ->once()
            ->andReturn(60);

        // Act
        $result = $this->userService->respondWithToken($token);

        // Assert
        $this->assertEquals([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => 3600,
        ], $result);
    }
}