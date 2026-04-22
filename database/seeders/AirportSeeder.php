<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use App\Models\Airport;

class AirportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Fetching airports data...');
        $response = Http::withoutVerifying()->get('https://raw.githubusercontent.com/mwgg/Airports/master/airports.json');
        
        if ($response->successful()) {
            $airports = $response->json();
            $data = [];
            
            foreach ($airports as $airport) {
                if (!empty($airport['iata']) && strlen($airport['iata']) === 3) {
                    $data[] = [
                        'iata_code' => strtoupper($airport['iata']),
                        'name' => $airport['name'] ?? 'Unknown Airport',
                        'city' => $airport['city'] ?? null,
                        'country' => $airport['country'] ?? 'Unknown',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }
            
            $this->command->info('Inserting ' . count($data) . ' airports...');
            $chunks = array_chunk($data, 500);
            foreach ($chunks as $chunk) {
                Airport::insertOrIgnore($chunk);
            }
            $this->command->info('Airports seeded successfully.');
        } else {
            $this->command->error('Failed to fetch airports data.');
        }
    }
}
