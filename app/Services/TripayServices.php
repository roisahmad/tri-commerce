<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TripayServices
{
    private $apiKey;
    private $privateKey;
    private $merchantCode;
    private $baseUrl;

    public function __construct()
    {
        $this->apiKey = config('tripay.api_key');
        $this->privateKey = config('tripay.private_key');
        $this->merchantCode = config('tripay.merchant_code');
        $this->baseUrl = config('tripay.base_url');
    }

    public function getPaymentChannels()
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
            ])->get($this->baseUrl . '/merchant/payment-channel');

            if ($response->successful()) {
                return $response->json();
            }

            throw new \Exception('Failed to fetch payment channels: ' . $response->body());
        } catch (\Exception $e) {
            Log::error('TriPay API Error: ' . $e->getMessage());
            throw $e;
        }
    }

    public function createTransaction($data)
    {
        try {
            $merchantRef = $data['merchant_ref'];
            $amount = $data['amount'];

            $payload = [
                'method'         => $data['method'],
                'merchant_ref'   => $merchantRef,
                'amount'         => $amount,
                'customer_name'  => $data['customer_name'],
                'customer_email' => $data['customer_email'],
                'customer_phone' => $data['customer_phone'],
                'order_items'    => $data['order_items'],
                'return_url'     => $data['return_url'] ?? config('app.url'),
                'expired_time'   => (time() + (24 * 60 * 60)), // 24 jam
                'signature'      => $this->generateSignature($merchantRef, $amount),
            ];

            $response = Http::asForm()->withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
            ])->post($this->baseUrl . '/transaction/create', $payload);

            if ($response->successful()) {
                return $response->json();
            }

            throw new \Exception('Failed to create transaction: ' . $response->body());
        } catch (\Exception $e) {
            Log::error('TriPay Transaction Error: ' . $e->getMessage());
            throw $e;
        }
    }

    private function generateSignature($merchantRef, $amount)
    {
        return hash_hmac('sha256', $this->merchantCode . $merchantRef . $amount, $this->privateKey);
    }
}
