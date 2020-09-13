<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('settings')->insert(['id'=>1]);
        DB::table('users')->insert([
            'email' => 'Admin@gmail.com',
            'name' => 'admin',
            'password' => bcrypt('123456'),
            'role' => 'admin',
            'status' => '',
            'accepted' => 1,
            'new_account' => 0
        ]);
        for($i=0;$i<11;$i++){
            DB::table('users')->insert([
                'email' => $i.'@gmail.com',
                'name' => $i.'',
                'password' => bcrypt('123456'),
                'role' => 'user',
                'status' => '',
                'accepted' => 1,
                'new_account' => 0
            ]);
        }
    }
}
