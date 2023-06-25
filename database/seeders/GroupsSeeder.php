<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Group;
use Illuminate\Database\Seeder;

class GroupsSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $groups = [
            ['name' => 'Admin', 'type' => 'admin', 'color' => '#cc6666', 'description' => "The Admin is the system's highest authority, responsible for configuration and user management."],
            ['name' => 'Client', 'type' => 'client', 'color' => '#6666cc', 'description' => "Clients are project stakeholders with limited access, monitoring project progress and collaboration."],
            ['name' => 'Team member', 'type' => 'team', 'color' => '#66cc66', 'description' => "Team members execute tasks, collaborate, and update project status within the system."],
        ];

        foreach ($groups as $group) {
            Group::query()->updateOrCreate(['name' => $group['name']], $group);
        }
    }
}
