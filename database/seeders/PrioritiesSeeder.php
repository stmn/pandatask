<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Priority;
use Illuminate\Database\Seeder;

class PrioritiesSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $priorities = [
            [
                'name' => 'Low',
                'color' => '#66ff66',
            ],
            [
                'name' => 'Medium',
                'color' => '#ffff66',
            ],
            [
                'name' => 'High',
                'color' => '#ff6666',
            ],
        ];

        foreach ($priorities as $priority) {
            Priority::query()->updateOrCreate(['name' => $priority['name']], $priority);
        }
    }
}
