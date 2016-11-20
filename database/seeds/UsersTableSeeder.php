<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            [
                'email' => 'dacsang97@gmail.com',
                'name' => 'Sang Nguyen Dac',
                'password' => Hash::make('123456'),
            ],
            [
                'email' => 'test@gmail.com',
                'name' => 'Tester',
                'password' => Hash::make('123456'),
            ]
        ];
        foreach ($users as $user) {
            App\User::create($user);
        }

    }
}