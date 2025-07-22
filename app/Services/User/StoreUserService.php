<?php

namespace App\Services\User;

use Illuminate\Support\Facades\Hash;
use App\DTO\User\UserDTO;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class StoreUserService
{
    /**
     * Creates a new user based on the provided data.
     *
     * - Hashes the password before saving
     * - Saves username, phone, address, and password to the database
     *
     * @param UserDTO $dto Data transfer object containing user data (username, phone, address, password)
     * @return User The created user model
     */
    public function store(UserDTO $dto): User
    {
        $user = new User();
        $user->username = $dto->username;
        $user->phone = $dto->phone;
        if ( User::where('phone', $dto->phone)->exists() ) {
            throw ValidationException::withMessages(['This phone is already registred']);
        }
        $user->adress = $dto->adress; 
        $user->password = Hash::make($dto->password);
        $user->save();

        return $user;
    }
}