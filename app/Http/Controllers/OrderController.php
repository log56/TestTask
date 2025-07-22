<?php

namespace App\Http\Controllers;

use App\Http\Requests\Order\StoreOrderRequest;
use App\Services\Order\StoreOrderService;
use Illuminate\Http\JsonResponse;
use App\Factories\UserProductDTOFactory;
use App\Services\Order\ShowOrderService;

class OrderController extends Controller
{
    /**
     * Handles order creation.
     *
     * @param StoreOrderRequest $request Validated request with user & product data
     * @param UserProductDTOFactory $factory Converts request to DTO
     * @param StoreOrderService $service Business logic for storing orders
     * @return JsonResponse
     */
    public function store(StoreOrderRequest $request, UserProductDTOFactory $factory, StoreOrderService $service): JsonResponse
    {
        // Transform validated request into DTO for internal use
        $dto = $factory->makeDTO($request);

        // Store the order using business logic service
        $order = $service->store($dto);

        // Return success response with order ID and status
        return response()->json([
            'message' => 'order created!',
            'data' => [
                'id' => $order->id,
                'status' => $order->status
            ]
        ], 201);
    }

    /**
     * Returns orders for a given user ID.
     *
     * @param int $id User ID
     * @param ShowOrderService $service Business logic to fetch orders
     * @return JsonResponse
     */
    public function index(int $id, ShowOrderService $service): JsonResponse
    {
        // Retrieve orders via service layer
        $orders = $service->show($id);

        // Return orders
        return response()->json(['data' => $orders], 200);
    }
}
