<?php

namespace App\Http\Controllers;

use App\Http\Requests\Order\StoreOrderRequest;
use App\Services\Order\StoreOrderService;
use Illuminate\Http\JsonResponse;
use App\Factories\UserProductDTOFactory;

class OrderController extends Controller
{
    public function store(StoreOrderRequest $request, UserProductDTOFactory $factory, StoreOrderService $service): JsonResponse
    {
        $dto = $factory->makeDTO($request);
        $order = $service->store($dto);
        return response()->json(['message' => 'order created!', 'data' => ['id' => $order->id, 'status' => $order->status]], 201);
    }

    public function index(int $id)
    {

    }
}

