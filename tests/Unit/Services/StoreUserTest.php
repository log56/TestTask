<?php

namespace Tests\Unit\Services;

use App\DTO\User\UserDTO;
use App\Services\User\StoreUserService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class StoreUserTest extends TestCase
{
    use RefreshDatabase;

    private StoreUserService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new StoreUserService();
    }

    public function test_it_creates_user_with_hashed_password()
    {
        $dto = new UserDTO(
            username: 'test_user',
            phone: '+79001234567',
            adress: 'Test Address',
            password: 'plain_password'
        );

        $user = $this->service->store($dto);

        $this->assertDatabaseHas('users', [
            'username' => 'test_user',
            'phone' => '+79001234567',
            'adress' => 'Test Address'
        ]);

        $this->assertTrue(Hash::check('plain_password', $user->password));
    }

    public function test_it_hashes_password_correctly()
    {
        $dto = new UserDTO(
            username: 'test_user',
            phone: '+79001234567',
            adress: 'Test Address',
            password: 'secret123'
        );

        $user = $this->service->store($dto);

        $this->assertNotEquals('secret123', $user->password);
        $this->assertTrue(Hash::check('secret123', $user->password));
    }
}