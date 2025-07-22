<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\JsonResponse;

class ProductController extends Controller
{
    /**
     * Return a list of all products with their categories.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        // Eager load 'category' relationship to avoid N+1 problem
        $products = Product::with('category')->get();

        // Return product list as JSON
        return response()->json([
            'data' => $products,
        ]);
    }

