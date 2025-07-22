<?php

namespace App\Factories\User;

use App\DTO\User\UserDTO;
use App\Http\Requests\User\StoreUserRequest;

class UserDTOFactory
{
    /**
     * Creates a UserDTO from the validated StoreUserRequest data.
     *
     * Maps request data fields into a UserDTO instance.
     *
     * @param StoreUserRequest $request Validated user creation request
     * @return UserDTO Data transfer object representing user information
     */
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