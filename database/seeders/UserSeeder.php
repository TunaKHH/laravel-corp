<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;

use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory()
                ->count(50)
                ->create();

//        DB::table('users')->insert([
//            'name' => Str::random(10),
//            'deposit' => rand(500,99999),
//        ]);
    }
}
