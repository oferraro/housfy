<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class OfficeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();
        for ($i = 1; $i <= 50; $i++) {
            $office = [
                'name' => $faker->name,
                'address' => $faker->address,
            ];
            \DB::table('offices')->insert($office);
        }
    }
}
