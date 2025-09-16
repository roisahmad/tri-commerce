<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Product;
use App\Services\TripayServices;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Throwable;

class InvoiceController extends Controller
{
    private $tripayService;

    public function __construct(TripayServices $tripayService)
    {
        $this->tripayService = $tripayService;
    }

    public function index(): JsonResponse
    {
        try {
            $invoices = Invoice::with('product')->get();
            return response()->json([
                'status' => 'success',
                'data' => $invoices
            ]);
        } catch (Throwable $e) {
            return $this->errorResponse($e);
        }
    }

    public function getPaymentChannels(): JsonResponse
    {
        try {
            $channels = $this->tripayService->getPaymentChannels();
            return response()->json([
                'status' => 'success',
                'data' => $channels['data'] ?? []
            ]);
        } catch (Throwable $e) {
            return $this->errorResponse($e);
        }
    }

    public function createTransaction(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'product_id' => 'required|exists:products,id',
                'payment_method' => 'required|string',
                'buyer_email' => 'required|email',
                'buyer_phone' => 'required|string',
            ]);

            $product = Product::findOrFail($validated['product_id']);

            $transactionData = [
                'method'         => $validated['payment_method'],
                'merchant_ref'   => $product->reference . '-' . time(),
                'amount'         => $product->price,
                'customer_name'  => explode('@', $validated['buyer_email'])[0],
                'customer_email' => $validated['buyer_email'],
                'customer_phone' => $validated['buyer_phone'],
                'order_items'    => [
                    [
                        'sku'      => $product->sku,
                        'name'     => $product->name,
                        'price'    => $product->price,
                        'quantity' => 1,
                    ]
                ],
            ];

            $tripayResponse = $this->tripayService->createTransaction($transactionData);

            $invoice = Invoice::create([
                'product_id'       => $product->id,
                'tripay_reference' => $tripayResponse['data']['reference'] ?? null,
                'buyer_email'      => $validated['buyer_email'],
                'buyer_phone'      => $validated['buyer_phone'],
                'raw_response'     => $tripayResponse,
            ]);

            return response()->json([
                'status' => 'success',
                'data' => [
                    'invoice'     => $invoice,
                    'tripay_data' => $tripayResponse,
                ]
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Validation failed',
                'errors'  => $e->errors(),
            ], 422);
        } catch (Throwable $e) {
            return $this->errorResponse($e);
        }
    }

    public function show(Invoice $invoice): JsonResponse
    {
        try {
            $invoice->load('product');
            return response()->json([
                'status' => 'success',
                'data' => $invoice
            ]);
        } catch (Throwable $e) {
            return $this->errorResponse($e);
        }
    }

    private function errorResponse(Throwable $e, int $status = 500): JsonResponse
        {
            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage(),
                'trace'   => config('app.debug') ? $e->getTrace() : null, 
            ], $status);
        }
    }
