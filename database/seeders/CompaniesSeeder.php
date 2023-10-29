<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class CompaniesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        foreach (range(1, 20) as $index) {
            Company::insert([
                'name' => $faker->company,
                'email' => $faker->unique()->safeEmail,
                'logo' => $faker->image('storage/app/public', 100, 100, null, false),
                'website' => $faker->url,
                'updated_at' => now(),
                'created_at' => now(),
            ]);
        }
    }
}
