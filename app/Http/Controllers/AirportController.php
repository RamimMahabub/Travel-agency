<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Airport;

class AirportController extends Controller
{
    public function search(Request $request)
    {
        $query = trim((string) $request->input('q'));
        
        if (empty($query)) {
            return response()->json([]);
        }

        $exactQuery = strtoupper($query);
        $likeQuery = '%' . $query . '%';
        $prefixQuery = $query . '%';
        $countryCodes = $this->resolveCountryCodes($query);

        $airports = Airport::where('iata_code', $exactQuery)
            ->orWhere('iata_code', 'like', $exactQuery . '%')
            ->orWhere('city', 'like', $prefixQuery)
            ->orWhere('name', 'like', $likeQuery)
            ->when(!empty($countryCodes), function ($query) use ($countryCodes) {
                $query->orWhereIn('country', $countryCodes);
            })
            ->orWhere('country', 'like', $exactQuery)
            ->orderByRaw("
                CASE 
                    WHEN iata_code = ? THEN 1 
                    WHEN city LIKE ? THEN 2
                    WHEN iata_code LIKE ? THEN 3
                    WHEN country = ? THEN 4
                    ELSE 5 
                END
            ", [$exactQuery, $prefixQuery, $exactQuery . '%', $exactQuery])
            ->limit(10)
            ->get()
            ->map(function ($airport) {
                return [
                    'code' => $airport->iata_code,
                    'name' => $airport->name,
                    'city' => $airport->city,
                    'country' => $airport->country,
                    'display_name' => $airport->city ? ($airport->city . ' (' . $airport->iata_code . ') - ' . $airport->name) : ($airport->name . ' (' . $airport->iata_code . ')')
                ];
            });

        return response()->json($airports);
    }

    /**
     * Convert a country name or code into matching ISO country codes.
     */
    private function resolveCountryCodes(string $query): array
    {
        $countries = require base_path('vendor/nesbot/carbon/src/Carbon/List/regions.php');
        $normalizedQuery = mb_strtolower($query);

        $matches = [];

        foreach ($countries as $code => $name) {
            $normalizedName = mb_strtolower($name);

            if ($normalizedQuery === mb_strtolower($code)) {
                $matches[] = $code;
                continue;
            }

            if (str_starts_with($normalizedName, $normalizedQuery) || str_contains($normalizedName, $normalizedQuery)) {
                $matches[] = $code;
            }
        }

        return array_values(array_unique($matches));
    }
}
