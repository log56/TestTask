<?php

namespace App\Http\Controllers;

use App\Factories\User\UserDTOFactory;
use App\Http\Requests\User\StoreUserRequest;
use App\Services\User\StoreUserService;
use Illuminate\Http\JsonResponse;

class UserController
{
    public function store(StoreUserRequest $request, UserDTOFactory $factory, StoreUserService $service): JsonResponse
    {
        $dto = $factory->makeDTO($request);
        $user = $service->store($dto);
        return response()->json(['message' => 'User registered successfully', 'data' => ['id' => $user->id]], 201);
    }
}
