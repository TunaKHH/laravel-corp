<?php

namespace Database\Seeders;

use App\Models\Restaurant;

use App\Models\RestaurantPhoto;
use Illuminate\Database\Seeder;

class RestaurantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        Restaurant::factory()
            ->count(10)
            ->hasPhotos(2)
            ->create();

        RestaurantPhoto::factory()
            ->count(10)
            ->create();
    }
}
