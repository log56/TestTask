<?php

namespace App\Http\Controllers;

use App\Factories\User\UserDTOFactory;
use App\Http\Requests\User\StoreUserRequest;
use App\Services\User\StoreUserService;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    /**
     * Store a new user using validated input and return the created user ID.
     *
     * @param StoreUserRequest $request Validated user input
     * @param UserDTOFactory $factory Converts input into a UserDTO
     * @param StoreUserService $service Handles user creation logic
     * @return JsonResponse
     */
    public function store(StoreUserRequest $request, UserDTOFactory $factory, StoreUserService $service): JsonResponse
    {
        // Convert validated request data into a UserDTO
        $dto = $factory->makeDTO($request);

        // Store the user via service layer
        $user = $service->store($dto);

        // Return success response with new user ID
        return response()->json([
            'message' => 'User registered successfully',
            'data' => ['id' => $user->id]
        ], 201);
    }
}
