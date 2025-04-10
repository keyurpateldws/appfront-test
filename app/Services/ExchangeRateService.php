<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class ExchangeRateService
{
    public function getRate(string $from = 'USD', string $to = 'EUR'): float
    {
        try {
            $response = Http::timeout(5)
                ->get("https://open.er-api.com/v6/latest/$from");

            if ($response->successful()) {
                $data = $response->json();

                if (isset($data['rates'][$to])) {
                    return $data['rates'][$to];
                }
            } else {
                Log::warning('Exchange rate API request failed with status: ' . $response->status());
            }
        } catch (\Exception $e) {
            Log::error('Failed to fetch exchange rate: ' . $e->getMessage());
        }

        return config('app.exchange_rate', 0.85); 
    }
}