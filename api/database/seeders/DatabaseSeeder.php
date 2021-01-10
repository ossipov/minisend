<?php

namespace Database\Seeders;

use App\Models\Mail;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::factory(10)->create();
        User::factory()->create([
            'name' => 'Dmitry Ossipov',
            'email' => 'dmitry@ossipov.me',
            'password' => Hash::make('123'),
        ]);
        User::factory()->create([
            'name' => 'Regular User',
            'email' => 'any@user.is',
            'password' => Hash::make('here'),
        ]);
        Mail::factory(500)->create();
    }
}
