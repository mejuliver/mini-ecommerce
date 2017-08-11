<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        DB::table('users')->insert([
            // 'username' => str_random(10),
            'username' => 'admin',
            'password' => bcrypt('admin'),
            'real_password' => 'admin',
        ]);

        DB::table('users')->insert([
            'username' => 'test_seller_1',
            'password' => bcrypt('test_seller_1'),
            'real_password' => 'test_seller_1',
        ]);


		DB::table('users')->insert([
            'username' => 'test_seller_2',
            'password' => bcrypt('test_seller_2'),
            'real_password' => 'test_seller_2',
        ]);

    }
}
