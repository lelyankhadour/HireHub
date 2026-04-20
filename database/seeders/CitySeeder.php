<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\City;

class CitySeeder extends Seeder
{
    public function run(): void
    {
        $cities = ['Beirut', 'Dubai', 'Riyadh', 'Amman', 'Cairo', 'Doha', 'Istanbul', 'Berlin', 'Paris', 'London'];

        foreach ($cities as $city) {
            City::create(['name' => $city]);
        }
    }
}
