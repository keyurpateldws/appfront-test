<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;

class ExchangeRateService
{
    public function getRate(string $from = 'USD', string $to = 'EUR'): float
    {
        try {
            $curl = curl_init();

            curl_setopt_array($curl, [
                CURLOPT_URL => "https://open.er-api.com/v6/latest/$from",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT => 5,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
            ]);

            $response = curl_exec($curl);
            $err = curl_error($curl);

            curl_close($curl);

            if (!$err) {
                $data = json_decode($response, true);
                if (isset($data['rates'][$to])) {
                    return $data['rates'][$to];
                }
            }
        } catch (\Exception $e) {
            Log::error('Failed to fetch exchange rate: ' . $e->getMessage());
        }
        return config('app.exchange_rate');
    }
}