<?php

namespace Tests\Unit;

use App\Http\Controllers\AuthController;
use App\Services\UserService;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

use Mockery;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test the register method.
     *
     * @return void
     */

    public function testRegisterUser()
    {
        // Arrange: Create mock data for the request
        $requestData = [
            'name' => 'bolaju boul',
            'email' => 'abz@gmail.com',
            'password' => 'pass1234',
            'c_password' => 'pass1234',
        ];

        $response = $this->json('POST', '/api/register', $requestData);

        // Assert the response status is 201 (Created)
        $response->assertStatus(201)
            ->assertJson(
                fn(AssertableJson $json) =>
                $json->has('success')
                && $json->has('message')
                && $json->has('data')
                && $json->has('data.name')
                && $json->has('data.email')
                && $json->where('success', true)
                && $json->where('message', 'User Registered Successfully')
                && $json->where('data.name', 'bolaju boul')
                && $json->where('data.email', 'abz@gmail.com')
                && $json->missing('data.password')
                && $json->missing('data.c_password')
            );
    }
}
