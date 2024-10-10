<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->registerUser();
    }

    public function registerUser()
    {
        $datas = [
            [
                'name' => 'Admin',
                'email' => 'admin@example.com',
                'password' => bcrypt('password')
            ],
            [
                'name' => 'User',
                'email' => 'user@example.com',
                'password' => bcrypt('password')
            ],
        ];

        foreach ($datas as $data) {
            DB::table('users')->insert($data);
        }
    }
}
