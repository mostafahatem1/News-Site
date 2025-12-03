<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RelatedSiteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       $faker = \Faker\Factory::create();

        for ($i = 0; $i < 5; $i++) {
            \App\Models\RelatedSite::create([
                'name' => $faker->unique()->company,
                'url' => $faker->unique()->url,
            ]);
        }   
    }
}
