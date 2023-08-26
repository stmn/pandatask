<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        /** @var User $user */
        $user = User::factory()->create([
            'first_name' => 'Adam',
            'last_name' => 'Smith',
            'email' => 'admin@demo.com',
            'password' => bcrypt('demo'),
        ]);

        $user->groups()->attach(1);
    }
}
