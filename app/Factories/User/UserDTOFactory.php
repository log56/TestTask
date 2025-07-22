<?php

namespace App\Factories\User;

use App\DTO\User\UserDTO;
use App\Http\Requests\User\StoreUserRequest;

class UserDTOFactory
{
    public function makeDTO(StoreUserRequest $request): UserDTO
    {
        $dto = new UserDTO(
            $request->username,
            $request->phone,
            $request->adress,
            $request->password,
        );
        return $dto;
    }
}
