<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends BaseSeeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->truncateDatabase();

        $this->call(SettingsSeeder::class);
        $this->call(GroupsSeeder::class);
        $this->call(PrioritiesSeeder::class);
        $this->call(StatusesSeeder::class);
        $this->call(AdminSeeder::class);
    }
}
