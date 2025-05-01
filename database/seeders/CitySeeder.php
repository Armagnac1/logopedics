<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $json = Storage::disk('local')->get('/json/cities.json');
        $cities = json_decode($json, true);
        foreach ($cities as $city) {
            \App\Models\City::create([
                'name' => $city['city'],
                'region' => $city['region'],
                'country' => 'Россия',
                'timezone' => preg_replace('~\D~', '', $city['timezone']),
            ]);
        }
    }
}
