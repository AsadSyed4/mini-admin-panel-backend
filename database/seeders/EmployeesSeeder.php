<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Employee;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class EmployeesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        $companies=Company::pluck('id')->toArray();

        foreach (range(1, 40) as $index) {
            Employee::insert([
                'first_name' => $faker->firstName,
                'last_name' => $faker->lastName,
                'company_id' => $faker->randomElement($companies),
                'email' => $faker->unique()->safeEmail,
                'phone' => $faker->phoneNumber,
                'updated_at' => now(),
                'created_at' => now(),
            ]);
        }
    }
}
