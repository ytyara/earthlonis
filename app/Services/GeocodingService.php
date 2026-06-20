<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeocodingService
{
    public function reverseGeocode(float $latitude, float $longitude): ?array
    {
        $key = 'geocode:' . round($latitude, 4) . ',' . round($longitude, 4);

        if (Cache::has($key)) {
            return Cache::get($key);
        }

        try {
            $response = Http::withHeaders([
                'User-Agent' => 'TravelLevel/1.0 (https://travellevel.test)',
            ])
                ->timeout(5)
                ->get('https://nominatim.openstreetmap.org/reverse', [
                    'format'          => 'json',
                    'lat'             => $latitude,
                    'lon'             => $longitude,
                    'zoom'            => 10,
                    'addressdetails'  => 1,
                    'accept-language' => 'en',
                ]);

            if (!$response->successful()) {
                return null;
            }

            $address = $response->json('address', []);

            $result = [
                'city'    => $this->englishOnly($address['city'] ?? $address['town'] ?? $address['village'] ?? $address['municipality'] ?? null),
                'region'  => $this->englishOnly($address['state'] ?? $address['region'] ?? $address['county'] ?? null),
                'country' => $this->englishOnly($address['country'] ?? null),
            ];

            Cache::forever($key, $result);

            return $result;
        } catch (\Throwable $e) {
            Log::warning('Reverse geocoding failed: ' . $e->getMessage());

            return null;
        }
    }

    private function englishOnly(?string $value): ?string
    {
        if ($value === null) {
            return null;
        }

        return preg_match('/[\x{0400}-\x{04FF}]/u', $value) ? null : $value;
    }
}
