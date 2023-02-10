<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
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
        // \App\Models\User::factory(10)->create();

       User::factory()->create([
            'name' => 'User Test',
            'email' => 'secret@email.com',
            'type'  => 1,
            'status' => 1,
            'password' => Hash::make('secret123')
        ]);
    }
}
