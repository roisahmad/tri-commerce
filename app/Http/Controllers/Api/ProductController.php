<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Product;
use Illuminate\Validation\ValidationException;
use Throwable;

class ProductController extends Controller
{
    public function index(): JsonResponse
    {
        try {
            $products = Product::all();
            return response()->json([
                'status' => 'success',
                'data' => $products
            ]);
        } catch (Throwable $e) {
            return $this->errorResponse($e);
        }
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'sku' => 'required|string|unique:products',
                'name' => 'required|string|max:255',
                'price' => 'required|integer|min:0',
                'reference' => 'required|string|max:255',
            ]);

            $product = Product::create($validated);

            return response()->json([
                'status' => 'success',
                'data' => $product
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (Throwable $e) {
            return $this->errorResponse($e);
        }
    }

    public function show(Product $product): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'data' => $product
        ]);
    }

    public function update(Request $request, Product $product): JsonResponse
    {
        try {
            $validated = $request->validate([
                'sku' => 'string|unique:products,sku,' . $product->id,
                'name' => 'string|max:255',
                'price' => 'integer|min:0',
                'reference' => 'string|max:255',
            ]);

            $product->update($validated);

            return response()->json([
                'status' => 'success',
                'data' => $product
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (Throwable $e) {
            return $this->errorResponse($e);
        }
    }

    public function destroy(Product $product): JsonResponse
    {
        try {
            $product->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Product deleted successfully'
            ]);
        } catch (Throwable $e) {
            return $this->errorResponse($e);
        }
    }

    /**
     * Helper untuk uniform error JSON
     */
    private function errorResponse(Throwable $e, int $status = 500): JsonResponse
    {
        return response()->json([
            'status' => 'error',
            'message' => 'Server Error',
            'error' => $e->getMessage(),
        ], $status);
    }
}
