<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Country;
use Illuminate\Support\Str;

class CountrySeeder extends Seeder
{
    public function run(): void
    {
        $countries = json_decode(file_get_contents(database_path('data/countries.json')), true);

        foreach ($countries as $name) {
            Country::create([
                'name' => $name,
                'slug' => Str::slug($name),
            ]);
        }
    }
}