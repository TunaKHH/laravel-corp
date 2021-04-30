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
                ->count(10)
                ->create();

//        DB::table('users')->insert([
//            'name' => "鮪魚",
//            'account' => "skw001",
//            'password' => "skw001",
//
//            'deposit' => 0,
//        ]);
    }
}
